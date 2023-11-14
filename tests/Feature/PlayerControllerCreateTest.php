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
