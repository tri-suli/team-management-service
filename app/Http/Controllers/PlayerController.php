<?php

// /////////////////////////////////////////////////////////////////////////////
// PLEASE DO NOT RENAME OR REMOVE ANY OF THE CODE BELOW.
// YOU CAN ADD YOUR CODE TO THIS FILE TO EXTEND THE FEATURES TO USE THEM IN YOUR WORK.
// /////////////////////////////////////////////////////////////////////////////

namespace App\Http\Controllers;

use App\Http\Requests\Player\StorePlayerRequest;
use App\Models\PlayerSkill;
use App\Models\Player;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PlayerController extends Controller
{
    public function index()
    {
        $players = Player::with('skills')->get();

        return response()->json($players->transform(fn (Player $player) => [
            ...$player->only(['id', 'name', 'position']),
            'playerSkills' => $player->skills->transform(fn (PlayerSkill $playerSkill) => [
                ...$playerSkill->only(['id', 'skill', 'value']),
                'playerId' => $playerSkill->player_id
            ])->toArray()
        ]));
    }

    public function show(int $id)
    {
        $player = Player::with('skills')->find($id);

        if (! ($player instanceof Player)) {
            return response()->json([
                'message' => 'Entity Not Found!'
            ], 404);
        }

        return response()->json([
            ...$player->only(['id', 'name', 'position']),
            'playerSkills' => $player->skills->transform(fn (PlayerSkill $playerSkill) => [
                ...$playerSkill->only(['id', 'skill', 'value']),
                'playerId' => $playerSkill->player_id
            ])->toArray()
        ]);
    }

    /**
     * Handle the request to create a new player resource
     *
     * @param StorePlayerRequest $request
     * @return Response|JsonResponse
     */
    public function store(StorePlayerRequest $request): Response|JsonResponse
    {
        $name = $request->get('name');
        $position = $request->get('position');
        $skills = $request->get('playerSkills');

        $player = Player::create([
            'name' => $name,
            'position' => $position,
        ]);

        if ($player instanceof Player) {
            $skills = $player->skills()->createMany($skills);
            return response()->json([
                'name' => $name,
                'position' => $position,
                'playerSkills' => $skills->transform(fn (PlayerSkill $playerSkill) => [
                    'skill' => $playerSkill->skill,
                    'value' => $playerSkill->value
                ])->toArray()
            ], 201);
        }

        return response("Failed", 500);
    }

    public function update(Request $request, int $id)
    {
        $name = $request->get('name');
        $position = $request->get('position');
        $playerSkills = $request->get('playerSkills');

        $player = Player::select([
            'id', 'name', 'position'
        ])->with('skills')->find($id);

        if ($player instanceof Player) {
            $skills = [];
            $player->update([
                'name' => $name,
                'position' => $position
            ]);

            foreach ($playerSkills as $playerSkill) {
                list($skill, $value) = array_values($playerSkill);
                $skill = $player->skills()->updateOrCreate(
                    ['player_id' => $player->id, 'skill' => $skill],
                    ['skill' => $skill, 'value' => $value, 'player_id' => $player->id]
                );
                $skills[] = [
                    ...$skill->only(['id', 'skill', 'value']),
                    'playerId' => $player->id
                ];
            }

            return response()->json([
                'id' => $player->id,
                'name' => $name,
                'position' => $position,
                'playerSkills' => $skills
            ]);
        }

        return response("Failed", 500);
    }

    public function destroy(int $id)
    {
        $player = Player::find($id);

        if (! ($player instanceof Player)) {
            return response()->json([
                'message' => 'Entity Not Found!'
            ], 404);
        }

        $deleted = $player->delete();

        if ($deleted) {
            return response()->json([
                'message' => 'Record deleted successfully!'
            ]);
        }

        return response("Failed", 500);
    }
}
