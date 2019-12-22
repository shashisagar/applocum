<?php

namespace App\Http\Requests;

use App\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateCompanyPost extends FormRequest
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
        $id = $this->request->get('company_id_update');
        return [
            'company_name' => 'required',
            'company_website' => "required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/",
            'email' => 'required|email|regex:"^[_A-Za-z0-9-\\+]+(\\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$"|unique:companies,email,'.$id,
            'company_password'     => 'nullable|regex:"^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"',
            'fileToUpload' => 'nullable|mimes:jpeg,jpg,png|max:10240'
        ];
    }

    public function messages()
    {
        return [
            'company_name.required'=>'Please enter name',
            'company_website.regex' =>'Please enter valid website',
            'company_website.required' =>'Please enter website address',
            'email.regex' =>'Please enter valid email address',
            'email.required' =>'Please enter email address',
            'company_password.required' => 'Please enter password',
            'company_password.regex' => 'Password must contain at least 1 uppercase letter, 1 special character and 1 number.',
            'fileToUpload.max' =>'File must be less than 10Mb'
        ];
    }
}
