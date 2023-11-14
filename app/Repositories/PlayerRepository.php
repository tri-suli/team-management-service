<?php

namespace App\Repositories;

use App\Models\Player;
use Illuminate\Database\Eloquent\Collection;

class PlayerRepository
{
    private Player $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    public function create(string $name, string $position): Player
    {
        return $this->player->create([
            'name' => $name,
            'position' => $position
        ]);
    }

    public function addSkills(Player $player, array $skills): Collection
    {
        return $player->skills()->createMany($skills);
    }
}
