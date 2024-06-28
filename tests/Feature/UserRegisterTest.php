<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserRegisterTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_a_user_can_register(): void
    {
        #$this->withExceptionHandling();
        
        # teniedo
        $data =[
            'email' => 'email@email.com',
            'password' => 'password',
            'name' => 'example',
            'last_name'=> 'example example'
        ];
    
        # haciendo
        $response = $this->postJson("{$this->apiBase}/users", $data);

        # esperando
        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'data', 'errors', 'status']);
        $response->assertJsonFragment(
            [
                'message'=> 'OK', 
                'data'=>['user'=>[
                                    'id'=>1,
                                    'email' => 'email@email.com',
                                    'name' => 'example',
                                    'last_name'=> 'example example'
                                ]
                ], 'status'=>200]);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'email'=>'email@email.com',
            'name' => 'example',
            'last_name'=> 'example example'
        ]);
    }

    public function test_a_register_can_user_login(): void
    {
       #$this->withoutExceptionHandling();
        # teniendo
        $data =[
            'email' => 'email@email.com',
            'password' => 'password',
            'name' => 'example',
            'last_name'=> 'example example'
        ];

        # haciendo
        $this->postJson("{$this->apiBase}/users", $data);
        $response = $this->postJson("{$this->apiBase}/login", ['email' => 'email@email.com','password' => 'password',]);
        #$response->dd();
        # esperando
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['token']]);

    }

    public function test_mail_must_be_required(): void
    {
        //$this->withoutExceptionHandling();
        # teniendo
        $data =[
            'email' => '',
            'password' => 'password',
            'name' => 'example',
            'last_name'=> 'example example'
        ];

        # haciendo
        $response = $this->postJson("{$this->apiBase}/users", $data);
        #$response->dd();
        # esperando
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['email']]);
        $response->assertJsonFragment([
            'errors' => [
                'email' => ['The email field is required.']
            ]
        ]);
    }

    public function test_mail_must_be_valid_required(): void
    {
        # teniendo
        $data =[
            'email' => 'mlkmlkmlk',
            'password' => 'password',
            'name' => 'example',
            'last_name'=> 'example example'
        ];

        # haciendo
        $response = $this->postJson("{$this->apiBase}/users", $data);
        #$response->dd();
        # esperando
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['email']]);
        $response->assertJsonFragment([
            'errors' => [
                'email' => ['The email field must be a valid email address.']
            ]
        ]);
    }

    public function test_email_must_be_valid_unique(): void
    {
        User::factory()->create(['email'=>'email@email.com']);

        # teniendo
        $data =[
            'email' => 'email@email.com',
            'password' => 'password',
            'name' => 'example',
            'last_name'=> 'example example'
        ];

        # haciendo
        $response = $this->postJson("{$this->apiBase}/users", $data);
        #$response->dd();
        # esperando
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['email']]);
        $response->assertJsonFragment([
            'errors' => [
                'email' => ['The email has already been taken.']
            ]
        ]);
    }


    public function test_password_must_be_required(): void
    {
       #$this->withoutExceptionHandling();
        # teniendo
        $data =[
            'email' => 'email@email.com',
            'password' => '',
            'name' => 'example',
            'last_name'=> 'example example'
        ];

        # haciendo
        $response = $this->postJson("{$this->apiBase}/users", $data);
        #$response->dd();
        # esperando
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['password']]);
        $response->assertJsonFragment([
            'errors' => [
                'password' => ['The password field is required.']
            ]
        ]);

    }


    public function test_password_must_have_at_lease_8_characters(): void
    {
       //$this->withoutExceptionHandling();
        # teniendo
        $data =[
            'email' => 'email@email.com',
            'password' => '1234556',
            'name' => 'example',
            'last_name'=> 'example example'
        ];

        # haciendo
        $response = $this->postJson("{$this->apiBase}/users", $data);
        #$response->dd();
        # esperando
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['password']]);
        $response->assertJsonFragment([
            'errors' => [
                'password' => ['The password field must be at least 8 characters.']
            ]
        ]);

    }

    public function test_name_must_be_required(): void
    {
        #$this->withoutExceptionHandling();
        # teniendo
        $data =[
            'email' => 'email@email.com',
            'password' => 'password',
            'name' => '',
            'last_name'=> 'example example'
        ];

        # haciendo
        $response = $this->postJson("{$this->apiBase}/users", $data);
        #$response->dd();
        # esperando
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['name']]);
        $response->assertJsonFragment([
            'errors' => [
                'name' => ['The name field is required.']
            ]
        ]);

    }

    public function test_name_must_at_lease_2_characters(): void
    {
       //$this->withoutExceptionHandling();
        # teniendo
        $data =[
            'email' => 'email@email.com',
            'password' => '1234556',
            'name' => 'e',
            'last_name'=> 'example example'
        ];

        # haciendo
        $response = $this->postJson("{$this->apiBase}/users", $data);
        #$response->dd();
        # esperando
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['name']]);

    }

    public function test_last_name_must_be_required(): void
    {
        #$this->withoutExceptionHandling();
        # teniendo
        $data =[
            'email' => 'email@email.com',
            'password' => 'password',
            'name' => 'example',
            'last_name'=> ''
        ];

        # haciendo
        $response = $this->postJson("{$this->apiBase}/users", $data);
        #$response->dd();
        # esperando
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['last_name']]);
    }

    public function test_last_name_must_at_lease_2_characters(): void
    {
       #$this->withoutExceptionHandling();
        # teniendo
        $data =[
            'email' => 'email@email.com',
            'password' => 'password',
            'name' => 'example',
            'last_name'=> 'e'
        ];

        # haciendo
        $response = $this->postJson("{$this->apiBase}/users", $data);
        #$response->dd();
        # esperando
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['last_name']]);

    }


}
