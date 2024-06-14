<?php

namespace App\Http\Requests;

use App\Exceptions\AlreadyExistsException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:subscribers'],
        ];
    }

    /**
     * @throws AlreadyExistsException
     */
    protected function failedValidation(Validator $validator)
    {
        if (in_array('Unique', array_keys($validator->failed()['email']))) {
            throw new AlreadyExistsException();
        }

        parent::failedValidation($validator);
    }
}
