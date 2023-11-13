<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;

use App\Models\Player;
use App\Models\PlayerSkill;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlayerControllerListingTest extends PlayerControllerBaseTest
{
    use RefreshDatabase;

    public function test_sample()
    {
        $res = $this->get(self::REQ_URI);

        $this->assertNotNull($res);
    }

    public function test_should_receive_players_collection_with_their_skills(): void
    {
        Player::factory(15)->create()->each(function (Player $player) {
            PlayerSkill::factory()->player($player)->create();
        });

        $response = $this->getJson(self::REQ_URI);

        $response
            ->assertStatus(200)
            ->assertJsonCount(15);
    }

    public function test_should_receive_empty_resource(): void
    {
        $response = $this->getJson(self::REQ_URI);

        $response
            ->assertStatus(200)
            ->assertJsonCount(0);
    }
}
