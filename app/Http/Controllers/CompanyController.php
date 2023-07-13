<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyCollection;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Notifications\CompanyInserted;
use Mail;

class CompanyController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        // $user = auth('sanctum')->;
        // echo '<br /><br /><br /><br /><pre>'; print_r($user); echo '</pre>'; exit;
        return new CompanyCollection(Company::orderBy('id', 'DESC')->paginate(10));
    }
    public function search($field, $query)
    {
        return new CompanyCollection(Company::where($field, 'LIKE', "%$query%")->latest()->paginate(10));
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
            'name'=> 'required',
            // 'email'=> 'nullable|unique:companies,email',
            'logo'=> 'nullable|mimes:jpg,jpeg,png|max:2048',
            // 'website'=> 'required',
        ]);
        if ($validator->fails()) {    
            return response()->json($validator->errors(), 400);
        }
        $company = new Company();
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $ext = $logo->extension();
            $file = time().'.'.$ext;
            $logo->storeAs('public/company', $file);
            $company->logo = $file;
        }
        $company->name = $request->name;
        $company->email = $request->email;
        $company->website = $request->website;
        $company->save();
        if($company->email!=''){
            // $company->notify(new CompanyInserted);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return new CompanyResource($company);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $validator = validator()->make($request->all(), [
            'name'=> 'required',
            // 'email'=> 'nullable|unique:companies,email',
            'logo'=> 'nullable|mimes:jpg,jpeg,png|max:2048',
            // 'website'=> 'required',
        ]);
        if ($validator->fails()) {    
            return response()->json($validator->errors(), 400);
        }
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $ext = $logo->extension();
            $file = time().'.'.$ext;
            $logo->storeAs('public/company', $file);
            $company->logo = $file;
        }
        $company->name = $request->name;
        $company->email = $request->email;
        $company->website = $request->website;
        $company->save();
        
        return new CompanyResource($company);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $company->delete();
        
        return new CompanyResource($company);
    }
}
