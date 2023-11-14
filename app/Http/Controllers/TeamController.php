<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\PlayerSkill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $requirements = $request->all();

        if (Arr::isAssoc($request->all())) {
            $requirements = [$requirements];
        }

        $teams = Collection::make();

        $currentRequirement = [];
        foreach ($requirements as $i => $requirement) {
            $position = $requirement['position'];
            $mainSkill = $requirement['mainSkill'];
            $numberOfPlayers = $requirement['numberOfPlayers'];

            if ($i >= 1) {
                if ($currentRequirement['position'] === $position && $currentRequirement['mainSkill'] === $mainSkill) {
                    return response()->json([
                        'message' => sprintf('Cannot send a request with two requirements with the same main skill: %s%s', $position, $i+1)
                    ], 400);
                }
            }

            $players = Player::select([
                'name',
                'position',
                DB::raw("case when player_skills.skill = 'attack' then player_skills.value else (select value from player_skills where player_id = players.id and skill = 'attack') end as 'attack'"),
                DB::raw("case when player_skills.skill = 'defense' then player_skills.value else (select value from player_skills where player_id = players.id and skill = 'defense') end as 'defense'"),
                DB::raw("case when player_skills.skill = 'speed' then player_skills.value else (select value from player_skills where player_id = players.id and skill = 'speed') end as 'speed'"),
                DB::raw("case when player_skills.skill = 'stamina' then player_skills.value else (select value from player_skills where player_id = players.id and skill = 'stamina') end as 'stamina'"),
                DB::raw("case when player_skills.skill = 'strength' then player_skills.value else (select value from player_skills where player_id = players.id and skill = 'strength') end as 'strength'"),
            ])->join('player_skills', 'players.id', '=', 'player_skills.player_id')
                ->where([
                    ['players.position', '=', $position],
                    ['player_skills.skill', '=', $mainSkill]
                ])
                ->orderBy('player_skills.value', 'desc')
                ->take($numberOfPlayers)
                ->get()
                ->transform(function (Player $player) {
                    $newPlayer = new Player();
                    $newPlayer->name = $player->name;
                    $newPlayer->position = $player->position;
                    $newPlayer->playerSkills = Collection::make(
                        $player->only(['attack', 'defense', 'speed', 'stamina', 'strength'])
                    )->filter(fn ($value) => !is_null($value))
                        ->reduce(function (Collection $playerSkills, int|null $value, string $skill) {
                        $playerSkill = new PlayerSkill;
                        $playerSkill->skill = $skill;
                        $playerSkill->value = $value;

                        $playerSkills->push($playerSkill);
                        return $playerSkills;
                    }, Collection::make([]))
                    ->toArray();
                    return $newPlayer;
                });

            if ($players->count() < $numberOfPlayers) {
                return response()->json([
                    'message' => sprintf('Insufficient number of players for position: %s', $position)
                ], 400);
            }

            $teams->push(...$players->toArray());
            $currentRequirement = $requirement;
        }

        return response()->json($teams->toArray());
    }
}
