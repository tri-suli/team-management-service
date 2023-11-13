<?php

namespace Tests\Feature;

use App\Models\Player;
use App\Models\PlayerSkill;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlayerControllerShowTest extends PlayerControllerBaseTest
{
    use RefreshDatabase;

    public function test_should_receive_player_details_by_player_id(): void
    {
        $player = Player::factory()->create();
        $skills = PlayerSkill::factory(3)->create(['player_id' => $player]);

        $response = $this->getJson(self::REQ_URI . $player->id);

        $response
            ->assertStatus(200)
            ->assertJson([
                ...$player->only(['id', 'name', 'position']),
                'playerSkills' => $skills->transform(fn (PlayerSkill $playerSkill) => [
                    ...$playerSkill->only(['id', 'skill', 'value']),
                    'playerId' => $playerSkill->player_id
                ])->toArray()
            ]);
    }

    public function test_should_receive_404_status_code(): void
    {
        $response = $this->getJson(self::REQ_URI . 1);

        $response->assertStatus(404);
    }
}
