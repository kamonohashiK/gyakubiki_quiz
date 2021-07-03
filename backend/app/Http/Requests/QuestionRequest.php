<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
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
            'answer' => ['required'],
            'question' => ['required', 'min:10'],
        ];
    }

    public function messages(){
        return [
            'answer.required'  => '答えが設定されていません。',
            'question.required'  => '問題を入力してください。',
            'question.min'  => '問題文は10文字以上で入力してください。',
        ];
    }
}
