<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use  Tests\TestCase;
use  App\Models\Employee;
use  App\Models\Company;
use  App\Models\User;
use Laravel\Sanctum\Sanctum;

class EmployeeTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_employee_store()
    {
        $user = User::find(1);
        Sanctum::actingAs($user,['*']);
        $lastCompanyId = collect(Company::all())->last()->id;
        $response = $this->post('/api/employee',[
            'firstname'=>'Test1 First Name',
            'lastname'=>'Test1 Last Name',
            'email'=>'testing@testing.com',
            'phone'=>'1234567890',
            'company_id'=>$lastCompanyId,
        ]);
        // dd($response);
        $response->assertStatus(200);
        // $this->assertTrue(true);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_employee_update()
    {
        $user = User::find(1);
        Sanctum::actingAs($user,['*']);
        $data = Employee::all();
        $lastInsertId = collect($data)->last()->id;
        
        $lastCompanyId = collect(Company::all())->last()->id;

        $response = $this->post('/api/employee/'.$lastInsertId,[
            'firstname'=>'Test1 First Name Update',
            'lastname'=>'Test1 Last Name',
            'email'=>'testing@testing.com',
            'phone'=>'1234567890',
            'company_id'=>$lastCompanyId,
        ]);
        // dd($response);
        $response->assertStatus(200);
        // $this->assertTrue(true);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_employee_delete()
    {
        $user = User::find(1);
        Sanctum::actingAs($user,['*']);
        $data = Employee::all();
        $lastInsertId = collect($data)->last()->id;
        
        $response = $this->delete('/api/employee/'.$lastInsertId);
        // dd($response);
        // $response->assertStatus($response->status(),200);
        $response->assertStatus(200);
        // $this->assertTrue(true);
    }
}
