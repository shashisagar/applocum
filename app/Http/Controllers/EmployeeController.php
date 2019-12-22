<?php

namespace App\Http\Controllers;
use App\Company;
use App\Employee;
use App\Http\Requests\StoreEmployeePost;
use App\Http\Requests\UpdateEmployeePost;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies=Company::select('*')->orderBy('name')->get();
        return view('employee.index',get_defined_vars());
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
    public function store(StoreEmployeePost $request)
    {
            $company_id=$request->select_company;
            $name=$request->employee_name;
            $email=$request->email;
            $phone=$request->employee_phone;
            $storeArray=array('name'=>$name,'email' =>$email,'phone' =>$phone,'company_id'=>$company_id);
            Employee::create($storeArray);
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


    public function employeeUpdate(UpdateEmployeePost $request){

        $comp_id=$request->employee_id_update;
       // $company_employee_id=$request->select_company;
        $email=$request->email;
        $name=$request->employee_name;
        $phone=$request->employee_phone;
        $updateArray=array('name'=>$name,'phone'=>$phone,'email'=>$email);
        Employee::where('id','=',$comp_id)->update($updateArray);
        return  json_encode($updateArray);
    }


    public function employeeList(Request $request){
        $data = Employee::select('employees.*','companies.name as cname')->join('companies','companies.id','=','employees.company_id')
            ->orderBy('employees.id','desc');
           return DataTables::of($data)
            ->addColumn('action', function ($data) {
                return '<button class="btn btn-xs btn-primary edit_employee" employee_id="' . $data->id . '" employee_name="' . $data->name . '"  employee_phone="' . $data->phone . '" employee_email="' . $data->email . '" company_id="' . $data->company_id . '"> Edit</button>
                   <button  class="btn btn-xs btn-danger delete_employee" employee_id="' . $data->id . '"> Delete</button>   ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function employeeDelete(Request $request){
        $comp_id=$request->employee_id;
        $status = Employee::where('id',$comp_id)->delete();
        if($status){
            return  json_encode("1");
        }
    }

}
