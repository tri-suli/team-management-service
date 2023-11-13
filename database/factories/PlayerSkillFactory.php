<?php

namespace Database\Factories;

use App\Models\Player;
use App\Models\PlayerSkill;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PlayerSkill>
 */
class PlayerSkillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            'skill' => $this->faker->randomElement([
                'attack', 'defense', 'speed', 'stamina', 'strength'
            ]),
            'value' => random_int(30, 100),
        ];
    }

    /**
     * Assign skill to specific player's.
     *
     * @param Player $player
     * @return Factory
     */
    public function player(Player $player): Factory
    {
        return $this->state(fn () => [
            'player_id' => $player->id,
        ]);
    }
}
