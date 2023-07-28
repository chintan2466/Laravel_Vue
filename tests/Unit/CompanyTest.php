<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use  Tests\TestCase;
use  App\Models\Company;

class CompanyTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_company_store()
    {
        $response = $this->post('/api/company',[
            'name'=>'Test1 Name',
            'email'=>'testing@testing.com',
            // 'logo'=>'',
            'website'=>'testing.com',
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
    public function test_company_update()
    {
        $data = Company::all();
        $lastInsertId = collect($data)->last()->id;
        
        $response = $this->post('/api/company/'.$lastInsertId,[
            'name'=>'Test1 Name Update',
            'email'=>'testing@testing.com',
            // 'logo'=>'',
            'website'=>'testing.com',
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
    public function test_company_delete()
    {
        $data = Company::all();
        $lastInsertId = collect($data)->last()->id;
        
        $response = $this->delete('/api/company/'.$lastInsertId,[
            'name'=>'Test1 Update Name',
            'email'=>'testing1@testing.com',
            // 'logo'=>'',
            'website'=>'testing.com',
        ]);
        // dd($response);
        // $response->assertStatus($response->status(),200);
        $response->assertStatus(200);
        // $this->assertTrue(true);
    }
}
