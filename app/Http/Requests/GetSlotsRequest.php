<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'ids' => ['required', 'array'], // employees ids array
            'earliest' => ['required', 'date','before:latest'], // date from
            'latest' => ['required', 'date','after:earliest'], // date overdue
            'length' => ['required', 'integer','min:1'], // period of the meeting
            'startAt' => ['required', 'integer','min:0', 'max:23', 'lt:endAt'], // work hour start at
            'endAt' => ['required', 'integer','min:0', 'max:24', 'gt:startAt'] // work hour end at
        ];
    }

    // public function attributes()
    // {
    //     return ['category_id' => 'category'];
    // }


    public function messages()
    {
        return [
            'startAt.lt'=> 'start at must be less than end at',
            'endAt.gt'=> 'start at must be less than start at',
        ];
    }
}
