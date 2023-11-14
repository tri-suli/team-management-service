<?php

namespace App\Rules;

use App\Contracts\Enum\PlayerSkill;
use Illuminate\Contracts\Validation\Rule;

class PlayerSkillRule implements Rule
{
    /**
     * The field name that using this rule
     *
     * @var string
     */
    public string $attribute = 'player skill';

    /**
     * Rule attack
     *
     * @var PlayerSkill
     */
    protected PlayerSkill $attack;

    /**
     * Rule defense
     *
     * @var PlayerSkill
     */
    protected PlayerSkill $defense;

    /**
     * Rule speed
     *
     * @var PlayerSkill
     */
    protected PlayerSkill $speed;

    /**
     * Rule stamina
     *
     * @var PlayerSkill
     */
    protected PlayerSkill $stamina;

    /**
     * Rule strength
     *
     * @var PlayerSkill
     */
    protected PlayerSkill $strength;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->attack = PlayerSkill::ATTACK;
        $this->defense = PlayerSkill::DEFENSE;
        $this->speed = PlayerSkill::SPEED;
        $this->stamina = PlayerSkill::STAMINA;
        $this->strength = PlayerSkill::STRENGTH;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $this->attribute = $attribute;

        return in_array($value, [
            $this->attack->value,
            $this->defense->value,
            $this->speed->value,
            $this->stamina->value,
            $this->strength->value,
        ]);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return __('validation.in', ['attribute' => $this->attribute]);
    }
}
