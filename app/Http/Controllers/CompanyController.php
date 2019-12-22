<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\StoreCompanyPost;
use App\Http\Requests\UpdateCompanyPost;
use App\Mail\CompanyCreationEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Image;
use Storage;
use Yajra\DataTables\DataTables;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('company.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCompanyPost $request)
    {
        $name = $request->company_name;
        $email = $request->email;
        $website = $request->company_website;
        $password = $request->company_password;
        $password = Hash::make($password);
        $storeArray = array();
        if ($_FILES['fileToUpload']['size'] != 0) {
            $image = $_FILES['fileToUpload'];
            $array = explode('.', $image['name']);
            $extension = end($array);
            $logoImageURL = uploadImageToStoragePath($image['tmp_name'], 'company', 'company-logo' . rand() . '-' . time() . '.' . $extension, 100
                , 100);
            $storeArray['logo'] = $logoImageURL;

        }
        $storeArray['name'] = $name;
        $storeArray['email'] = $email;
        $storeArray['website'] = $website;
        $storeArray['password'] = $password;
        Company::insert($storeArray);
        $subject = 'New Company Creation';
        $data = array('email' => $email, 'subject' => $subject, 'name' => $name);

        Mail::to('shashisagar120@gmail.com')->send(new CompanyCreationEmail($data));

        /*
        Mail::to('applocumadmin@yopmail.com)->send(new CompanyCreationEmail($data));
        */

        return json_encode($storeArray);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function companyUpdate(UpdateCompanyPost $request)
    {
        $comp_id = $request->company_id_update;
        $companyDetails = (array)DB::table('companies')
            ->where('id', '=', $comp_id)
            ->first();
        $email = $request->email;
        $name = $request->company_name;
        $website = $request->company_website;
        $updateArray = array();
        $updateArray['name'] = $name;
        $updateArray['website'] = $website;
        $updateArray['email'] = $email;

        if ($_FILES['fileToUpload']['size'] != 0) {
            $image = $_FILES['fileToUpload'];
            $array = explode('.', $image['name']);
            $extension = end($array);
            $logoImageURL = uploadImageToStoragePath($image['tmp_name'], 'company', 'company-logo' . rand() . '-' . time() . '.' . $extension, 100
                , 100);
            $updateArray['logo'] = $logoImageURL;
            if ($companyDetails['logo'] != NULL)
                deleteImageFromStoragePath($companyDetails['logo']);
        }

        if (!empty($request->company_password)) {
            $password = $request->company_password;
            $password = Hash::make($password);
            $updateArray['password'] = $password;
        }
        Company::where('id', '=', $comp_id)->update($updateArray);
        return json_encode($updateArray);
    }


    public function companyList(Request $request)
    {
        $data = Company::select('*')->orderBy('id','desc');
        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                return '<button class="btn btn-xs btn-primary edit_company" company_id="' . $data->id . '" company_name="' . $data->name . '"  company_website="' . $data->website . '" company_logo="' . $data->logo . '" company_email="' . $data->email . '"> Edit</button>
                   <button  class="btn btn-xs btn-danger delete_company" company_id="' . $data->id . '"> Delete</button>   ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function companyDelete(Request $request)
    {
        $comp_id = $request->company_id;
        $status = Company::where('id', $comp_id)->delete();
        if ($status) {
            return json_encode("1");

        }
    }

}
