<?php

namespace App\Http\Requests;

use App\Rules\IsIsbn;

class SaveBookRequest extends ApiFormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            // 'isbn' => ['required', 'unique:books', 'regex:/^(97(8|9))?\d{9}(\d|X)$/'],
            'isbn' => ['required', 'unique:books', new IsIsbn, 'sometimes'],
            'title' => 'max:200',
            'year' => 'required',
            // 'authors' => 'required|array',
            // 'authors' => 'integer'
        ];
    }

    // if title == bad, throw exception
    public function withValidator($validator) {
        $validator->after(function ($validator) { 
            if ($this->input('title') == "bad") {
                $validator->errors()->add('field', 'Something is wrong with this field!'); 
            }
    }); }
}
