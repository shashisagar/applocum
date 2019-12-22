<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserCompanyPost extends FormRequest
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
        $id = $this->request->get('employee_id_update');
        return [
            'employee_name' => 'required',
            'email' => 'required|email|regex:"^[_A-Za-z0-9-\\+]+(\\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$"|unique:companies,email,'.$id,
            'employee_phone' => 'required|digits:11'

        ];
    }

    public function messages()
    {
        return [
            'employee_name.required'=>'Please enter name',
            'email.required'=>'Please enter email address',
            'employee_phone.required' => 'Please enter phone number',
            'employee_phone.digits' => 'Please enter valid phone number,numeric and must 11 in length',
            'email.regex' => 'Please enter valid email address',
        ];
    }
}
