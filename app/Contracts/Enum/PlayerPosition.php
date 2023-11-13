<?php

namespace App\Contracts\Enum;

enum PlayerPosition: string
{
    /**
     * Define a constant value for defender player's position
     *
     * @var string
     */
    case DFD = 'defender';

    /**
     * Define a constant value for forward player's position
     *
     * @var string
     */
    case FWD = 'forward';

    /**
     * Define a constant value for midfielder player's position
     *
     * @var string
     */
    case MFD = 'midfielder';
}
