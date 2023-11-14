<?php

namespace App\Http\Requests\Player;

use App\Rules\PlayerPositionRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
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
            'position' => ['required', new PlayerPositionRule()]
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        if ($this->expectsJson()) {
            $errors = (new ValidationException($validator))->errors();
            $fields = array_keys($errors);

            throw new HttpResponseException(
                response()->json([
                    'message' => sprintf('Invalid value for %s: %s', $fields[0], $this->get($fields[0])),
                ], 422)
            );
        }

        parent::failedValidation($validator);
    }
}
