<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class PermissionsFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|string|min:1|max:255',
            'slug' => 'required|string|min:1|max:255',
            'description' => 'nullable',
            'created_at' => 'nullable|date_format:n/j/Y H:i A',
            'updated_at' => 'nullable|date_format:n/j/Y H:i A',
    
        ];

        return $rules;
    }
    
    /**
     * Get the request's data from the request.
     *
     * 
     * @return array
     */
    public function getData()
    {
        $data = $this->only(['name','slug','description','created_at','updated_at']);

        return $data;
    }

}