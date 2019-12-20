<?php

namespace App\Http\Requests;

use App\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
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

        return [
            'company_name' => 'required',
            //'company_website' => 'regex:/^http:\/\/\w+(\.\w+)*(:[0-9]+)?\/?$/',
            'email' => 'required|email|unique:companies,'.Rule::unique('companies')->ignore($this->id),
            //'email' => 'required|email|unique:companies,',

           // 'company_password' => 'string|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'fileToUpload' => 'mimes:jpeg,jpg,png,gif|max:10000'
        ];
    }
}
