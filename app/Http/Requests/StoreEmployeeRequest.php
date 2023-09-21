<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->isAdmin();
    }

    public function rules()
    {
        return [
            'username' => 'required|min:3|max:255',
            'name' => 'required|min:3|max:255',
            'email' => 'required|email',
            'password' => 'required|min:7|max:255',
            'avatar' => 'image',
            'role' => 'required',
        ];
    }
}
