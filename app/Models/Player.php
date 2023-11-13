<?php

// /////////////////////////////////////////////////////////////////////////////
// PLEASE DO NOT RENAME OR REMOVE ANY OF THE CODE BELOW.
// YOU CAN ADD YOUR CODE TO THIS FILE TO EXTEND THE FEATURES TO USE THEM IN YOUR WORK.
// /////////////////////////////////////////////////////////////////////////////

namespace App\Models;

use App\Casts\PlayerPositionCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer $id
 * @property string $name
 * @property PlayerPositionCast $position
 * @property PlayerSkill $skill
 */
class Player extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'position'
    ];

    protected $casts = [
        'position' => PlayerPositionCast::class
    ];

    protected $with = ['skills'];

    public function skills(): HasMany
    {
        return $this->hasMany(PlayerSkill::class);
    }
}
