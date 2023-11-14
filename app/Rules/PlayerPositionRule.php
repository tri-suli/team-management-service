<?php

namespace App\Rules;

use App\Contracts\Enum\PlayerPosition;
use Illuminate\Contracts\Validation\Rule;

class PlayerPositionRule implements Rule
{
    /**
     * The field name that using this rule
     *
     * @var string
     */
    public string $attribute;

    /**
     * Rule defender
     *
     * @var PlayerPosition
     */
    protected PlayerPosition $defender;

    /**
     * Rule forward
     *
     * @var PlayerPosition
     */
    protected PlayerPosition $striker;

    /**
     * Rule midfielder
     *
     * @var PlayerPosition
     */
    protected PlayerPosition $midfielder;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->defender = PlayerPosition::DFD;
        $this->midfielder = PlayerPosition::MFD;
        $this->striker = PlayerPosition::FWD;
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
            $this->defender->value,
            $this->midfielder->value,
            $this->striker->value
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
