<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Http\Resources\EmployeeCollection;
use App\Http\Resources\EmployeeResource;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = \App\Models\Company::all();
        $employees = new EmployeeCollection(Employee::orderBy('id', 'DESC')->paginate(10));
        // $employees = new EmployeeCollection(Employee::join('companies', 'companies.id', '=', 'employees.company_id')
        // ->select(['employees.*','companies.name'])->orderBy('id', 'DESC')->paginate(10));
        // echo '<br /><br /><br /><br /><pre>'; print_r($employees); echo '</pre>'; exit;
        $employees->additional(['companies' => $companies]);
        return $employees; 
        // return new EmployeeCollection(Employee::orderBy('id', 'DESC')->paginate(10));
    }
    public function search($field, $query)
    {
        return new EmployeeCollection(Employee::where($field, 'LIKE', "%$query%")->latest()->paginate(10));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'firstname'=> 'required',
            'lastname'=> 'required',
            // 'email'=> 'required',
            // 'phone'=> 'required',
            'company_id'=> 'required',
        ],[
            'company_id.required' => 'The Company field is required.'
        ]);
        if ($validator->fails()) {    
            return response()->json($validator->errors(), 400);
        }
        $employee = new Employee();
        $employee->firstname = $request->firstname;
        $employee->lastname = $request->lastname;
        $employee->email = $request->email;
        $employee->phone = $request->phone;
        $employee->company_id = $request->company_id;
        
        $employee->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        return new EmployeeResource($employee);
    }
 
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        $validator = validator()->make($request->all(), [
            'firstname'=> 'required',
            'lastname'=> 'required',
            // 'email'=> 'required',
            // 'phone'=> 'required',
            'company_id'=> 'required',
        ],[
            'company_id.required' => 'The Company field is required.'
        ]);
        if ($validator->fails()) {    
            return response()->json($validator->errors(), 400);
        }
        $employee->firstname = $request->firstname;
        $employee->lastname = $request->lastname;
        $employee->email = $request->email;
        $employee->phone = $request->phone;
        $employee->company_id = $request->company_id;
        
        $employee->save();
        
        return new EmployeeResource($employee);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        
        return new EmployeeResource($employee);
    }
}
