<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;

use App\Http\Controllers\UserController;
use  Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_user_details_is_valid()
    {
        // $ret = (new UserController)->index();
        $response = $this->post('/api/user',[
            'email'=>'admin@admin.com',
            'password'=>'password',
        ]);
        // dd($response);
        $response->assertStatus(201);
        // $this->assertTrue(true);
    }
}
