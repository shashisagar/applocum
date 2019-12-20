<?php

namespace App\Http\Controllers;
use App\Company;
use App\Http\Requests\StoreCompanyPost;
use App\Http\Requests\UpdateCompanyPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCompanyPost $request)
    {
            $name=$request->company_name;
            $email=$request->email;
            $website=$request->company_website;
            $password=$request->company_password;
            $password=Hash::make($password);
            $image = $_FILES['fileToUpload'];
            $array = explode('.', $image['name']);
            $extension = end($array);
            $mainImageURL = uploadImageToStoragePath($image['tmp_name'], 'company', 'company-main' . rand() . '-' . time() . '.' . $extension, 100, 100);
            $storeArray=array('name'=>$name,'email' =>$email,'logo' =>$mainImageURL,'website'=>$website,
                'password'=>$password);
            Company::create($storeArray);

            $subject = 'Company Creation';
            $data = array('email' => $email, 'subject' => $subject);


            /*
             *
             *   Send email
            Mail::send('email.send_email', $data, function ($message) use ($data) {
                $message->from('shashi@mailinator.com', 'News Information');
                $message->to($data['email']);
                $message->subject($data['subject']);

            });
            */

            return  json_encode($storeArray);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function companyUpdate(UpdateCompanyPost $request){

        $comp_id=$request->company_id_update;
        $email=$request->email;
        $name=$request->company_name;
        $website=$request->company_website;
        $password=$request->company_password;
        if(!empty($password)){
            $password=Hash::make($password);
            $updateArray=array('name'=>$name,'website'=>$website,'email'=>$email,'password'=>$password);
        }
        else{
            $updateArray=array('name'=>$name,'website'=>$website,'email'=>$email);
        }

        $res=Company::where('id','=',$comp_id)->update($updateArray);
        return  json_encode($updateArray);
    }


    public function companyList(Request $request){
        $data = Company::select('*');
        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                return '<button class="btn btn-xs btn-primary edit_company" company_id="' . $data->id . '" company_name="' . $data->name . '"  company_website="' . $data->website . '" company_logo="' . $data->logo . '" company_email="' . $data->email . '"> Edit</button>
                   <button  class="btn btn-xs btn-danger delete_company" company_id="' . $data->id . '"> Delete</button>   ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function companyDelete(Request $request){
        $comp_id=$request->company_id;
        $status = Company::where('id',$comp_id)->delete();
        if($status){
            return  json_encode("1");

        }
    }

}
