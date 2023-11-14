<?php


// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;


class PlayerControllerCreateTest extends PlayerControllerBaseTest
{
    public function test_sample()
    {
        $data = [
            "name" => "test",
            "position" => "defender",
            "playerSkills" => [
                0 => [
                    "skill" => "attack",
                    "value" => 60
                ],
                1 => [
                    "skill" => "speed",
                    "value" => 80
                ]
            ]
        ];

        $res = $this->postJson(self::REQ_URI, $data);

        $this->assertNotNull($res);
    }

    public function test_successfully_create_new_player(): void
    {
        $data = [
            "name" => "Player 1",
            "position" => "midfielder",
            "playerSkills" => [
                0 => [
                    "skill" => "attack",
                    "value" => 60
                ],
                1 => [
                    "skill" => "speed",
                    "value" => 80
                ]
            ]
        ];

        $response = $this->postJson(self::REQ_URI, $data);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'name',
                'position',
                'playerSkills' => [
                    ['id', 'skill', 'value', 'playerId']
                ]
            ]);
        $this->assertDatabaseHas('players', [
            'name' => $data['name'],
            'position' => $data['position']
        ]);
        $this->assertDatabaseHas('player_skills', $data['playerSkills'][0]);
        $this->assertDatabaseHas('player_skills', $data['playerSkills'][1]);
    }

    public function test_should_receive_error_message_when_name_is_empty(): void
    {
        $response = $this->postJson(self::REQ_URI, [
            'name' => '',
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => "Invalid value for name: ",
            ]);
    }

    public function test_should_receive_error_message_when_position_is_empty(): void
    {
        $response = $this->postJson(self::REQ_URI, [
            'name' => 'Player 1',
            'position' => ''
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => "Invalid value for position: ",
            ]);
    }

    public function test_should_receive_error_message_when_player_skill_is_invalid(): void
    {
        $response = $this->postJson(self::REQ_URI, [
            'name' => 'Player 1',
            'position' => 'defender',
            'playerSkills' => [
                ['skill' => 'throwing', 'value' => 70],
            ]
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => "Invalid value for player skill: throwing",
            ]);
    }

    public function test_should_receive_error_message_when_position_value_is_valid(): void
    {
        $response = $this->postJson(self::REQ_URI, [
            'name' => 'Player 1',
            'position' => 'coach'
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => "Invalid value for position: coach",
            ]);
    }
}
