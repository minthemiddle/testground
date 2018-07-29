<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UsersTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function a_user_not_logged_in_cannot_see_tasks()
    {
        $response = $this->get('/tasks');
        $response->assertStatus(302);
    }

    /** @test */
    public function a_logged_in_user_can_see_tasks()
    {
       $user = factory(User::class)->make();

       $response = $this->actingAs($user)->get('/tasks');
       $response->assertStatus(200);
    }

    /** @test */
    public function a_user_giving_correct_login_data_can_log_in()
    {
        $pw = 'secret';
        $user = factory(User::class)->create(['password' => Hash::make($pw)]);

        $options = [
            'email' => $user->email,
            'password' => $pw,
        ];

        $response = $this->post('/login', $options);
        $response->assertRedirect('/home');
    }

    /** @test */
    public function a_user_providing_all_information_can_register()
    {
        $options = [
            'name' => 'test001',
            'email' => 'test001@test.test',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ];

        $response = $this->post('/register', $options);
        
        $response->assertRedirect('/home');
    }
}
