<?php

// /////////////////////////////////////////////////////////////////////////////
// PLEASE DO NOT RENAME OR REMOVE ANY OF THE CODE BELOW. 
// YOU CAN ADD YOUR CODE TO THIS FILE TO EXTEND THE FEATURES TO USE THEM IN YOUR WORK.
// /////////////////////////////////////////////////////////////////////////////

namespace App\Http\Controllers;

use App\Models\PlayerSkill;
use App\Models\Player;
use Illuminate\Http\Request;
class PlayerController extends Controller
{
    public function index()
    {
        return response("Failed", 500);
    }

    public function show()
    {
        return response("Failed", 500);
    }

    public function store(Request $request)
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

    public function update()
    {
        return response("Failed", 500);
    }

    public function destroy()
    {
        return response("Failed", 500);
    }
}
