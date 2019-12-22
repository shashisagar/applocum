<?php

namespace App\Http\Controllers;
use App\Company;
use App\Employee;
use App\Http\Requests\StoreUserCompanyPost;
use App\Http\Requests\UpdateUserCompanyPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Image;
use Storage;
use Yajra\DataTables\DataTables;

class UserCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies=Company::all();
        return view('companyAccessDir/employee.index',get_defined_vars());
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
    public function store(StoreUserCompanyPost $request)
    {
        $company_id=Auth::guard('company')->user()->id;
        $name=$request->employee_name;
        $email=$request->email;
        $phone=$request->employee_phone;
        $storeArray=array('name'=>$name,'email' =>$email,'phone' =>$phone,'company_id'=>$company_id);
        Employee::insert($storeArray);
        return  json_encode($storeArray);

    }


    public function employeeUpdate(UpdateUserCompanyPost $request){
        $company_id_auth=Auth::guard('company')->user()->id;
        $comp_id=$request->employee_id_update;
        $email=$request->email;
        $name=$request->employee_name;
        $phone=$request->employee_phone;
        $updateArray=array('name'=>$name,'phone'=>$phone,'email'=>$email);
        $res=Employee::where('id','=',$comp_id)->where('company_id','=',$company_id_auth)->update($updateArray);
        return  json_encode($updateArray);
    }



    public function employeeList(Request $request){
        $data = Employee::select('employees.*','companies.name as cname')->join('companies','companies.id','=','employees.company_id')
                ->where('employees.company_id','=',Auth::guard('company')->user()->id);
        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                return '<button class="btn btn-xs btn-primary edit_employee" employee_id="' . $data->id . '" employee_name="' . $data->name . '"  employee_phone="' . $data->phone . '" employee_email="' . $data->email . '"> Edit</button>
                   <button  class="btn btn-xs btn-danger delete_employee" employee_id="' . $data->id . '"> Delete</button>   ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function employeeDelete(Request $request){
        $company_id_auth=Auth::guard('company')->user()->id;
        $emp_id=$request->employee_id;
        $status = Employee::where('id',$emp_id)->where('company_id','=',$company_id_auth)->delete();
        if($status){
            return  json_encode("1");

        }
    }
    public function login(Request $request){
        return redirect('/comp/dashboard');

    }

}
