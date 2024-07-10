<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if(auth()->id() == $this->user->id || auth()->user()->role == 'administrator')
        return true;
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       
        return [
            'name'=>'required|max:255',
            'email'=>'required|email|unique:users,email,'.$this->user->id,
            'role'=> ['sometimes',Rule::in(['user', 'administrator'])],
        ];
    }

    public function prepareForValidation(){
        
        if(auth()->user()->role == 'administrator' &&  auth()->user()->id == $this->user->id)
        {
            $this->merge(['role'=>'administrator']);
     }
    }
}
