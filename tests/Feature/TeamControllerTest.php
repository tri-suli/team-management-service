<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;


use App\Models\Player;
use App\Models\PlayerSkill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use phpDocumentor\Reflection\DocBlock\Tags\Param;

class TeamControllerTest extends PlayerControllerBaseTest
{
    use RefreshDatabase;

    public function test_sample()
    {
        $requirements =
            [
                'position' => "defender",
                'mainSkill' => "speed",
                'numberOfPlayers' => 1
            ];


        $res = $this->postJson(self::REQ_TEAM_URI, $requirements);

        $this->assertNotNull($res);
    }

    /**
     * Test rule number 6
     *
     * @return void
     */
    public function test_should_receive_insufficient_number_of_players_position_message(): void
    {
        $ronaldo = Player::factory()->create(['position' => 'forward']);
        PlayerSkill::factory()->player($ronaldo)->create();
        $requirements = [
            [
                'position' => "forward",
                'mainSkill' => "speed",
                'numberOfPlayers' => 2
            ]
        ];

        $response = $this->postJson(self::REQ_TEAM_URI, $requirements);

        $response
            ->assertStatus(400)
            ->assertJson([
                'message' => 'Insufficient number of players for position: forward'
            ]);
    }

    public function test_should_receive_1_best_player_in_every_positions(): void
    {
        $this->artisan('db:seed --class=PlayersSkillsSeeder');
        $requirements = [
            [
                'position' => "forward",
                'mainSkill' => "speed",
                'numberOfPlayers' => 1
            ],
            [
                'position' => "midfielder",
                'mainSkill' => "stamina",
                'numberOfPlayers' => 1
            ],
            [
                'position' => "defender",
                'mainSkill' => "defense",
                'numberOfPlayers' => 1
            ],
        ];

        $response = $this->postJson(self::REQ_TEAM_URI, $requirements);

        $response
            ->assertStatus(200)
            ->assertJson([
                [
                    'name' => 'Kylian Mbappe',
                    'position' => 'forward',
                    'playerSkills' => [
                        ['skill' => 'attack', 'value' => 82],
                        ['skill' => 'speed', 'value' => 92],
                        ['skill' => 'stamina', 'value' => 87],
                        ['skill' => 'strength', 'value' => 76],
                    ]
                ],
                [
                    'name' => 'Kevin De Bruyne',
                    'position' => 'midfielder',
                    'playerSkills' => [
                        ['skill' => 'attack', 'value' => 82],
                        ['skill' => 'defense', 'value' => 62],
                        ['skill' => 'speed', 'value' => 79],
                        ['skill' => 'stamina', 'value' => 88],
                        ['skill' => 'strength', 'value' => 74],
                    ]
                ],
                [
                    'name' => 'Virgil Van Dijk',
                    'position' => 'defender',
                    'playerSkills' => [
                        ['skill' => 'attack', 'value' => 63],
                        ['skill' => 'defense', 'value' => 90],
                        ['skill' => 'speed', 'value' => 72],
                        ['skill' => 'stamina', 'value' => 74],
                        ['skill' => 'strength', 'value' => 93],
                    ]
                ],
            ]);
    }

    /**
     * Test rule number 2
     *
     * @dataProvider secondRuleDataProvider
     *
     * @param string $mainSkill
     * @param array $teamExpectation
     * @return void
     */
    public function test_get_best_players_in_every_positions_where_have_the_highest_skill_set(string $mainSkill, array $teamExpectation): void
    {
        $this->artisan('db:seed --class=PlayersSkillsSeeder');
        $requirements = [
            [
                'position' => "defender",
                'mainSkill' => $mainSkill,
                'numberOfPlayers' => 2
            ],
            [
                'position' => "midfielder",
                'mainSkill' => $mainSkill,
                'numberOfPlayers' => 3
            ],
            [
                'position' => "forward",
                'mainSkill' => $mainSkill,
                'numberOfPlayers' => 1
            ],
        ];

        $response = $this->postJson(self::REQ_TEAM_URI, $requirements);

        $response
            ->assertStatus(200)
            ->assertJson($teamExpectation);
    }

