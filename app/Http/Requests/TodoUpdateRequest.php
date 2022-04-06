<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\DB;

class TodoUpdateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'todo_content' => 'required|max:40',
            'deadline_month' => 'required_with:deadline_date,deadline_time',
            'deadline_date' => 'required_with:deadline_month,deadline_time',
        ];
    }

    public function attributes()
    {
        return [
            'todo_content' => 'タスク',
            'deadline_month' => '月',
            'deadline_date' => '日',
        ];
    }

    public function messages(){
        return [
            'todo_content.required' => ':attributeを入力してください',
            'todo_content.max' => ':attributeは40字以内にしてください',
            'deadline_month.required_with' => '「:attribute」を入力してください',
            'deadline_date.required_with' => '「:attribute」を入力してください',
        ];
    }

    protected function passedValidation()
    {
        //「1」➡「01」のように
        // 一桁の整数値の最初に「0」をつける
        $month = $this->input('deadline_month');
        $date = $this->input('deadline_date');
        if($month != '' && (int)$month < 10){
            $month = '0'.$month;
        }
        if($date != '' && (int)$date < 10){
            $date = '0'.$date;
        }
        $this->merge([
            'deadline_month' => $month,
            'deadline_date' => $date,
        ]);
    }

    
    public function withValidator(Validator $validator){
        $validator->after(function ($validator){
            $month = $this->input('deadline_month');
            $date = $this->input('deadline_date');

            if($month != '' && $date != ''){
                
                // 1年以内に存在する日付かどうかをバリデーションする
                $day = $month.'-'.$date;
                $nowYear = date('Y');
                $today = strtotime(date("Y-m-d"));
                if(strtotime($nowYear.'-'.$day) < $today){
                    $year = (int)$nowYear + 1 ;
                }
                else{
                    $year = (int)$nowYear;
                }

                if(!checkdate((int)$month,(int)$date,$year)){
                    $validator->errors()
                                ->add('deadline_month_date', '1年以内に存在する日付にしてください');
                }
            }

            // タスクに変更がある場合、既存のタスクとの重複をバリデーションする
            $id = $this->input('id');
            $currentTodoItem = DB::table('todo')->where('id',$id)->first();
            $currentTodoContent = $currentTodoItem->todo_content;
            $newTodoContent = $this->input('todo_content');
            if($newTodoContent != $currentTodoContent && DB::table('todo')->where('todo_content',$newTodoContent)->exists()){
                $validator->errors()
                            ->add('todo_content','そのタスクはすでに存在します');
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
