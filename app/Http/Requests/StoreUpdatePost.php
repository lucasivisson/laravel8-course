<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule as ValidationRule;

class StoreUpdatePost extends FormRequest
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
        $id = $this->segment(2); // Essa linha pega uma parte do nosso endpoint, nesse caso, ela vai pegar o id do endpoint da rota de edit.

        $rules = [
            // 'title' => "required|min:3|max:160|unique:posts,title,{$id},id",
            'title' => [
                'required',
                'min:3',
                'max:160',
                // "unique:posts,title,{$id},id"
                ValidationRule::unique('posts')->ignore($id),
            ],
            'content' => ['nullable', 'min:5', 'max:10000'],
            'image' => ['required', 'image'],
        ];

        if ($this->method() == 'PUT')
        {
            $rules['image'] = ['nullable', 'image'];
        }

        return $rules;
    }
}