    public function secondRuleDataProvider(): array
    {
        $alaba = [
            'name' => 'David Alaba',
            'position' => 'defender',
            'playerSkills' => [
                ['skill' => 'attack', 'value' => 76],
                ['skill' => 'defense', 'value' => 85],
                ['skill' => 'speed', 'value' => 80],
                ['skill' => 'stamina', 'value' => 74],
                ['skill' => 'strength', 'value' => 78],
            ]
        ];

        $deBruyne = [
            'name' => 'Kevin De Bruyne',
            'position' => 'midfielder',
            'playerSkills' => [
                ['skill' => 'attack', 'value' => 82],
                ['skill' => 'defense', 'value' => 62],
                ['skill' => 'speed', 'value' => 79],
                ['skill' => 'stamina', 'value' => 88],
                ['skill' => 'strength', 'value' => 74],
            ]
        ];

        $gnabry = [
            'name' => 'Serge Gnabry',
            'position' => 'midfielder',
            'playerSkills' => [
                ['skill' => 'attack', 'value' => 74],
                ['skill' => 'defense', 'value' => 44],
                ['skill' => 'speed', 'value' => 84],
                ['skill' => 'stamina', 'value' => 76],
                ['skill' => 'strength', 'value' => 69],
            ]
        ];

        $haaland = [
            'name' => 'Erling Haaland',
            'position' => 'forward',
            'playerSkills' => [
                ['skill' => 'attack', 'value' => 76],
                ['skill' => 'defense', 'value' => 42],
                ['skill' => 'speed', 'value' => 82],
                ['skill' => 'stamina', 'value' => 81],
                ['skill' => 'strength', 'value' => 93],
            ]
        ];

        $hummels = [
            'name' => 'Mats Hummels',
            'position' => 'defender',
            'playerSkills' => [
                ['skill' => 'attack', 'value' => 71],
                ['skill' => 'defense', 'value' => 86],
                ['skill' => 'speed', 'value' => 63],
                ['skill' => 'stamina', 'value' => 66],
                ['skill' => 'strength', 'value' => 87],
            ]
        ];

        $kroos = [
            'name' => 'Toni Kroos',
            'position' => 'midfielder',
            'playerSkills' => [
                ['skill' => 'attack', 'value' => 80],
                ['skill' => 'defense', 'value' => 68],
                ['skill' => 'speed', 'value' => 66],
                ['skill' => 'stamina', 'value' => 75],
                ['skill' => 'strength', 'value' => 72],
            ]
        ];

        $mbappe = [
            'name' => 'Kylian Mbappe',
            'position' => 'forward',
            'playerSkills' => [
                ['skill' => 'attack', 'value' => 82],
                ['skill' => 'speed', 'value' => 92],
                ['skill' => 'stamina', 'value' => 87],
                ['skill' => 'strength', 'value' => 76],
            ]
        ];

        $modric = [
            'name' => 'Luka Modric',
            'position' => 'midfielder',
            'playerSkills' => [
                ['skill' => 'attack', 'value' => 76],
                ['skill' => 'defense', 'value' => 72],
                ['skill' => 'speed', 'value' => 83],
                ['skill' => 'stamina', 'value' => 83],
                ['skill' => 'strength', 'value' => 58],
            ]
        ];

        $vanDijk = [
            'name' => 'Virgil Van Dijk',
            'position' => 'defender',
            'playerSkills' => [
                ['skill' => 'attack', 'value' => 63],
                ['skill' => 'defense', 'value' => 90],
                ['skill' => 'speed', 'value' => 72],
                ['skill' => 'stamina', 'value' => 74],
                ['skill' => 'strength', 'value' => 93],
            ]
        ];

        return [
            'main skill attack' => [
                'attack',
                [
                    $alaba,
                    [
                        'name' => 'Sergio Ramos',
                        'position' => 'defender',
                        'playerSkills' => [
                            ['skill' => 'attack', 'value' => 73],
                            ['skill' => 'defense', 'value' => 82],
                            ['skill' => 'speed', 'value' => 68],
                            ['skill' => 'stamina', 'value' => 56],
                            ['skill' => 'strength', 'value' => 81],
                        ]
                    ],
                    $deBruyne,
                    $kroos,
                    $modric,
                    [
                        'name' => 'Karim Benzema',
                        'position' => 'forward',
                        'playerSkills' => [
                            ['skill' => 'attack', 'value' => 87],
                            ['skill' => 'speed', 'value' => 80],
                            ['skill' => 'stamina', 'value' => 82],
                            ['skill' => 'strength', 'value' => 82],
                        ]
                    ],
                ]
            ],
            'main skill defense' => [
                'defense',
                [
                    $vanDijk,
                    $hummels,
                    $modric,
                    $kroos,
                    $deBruyne,
                    $haaland,
                ]
            ],
            'main skill speed' => [
                'speed',
                [
                    $alaba,
                    $vanDijk,
                    [
                        'name' => 'Kingsley Coman',
                        'position' => 'midfielder',
                        'playerSkills' => [
                            ['skill' => 'attack', 'value' => 75],
                            ['skill' => 'speed', 'value' => 89],
                            ['skill' => 'stamina', 'value' => 69],
                            ['skill' => 'strength', 'value' => 66],
                        ]
                    ],
                    $gnabry,
                    $modric,
                    $mbappe,
                ]
            ],
            'main skill stamina' => [
                'stamina',
                [$alaba, $vanDijk, $deBruyne, $modric,  $gnabry, $mbappe]
            ],
            'main skill strength' => [
                'strength',
                [$vanDijk, $hummels, $deBruyne, $kroos,  $gnabry, $haaland]
            ],
        ];
    }

