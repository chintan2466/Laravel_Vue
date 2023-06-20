<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
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

Route::apiResource('company', CompanyController::class)->only('index', 'store', 'destroy');
Route::post('company/{company}', [CompanyController::class, 'update'])->name('company.update');
Route::get('search/company/{field}/{query}', [CompanyController::class, 'search']);

Route::apiResource('employee', EmployeeController::class)->only('index', 'store', 'destroy');
Route::post('employee/{employee}', [EmployeeController::class, 'update'])->name('employee.update');
Route::get('search/employee/{field}/{query}', [EmployeeController::class, 'search']);
