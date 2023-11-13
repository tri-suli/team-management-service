<?php

namespace Tests\Unit\Enums;

use App\Contracts\Enum\PlayerPosition;
use PHPUnit\Framework\TestCase;

class PlayerPositionTest extends TestCase
{
    /**
     * @dataProvider skillsDataProvider
     *
     * @param PlayerPosition $actualPosition
     * @param string $expectedPosition
     * @return void
     */
    public function test_get_player_positions_value(PlayerPosition $actualPosition, string $expectedPosition): void
    {
        $this->assertEquals($expectedPosition, $actualPosition->value);
    }

    public static function skillsDataProvider(): array
    {
        return [
            'test dfd attribute' => [PlayerPosition::DFD, 'defender'],
            'test fwd attribute' => [PlayerPosition::FWD, 'forward'],
            'test mfd attribute' => [PlayerPosition::MFD, 'midfielder'],
        ];
    }
}