    /**
     * Test rule number 3
     *
     * @return void
     */
    public function test_cannot_send_requirements_with_the_same_positions_and_skills(): void
    {
        $this->artisan('db:seed --class=PlayersSkillsSeeder');
        $requirements = [
            [
                'position' => "forward",
                'mainSkill' => "speed",
                'numberOfPlayers' => 2
            ],
            [
                'position' => "forward",
                'mainSkill' => "speed",
                'numberOfPlayers' => 1
            ]
        ];

        $response = $this->postJson(self::REQ_TEAM_URI, $requirements);

        $response
            ->assertStatus(400)
            ->assertJson([
                'message' => 'Cannot send a request with two requirements with the same main skill: forward2'
            ]);
    }

    /**
     * @TODO: Fix test case number 4
     *
     * @return void
     */
    public function should_return_the_highest_main_skill_value_when_no_players_with_desired_skill_found(): void
    {
        $requirements = [
            [
                'position' => "forward",
                'mainSkill' => "strength",
                'numberOfPlayers' => 1
            ]
        ];
        $player1 = Player::factory()->create(['position' => 'forward']);
        PlayerSkill::factory()->player($player1)->create(['skill' => 'attack', 'value' => 80]);
        $player2 = Player::factory()->create(['position' => 'defender']);
        PlayerSkill::factory()->player($player2)->create(['skill' => 'defense', 'value' => 98]);
        $player3 = Player::factory()->create(['position' => 'midfielder']);
        PlayerSkill::factory()->player($player3)->create(['skill' => 'stamina', 'value' => 78]);

        $response = $this->postJson(self::REQ_TEAM_URI, $requirements);

        $response
            ->assertStatus(200)
            ->assertJson([
                [
                    'name' => $player2->name,
                    'position' => $player2->position,
                    'playerSkills' => [
                        ['skill' => 'defense', 'value' => 98],
                    ]
                ],
            ]);
    }
}
