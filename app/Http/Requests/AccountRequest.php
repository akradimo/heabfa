<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'کد' => 'required|string|unique:accounts,کد,' . $this->id,
            'عنوان' => 'required|string|max:255',
            'نوع_حساب' => 'required|string|in:دارایی,بدهی,درآمد,هزینه,سرمایه',
            'حساب_والد' => 'nullable|exists:accounts,id',
            'توضیحات' => 'nullable|string',
            'فعال' => 'boolean',
            'شرکت_id' => 'required|exists:companies,id'
        ];
    }

    public function messages()
    {
        return [
            'کد.required' => 'کد حساب الزامی است',
            'کد.unique' => 'این کد حساب قبلاً ثبت شده است',
            'عنوان.required' => 'عنوان حساب الزامی است',
            'نوع_حساب.required' => 'نوع حساب الزامی است',
            'نوع_حساب.in' => 'نوع حساب نامعتبر است'
        ];
    }
}