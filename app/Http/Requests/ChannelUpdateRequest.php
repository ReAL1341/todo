<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;

class ChannelUpdateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|max:14',
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
        ];
    }

    public function withValidator(Validator $validator){
        $validator->after(function ($validator){
            // チャンネル名に変更がある場合、既存のチャンネル名との重複をバリデーションする
            $id = $this->input('id');
            $currentChannelItem = DB::table('channel')->where('id',$id)->first();
            $currentChannelName = $currentChannelItem->name;
            $newChannelName = $this->input('name');
            if($newChannelName != $currentChannelName && DB::table('channel')->where('name',$newChannelName)->exists()){
                $validator->errors()
                            ->add('name','そのチャンネル名はすでに存在します');
            }
        });
    }

    protected function failedValidation($validator){
        $response['errors']  = $validator->errors()->first();
        throw new HttpResponseException(
            response()->json($response)
        );
    }

}

