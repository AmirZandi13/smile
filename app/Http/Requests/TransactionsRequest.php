<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'account_number' => $this->accountNumberValidation()
        ];
    }

    /**
     * @return string[]
     */
    public function accountNumberValidation(): array
    {
        return ['required', 'string', 'min:8', 'max:8', 'exists:App\Models\Account,account_number'];
    }
}
