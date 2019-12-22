<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
///These root are access by Admin User.

Route::group(['middleware' => ['user_auth']], function () {

    Route::get('/home', 'HomeController@index')->name('home');
    Route::resource('company', 'CompanyController');
    Route::post('company/company_list', 'CompanyController@companyList');
    Route::post('company/update', 'CompanyController@companyUpdate');
    Route::post('company/delete', 'CompanyController@companyDelete');
    Route::resource('employee', 'EmployeeController');
    Route::post('employee/employee_list', 'EmployeeController@employeeList');
    Route::post('employee/update', 'EmployeeController@employeeUpdate');
    Route::post('employee/delete', 'EmployeeController@employeeDelete');
});
///These root are access by Company User.

Route::group(['middleware' => ['company_auth']], function () {
    Route::get('/cmp/login', 'UserCompanyController@login');
    Route::get('/comp/dashboard', 'UserCompanyController@index');
    Route::post('/comp/employee/store', 'UserCompanyController@store');
    Route::post('/comp/employee_list', 'UserCompanyController@employeeList');
    Route::post('/comp/employee/update', 'UserCompanyController@employeeUpdate');
    Route::post('/comp/employee/delete', 'UserCompanyController@employeeDelete');

});
///These root are access for getting images from storage

Route::get('/image/{filePath}', function ($filePath) {
    $filePath = explode("_", $filePath);
    $filePath = implode('/', $filePath);
    $filePath = storage_path() . '/' . $filePath;
    $file = File::get($filePath);
    $type = File::mimeType($filePath);
    $response = Response::make($file, 500);
    $response->header("Content-Type", $type);
    return $response;
});

