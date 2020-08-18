<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssetStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->is_superuser;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'name' => strtolower($this->name)
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =[
            'name' => 'string|required|unique:assets,name',
            'description' => 'string|required'
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])){
            $rules = [
                'name' => 'string|required',
                'description' => 'string|required'
            ];
        }
        return $rules;
    }


}
