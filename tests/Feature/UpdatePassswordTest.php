<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Hash;

class UpdatePassswordTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_an_authenticated_user_can_modify_their_password(): void
    {
        #$this->withExceptionHandling();
        
        # teniedo
        $data =[
            'old_password' => 'passwordjjhgjhg',
            'password'=> 'newpassword',
            'password_confirmation' => 'newpassword'
        ];
    
        # haciendo
        $response = $this->apiAs(User::find(1), 'put', "{$this->apiBase}/password", $data);
        #$response->dd();


        # esperando
        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'data', 'errors', 'status']);
        $user = User::find(1);
        $this->assertTrue(Hash::check('newpassword', $user->password));

    }

    public function test_old_password_must_validated(): void
    {
        $this->withExceptionHandling();
        
        # teniedo
        $data =[
            'old_password' => 'wrogpassword',
            'password'=> 'newpassword',
            'password_confirmation' => 'newpassword'
        ];
    
        # haciendo
        $response = $this->apiAs(User::find(1), 'put', "{$this->apiBase}/password", $data);
        #$response->dd();


        # esperando
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'errors'=>['old_password']]);
        $response->assertJsonFragment(['errors'=>['old_password' => [
            'The password does not match.'
        ]]]);

    }

    public function test_old_password_must_be_required(): void
    {
        #$this->withExceptionHandling();
        
        # teniedo
        $data =[
            'old_password' => '',
            'password'=> 'newpassword',
            'password_confirmation' => 'newpassword'
        ];
    
        # haciendo
        $response = $this->apiAs(User::find(1), 'put', "{$this->apiBase}/password", $data);
        #$response->dd();


        # esperando
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'errors'=>['old_password']]);
        
    }

    public function test_password_must_be_required(): void
    {
        #$this->withExceptionHandling();
        
        # teniedo
        $data =[
            'old_password' => 'password',
            'password'=> '',
            'password_confirmation' => 'newpassword'
        ];
    
        # haciendo
        $response = $this->apiAs(User::find(1), 'put', "{$this->apiBase}/password", $data);
        #$response->dd();


        # esperando
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'errors'=>['password']]);
        
    }

    public function test_password_must_be_confirmed(): void
    {
        #$this->withExceptionHandling();
        
        # teniedo
        $data =[
            'old_password' => 'password',
            'password'=> 'newpassword',
            'password_confirmation' => ''
        ];
    
        # haciendo
        $response = $this->apiAs(User::find(1), 'put', "{$this->apiBase}/password", $data);
        #$response->dd();


        # esperando
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'errors'=>['password']]);
        $response->assertJsonFragment(['errors'=>['password' => [
            'The password field confirmation does not match.'
        ]]]);
        
    }
    

    protected function setUp():void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }
}
