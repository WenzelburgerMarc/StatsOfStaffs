<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public $tested = false;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->is($this->user()) || auth()->user()->isAdmin();
    }

    public function rules()
    {

        return [
            'username' => 'required|min:3|max:255',
            'name' => 'required|min:3|max:255',
            'email' => 'required|email',
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|nullable|sometimes',
            'password' => 'required|min:7|max:255|sometimes',
            'new-password' => 'nullable|min:7|max:255',
            'confirm-new-password' => 'nullable|min:7|max:255|same:new-password',
            'total_absence_days' => 'sometimes|integer|max:365',
            'remaining_absence_days' => 'sometimes|integer|max:365',
        ];
    }

    public function validateNewPassword(): void
    {
        $this->validate([
            'new-password' => 'required|min:7|max:255',
            'confirm-new-password' => 'required|min:7|max:255|same:new-password',
        ]);
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(redirect('/')->with('error', 'You are not authorized to perform this action'));
    }
}
