<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
 
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function(){
    
    // $token = Request()->user();
    // echo '<br /><br /><br /><br /><pre>'; print_r($token); echo '</pre>'; exit;

    Route::apiResource('company', CompanyController::class)->only('index', 'store', 'destroy');
    Route::controller(CompanyController::class)->group(function () {
        Route::get('search/company/{field}/{query}', 'search');
        Route::post('company/{company}', 'update')->name('company.update');
    });
    

    Route::apiResource('employee', EmployeeController::class)->only('index', 'store', 'destroy');
    Route::controller(EmployeeController::class)->group(function () {
        Route::get('search/employee/{field}/{query}', 'search');
        Route::post('employee/{employee}', 'update')->name('employee.update');
    });
    

});

// For check token value of user 
Route::post('user', [UserController::class, 'index']);
// http://127.0.0.1:8000/api/user POST 
// { "email":"admin@admin.com", "password":"password" }