<?php

namespace App\Http\Requests\Player;

use App\Rules\PlayerPositionRule;
use App\Rules\PlayerSkillRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class StorePlayerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['bail', 'required'],
            'position' => ['bail', 'required', new PlayerPositionRule()],
            'playerSkills' => ['array', 'required'],
            'playerSkills.*.skill' => ['bail', 'required', new PlayerSkillRule()],
            'playerSkills.*.value' => ['bail', 'required', 'min:1', 'max:100']
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        if ($this->expectsJson()) {
            $errors = (new ValidationException($validator))->errors();
            $fields = array_keys($errors);
            $field = $fields[0];
            $value = $this->get($fields[0]);

            if (str_contains($field, '.skill') || str_contains($field, '.value')) {
                $field = 'player skill';
                $value = Arr::get($this->all(), $fields[0]);
            }

            throw new HttpResponseException(
                response()->json([
                    'message' => sprintf('Invalid value for %s: %s', $field, $value),
                ], 422)
            );
        }

        parent::failedValidation($validator);
    }
}
