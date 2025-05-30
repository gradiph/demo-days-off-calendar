<?php

namespace App\Http\Requests\Calendar;

use App\Rules\UniqueOffDay;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class NewDaysOffRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->input('userId') === Auth::id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'newOffDays' => ['required', 'array'],
            'newOffDays.*' => ['required', 'date', new UniqueOffDay, 'after:today'],
            'userId' => ['required', 'exists:users,id'],
            'reason' => ['required', 'string', 'max:64'],
        ];
    }
}
