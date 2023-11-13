<?php

namespace Tests\Unit\Enums;

use App\Contracts\Enum\PlayerSkill;
use PHPUnit\Framework\TestCase;

class PlayerSkillsTest extends TestCase
{
    /**
     * @dataProvider skillsDataProvider
     *
     * @param PlayerSkill $actualSkill
     * @param string $expectedSkill
     * @return void
     */
    public function test_get_player_skills_value(PlayerSkill $actualSkill, string $expectedSkill): void
    {
        $this->assertEquals($expectedSkill, $actualSkill->value);
    }

    public static function skillsDataProvider(): array
    {
        return [
            'test attack attribute' => [PlayerSkill::ATTACK, 'attack'],
            'test defense attribute' => [PlayerSkill::DEFENSE, 'defense'],
            'test speed attribute' => [PlayerSkill::SPEED, 'speed'],
            'test stamina attribute' => [PlayerSkill::STAMINA, 'stamina'],
            'test strength attribute' => [PlayerSkill::STRENGTH, 'strength'],
        ];
    }
}
