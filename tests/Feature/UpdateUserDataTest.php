<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;

use Tests\TestCase;

class UpdateUserDataTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_an_authenticated_user_can_modify_their_data(): void
    {
        #$this->withExceptionHandling();
        
        # teniedo
        $data =[
            'name' => 'newname',
            'last_name'=> 'new lastname'
        ];
    
        # haciendo
        $response = $this->apiAs(User::find(1), 'put', "{$this->apiBase}/profile", $data);
        #$response->dd();


        # esperando
        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'data', 'errors', 'status']);
        $response->assertJsonFragment(
            [
                'message'=> 'OK', 
                'data'=>['user'=>[
                                    'id'=>1,
                                    'email' => 'example@example.com',
                                    'name' => 'newname',
                                    'last_name'=> 'new lastname'
                                ]
                ], 'status'=>200]);

        
        $this->assertDatabaseMissing('users', [
            'email'=>'email@email.com',
            'name' => 'User',
            'last_name'=> 'Test'
        ]);
    }

    public function test_an_authenticated_user_cannot_modify_their_email(): void
    {
        #$this->withExceptionHandling();
        
        # teniedo
        $data =[
            'email'=> 'newemail@example.com',
            'name' => 'newname',
            'last_name'=> 'new lastname'
        ];
    
        # haciendo
        $response = $this->apiAs(User::find(1), 'put', "{$this->apiBase}/profile", $data);
        #$response->dd();


        # esperando
        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'data', 'errors', 'status']);
        $response->assertJsonFragment(
            [
                'message'=> 'OK', 
                'data'=>['user'=>[
                                    'id'=>1,
                                    'email' => 'example@example.com',
                                    'name' => 'newname',
                                    'last_name'=> 'new lastname'
                                ]
                ], 'status'=>200]);

        
        $this->assertDatabaseHas('users', [
            'email'=>'example@example.com',
            'name' => 'newname',
            'last_name'=> 'new lastname'
        ]);
    }

    public function test_an_authenticated_user_cannot_modify_their_password(): void
    {
        $this->withExceptionHandling();
        
        # teniedo
        $data =[
            'password'=> 'newpassword',
            'name' => 'newname',
            'last_name'=> 'new lastname'
        ];
    
        # haciendo
        $response = $this->apiAs(User::find(1), 'put', "{$this->apiBase}/profile", $data);
        #$response->dd();


        # esperando
        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'data', 'errors', 'status']);
        $user = User::find(1);
        $this->assertFalse(Hash::check('newpassword', $user->password));
        
    }

    public function test_name_must_be_required(): void
    {
        #$this->withoutExceptionHandling();
        # teniendo
        $data =[
            'name' => '',
            'last_name'=> 'example example'
        ];

        # haciendo
        $response = $this->apiAs(User::find(1),'put', "{$this->apiBase}/profile", $data);
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
        #$this->withoutExceptionHandling();
        # teniendo
        $data =[
            'name' => 'e',
            'last_name'=> 'example example'
        ];

        # haciendo
        $response = $this->apiAs(User::find(1),'put',"{$this->apiBase}/profile", $data);
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
            'name' => 'example',
            'last_name'=> ''
        ];

        # haciendo
        $response = $this->apiAs(User::find(1),'put',"{$this->apiBase}/profile", $data);
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
            'name' => 'example',
            'last_name'=> 'e'
        ];

        # haciendo
        $response = $this->apiAs(User::find(1),'put',"{$this->apiBase}/profile", $data);
        #$response->dd();
        # esperando
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['last_name']]);

    }

    protected function setUp():void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }
    

}
