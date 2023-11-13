<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;

use App\Models\Player;
use App\Models\PlayerSkill;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlayerControllerDeleteTest extends PlayerControllerBaseTest
{
    use RefreshDatabase;

    public function test_sample()
    {
        $res = $this->delete(self::REQ_URI . '1');

        $this->assertNotNull($res);
    }

    public function test_successfully_delete_player_record(): void
    {
        $player = Player::factory()->create();
        $skill = PlayerSkill::factory()->create(['player_id' => $player]);

        $response = $this->deleteJson(self::REQ_URI . $player->id, [], [
            'Authorization' => sprintf('Bearer %s', config('api.token.delete'))
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('players', $player->toArray());
        $this->assertDatabaseMissing('player_skills', $skill->toArray());
    }

    public function test_delete_player_failed_because_entity_not_found(): void
    {
        $response = $this->deleteJson(self::REQ_URI . 1, [], [
            'Authorization' => sprintf('Bearer %s', config('api.token.delete'))
        ]);

        $response
            ->assertStatus(404)
            ->assertJsonStructure(['message']);
    }

    public function test_should_return_unauthorized_response(): void
    {
        $player = Player::factory()->create();

        $response = $this->deleteJson(self::REQ_URI . $player->id, [], [
            'Authorization' => sprintf('Bearer %s', '123456')
        ]);

        $response->assertStatus(403);
    }
}
