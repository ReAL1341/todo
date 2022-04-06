<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ChannelInputRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|max:14|unique:channel',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'チャンネル名',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => ':attributeを入力してください',
            'name.max' => ':attributeは14字以内にしてください',
            'name.unique' => 'その:attributeはすでに存在します',
        ];
    }

    protected function failedValidation($validator){
        $response['errors']  = $validator->errors()->first();
        throw new HttpResponseException(
            response()->json($response)
        );
    }

}
