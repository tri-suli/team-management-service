<?php

namespace App\Contracts\Enum;

enum PlayerSkill: string
{
    /**
     * Define a constant value for skill attack
     *
     * @var string
     */
    case ATTACK = 'attack';

    /**
     * Define a constant value for skill defense
     *
     * @var string
     */
    case DEFENSE = 'defense';

    /**
     * Define a constant value for skill speed
     *
     * @var string
     */
    case SPEED = 'speed';

    /**
     * Define a constant value for skill stamina
     *
     * @var string
     */
    case STAMINA = 'stamina';

    /**
     * Define a constant value for skill strength
     *
     * @var string
     */
    case STRENGTH = 'strength';
}
