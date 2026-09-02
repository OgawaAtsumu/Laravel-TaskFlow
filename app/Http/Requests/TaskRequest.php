<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
        {
            return true;
        }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
        {
            return [
                'category_id' => [
                    'required',
                    'integer',
                    'exists:categories,id',
                ],
                'title' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'description' => [
                    'nullable',
                    'string',
                    'max:2000',
                ],
                'status' => [
                    'required',
                    'in:not_started,in_progress,completed',
                ],
                'priority' => [
                    'required',
                    'in:low,medium,high',
                ],
                'due_date' => [
                    'nullable',
                    'date_format:Y-m-d',
                ],
            ];
        }

    public function messages(): array
        {
        return [
            'category_id.required' =>
                'カテゴリを選択してください。',
            'category_id.integer' =>
                'カテゴリの指定が正しくありません。',
            'category_id.exists' =>
                '選択したカテゴリが存在しません。',

            'title.required' =>
                'タイトルは必須です。',
            'title.string' =>
                'タイトルは文字列で入力してください。',
            'title.max' =>
                'タイトルは255文字以内で入力してください。',

            'description.string' =>
                '詳細は文字列で入力してください。',
            'description.max' =>
                '詳細は2000文字以内で入力してください。',

            'status.required' =>
                'ステータスを選択してください。',
            'status.in' =>
                '選択したステータスが正しくありません。',

            'priority.required' =>
                '優先度を選択してください。',
            'priority.in' =>
                '選択した優先度が正しくありません。',

            'due_date.date_format' =>
                '期限は日付形式で入力してください。',
            ];
        }
}
