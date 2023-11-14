<?php

namespace Database\Seeders;

use App\Models\Player;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class PlayersSkillsSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Generate forward players
        foreach ($this->getPlayersName() as $position => $names) {
            foreach ($names as $name) {
                $skills = Collection::make($this->getPlayersSkills($name))->transform(function (int $value, string $skill) {
                    return ['skill' => $skill, 'value' => $value];
                })->values();
                $player = Player::where('name', $name)->where('position', $position)->first();
                if (! ($player instanceof Player)) {
                    $player = Player::create(['name' => $name, 'position' => $position]);
                    $player->skills()->createMany($skills->toArray());
                }
            }
        }
    }

    public function getPlayersName(): array
    {
        return [
            'defender' => [
                'David Alaba',
                'Gerard Pique',
                'John Stones',
                'Mats Hummels',
                'Sergio Ramos',
                'Virgil Van Dijk',
            ],
            'forward' => [
                'Cristiano Ronaldo',
                'Erling Haaland',
                'Karim Benzema',
                'Lionel Messi',
                'Kylian Mbappe',
                'Neymar',
                'Robert Lewandowski',
            ],
            'midfielder' => [
                'Kevin De Bruyne',
                'Kingsley Coman',
                'Luka Modric',
                'Mesut Özil',
                'Serge Gnabry',
                'Toni Kroos',
            ],
        ];
    }

    public function getPlayersSkills(string $playerName): array
    {
        return [
            'Cristiano Ronaldo'   => [
                'attack'    => 86,
                'speed'     => 80,
                'strength'  => 77,
                'stamina'   => 76
            ],
            'David Alaba'     => [
                'attack'    => 76,
                'defense'   => 85,
                'speed'     => 80,
                'stamina'   => 74,
                'strength'  => 78
            ],
            'Erling Haaland' => [
                'attack'    => 76,
                'defense'   => 42,
                'speed'     => 82,
                'stamina'   => 81,
                'strength'  => 93
            ],
            'Gerard Pique'   => [
                'attack'    => 68,
                'defense'   => 83,
                'speed'     => 56,
                'strength'  => 83,
                'stamina'   => 61
            ],
            'John Stones'   => [
                'attack'    => 65,
                'defense'   => 84,
                'speed'     => 70,
                'strength'  => 78,
                'stamina'   => 73
            ],
            'Karim Benzema'   => [
                'attack'    => 87,
                'speed'     => 80,
                'strength'  => 82,
                'stamina'   => 82
            ],
            'Kevin De Bruyne'  => [
                'attack'    => 82,
                'defense'   => 62,
                'speed'     => 79,
                'strength'  => 74,
                'stamina'   => 88
            ],
            'Kingsley Coman'  => [
                'attack'    => 75,
                'speed'     => 89,
                'strength'  => 66,
                'stamina'   => 69
            ],
            'Kylian Mbappe'    => [
                'attack'    => 82,
                'speed'     => 92,
                'strength'  => 76,
                'stamina'   => 87
            ],
            'Lionel Messi'     => [
                'attack'    => 85,
                'speed'     => 88,
                'strength'  => 68,
                'stamina'   => 70
            ],
            'Luka Modric'     => [
                'attack'    => 76,
                'defense'   => 72,
                'speed'     => 83,
                'strength'  => 58,
                'stamina'   => 83
            ],
            'Mats Hummels'    => [
                'attack'    => 71,
                'defense'   => 86,
                'speed'     => 63,
                'strength'  => 87,
                'stamina'   => 66
            ],
            'Mesut Özil'    => [
                'attack'    => 73,
                'speed'     => 70,
            ],
            'Neymar'    => [
                'attack'    => 80,
                'speed'     => 87,
                'strength'  => 52,
                'stamina'   => 79
            ],
            'Robert Lewandowski'    => [
                'attack'    => 86,
                'speed'     => 81,
                'strength'  => 87,
                'stamina'   => 76
            ],
            'Serge Gnabry'   => [
                'attack'    => 74,
                'defense'   => 44,
                'speed'     => 84,
                'strength'  => 69,
                'stamina'   => 76
            ],
            'Sergio Ramos'   => [
                'attack'    => 73,
                'defense'   => 82,
                'speed'     => 68,
                'strength'  => 81,
                'stamina'   => 56
            ],
            'Toni Kroos'    => [
                'attack'    => 80,
                'defense'   => 68,
                'speed'     => 66,
                'strength'  => 72,
                'stamina'   => 75
            ],
            'Virgil Van Dijk'    => [
                'attack'    => 63,
                'defense'   => 90,
                'speed'     => 72,
                'strength'  => 93,
                'stamina'   => 74
            ],
        ][$playerName];
    }
}
