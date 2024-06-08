<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email'
        ];
    }
}
