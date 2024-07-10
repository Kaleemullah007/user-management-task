<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_administrator_can_create_user(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
            'email'=>'test@gmail.com',
            'role'=>'administrator'
        ]);
        $this->actingAs($user);
        

        $response = $this->post('/users',[
            'name'=>'hello',
            'email'=>'hello@gmail.com',
            'password'=>'password',
            'role'=>'user'
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'email' => 'hello@gmail.com',
        ]);
    }


    public function test_user_role_can_not_create_user(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
            'email'=>'test@gmail.com',
            'role'=>'user'
        ]);
        $this->actingAs($user);
        
        $response = $this->post('/users',[
            'name'=>'hello',
            'email'=>'hello@gmail.com',
            'password'=>'password',
            'role'=>'user'
        ]);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('users', [
            'email' => 'hello@gmail.com',
        ]);
    }


    public function test_user_role_can_update_his_profile(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
            'email'=>'test@gmail.com',
            'role'=>'user'
        ]);
        $this->actingAs($user);
        
        $response = $this->put(route('users.update',$user->id),[
            'name'=>'hello',
            'email'=>'hello@gmail.com'
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'name'=>'hello'
            
        ]);
    }

    public function test_user_role_can_not_update_profile_of_other(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
            'email'=>'test@gmail.com',
            'role'=>'user'
        ]);
        $this->actingAs($user);

        $user_2 = User::factory()->create([
            'password' => bcrypt($password = 'password'),
            'email'=>'test@gmail1.com',
            'role'=>'user'
        ]);
        
        $response = $this->put(route('users.update',$user_2->id),[
            'name'=>'hello',
            'email'=>'hello@gmail.com'
        ]);
        $response->assertStatus(403);
    }

    public function test_administratoe_role_can_update_profile_of_other(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
            'email'=>'test@gmail.com',
            'role'=>'administrator'
        ]);
        $this->actingAs($user);

        $user_2 = User::factory()->create([
            'password' => bcrypt($password = 'password'),
            'email'=>'test@gmail1.com',
            'role'=>'user'
        ]);
        
        $response = $this->put(route('users.update',$user_2->id),[
            'name'=>'hello1',
            'email'=>'hello@gmail.com'
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('users',['name'=>'hello1']);
    }

    public function test_user_role_can_not_see_profiles_of_other(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
            'role'=>'user'
        ]);
        $this->actingAs($user);

        $response = $this->get(route('users.index'));
        $response->assertDontSeeText('User Listing');
        
        
    }

    public function test_administrator_role_can_see_profiles_of_other(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
            'role'=>'administrator'
        ]);
        $this->actingAs($user);
        $response = $this->get(route('users.index'));
        $response->assertSeeText('User Listing');
        
    }

    public function test_user_role_can_not_see_create_user_button(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
            'role'=>'user'
        ]);
        $this->actingAs($user);

         $response = $this->get(route('users.index'));
        $response->assertDontSeeText('Create User');
        
    }

    public function test_administrator_role_can_see_create_user_button(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
            'role'=>'administrator'
        ]);
        $this->actingAs($user);

         $response = $this->get(route('users.index'));
        $response->assertSeeText('Create User');
    }


    public function test_administrator_role_can_not_be_user(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
            'role'=>'administrator'
        ]);
        $this->actingAs($user);
        $response = $this->put(route('users.update',$user->id),[
            'name'=>'hello',
            'email'=>'hello@gmail.com',
            'role'=>'user'
        ]);
        $response->assertStatus(302);
    }


    public function test_administrator_role_can_delete_user(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
            'role'=>'administrator'
        ]);
        $this->actingAs($user);
        $user_2 = User::factory()->create([
            'password' => bcrypt($password = 'password'),
            'email'=>'test@gmail1.com',
            'role'=>'user'
        ]);
        $response = $this->delete(route('users.destroy',$user_2->id));
        $response->assertStatus(302);

        $this->assertDatabaseMissing('users',['id'=>$user_2->id]);

    }


    public function test_user_role_can_not_delete_user(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
            'role'=>'user'
        ]);
        $this->actingAs($user);
        $user_2 = User::factory()->create([
            'password' => bcrypt($password = 'password'),
            'email'=>'test@gmail1.com',
            'role'=>'administrator'
        ]);
        $response = $this->delete(route('users.destroy',$user_2->id));
        $response->assertStatus(403);

        

    }

    
}
