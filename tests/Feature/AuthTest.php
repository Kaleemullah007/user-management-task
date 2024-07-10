<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */

     public function test_the_application_returns_a_successful_response(): void
     {
         $response = $this->get('/');
 
         $response->assertStatus(200);
     }

    public function test_user_can_not_login_with_invalid_credentails(): void
    {
        $response = $this->post('/login',['email'=>'test@gmail.com','password'=>bcrypt('password')]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_user_can_login_with_valid_credentails(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
            'email'=>'test@gmail.com'
        ]);
        $response = $this->post('/login',['email'=>$user->email,'password'=>$password]);

        $response->assertStatus(302);
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_logout()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertStatus(302);
        $this->assertGuest();
    }

}
