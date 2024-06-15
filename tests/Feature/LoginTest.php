<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

// php artisan test --filter test_an_exixting_user_can_login
// php artsisan test --filter LoginTest
class LoginTest extends TestCase
{

   use RefreshDatabase;

    protected function setUp():void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }

    # [Test]
    public function test_an_exixting_user_can_login(): void
    {
        $this->withoutExceptionHandling();
        # teniendo
        $credenciales = ['email'=> 'example@example.com', 'password'=> 'password'];

        # haciendo
        $response = $this->postJson("{$this->apiBase}/login", $credenciales);
        # esperando
        # dd($response->json());
        # Verificando que el usuario fue creado en la base de datos en memoria
        # $users = \App\Models\User::all();
        # dump($users);  // O usa dd($users) para detener la ejecución y ver los datos

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['token']]);

    }

    # [Test]
    public function test_a_non_existing_user_can_login(): void
    {
        $this->withoutExceptionHandling();
        # teniendo
        $credenciales = ['email'=> 'prueba@nonexting.com', 'password'=> 'password'];

        # haciendo
        $response = $this->postJson("{$this->apiBase}/login", $credenciales);
        # dump($response->json());
        # esperando
        $response->assertStatus(401);
        $response->assertJsonFragment(['status' => 401, 'message' => 'Unauthorized']);

    }

    
    public function test_mail_must_be_required(): void
    {
        # teniendo
        $credenciales = ['password'=> 'password'];

        # haciendo
        $response = $this->postJson("{$this->apiBase}/login", $credenciales);
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
        $credenciales = ['email'=> 'khkjhkjk', 'password'=> 'password'];

        # haciendo
        $response = $this->postJson("{$this->apiBase}/login", $credenciales);
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

    public function test_mail_must_be_a_string(): void
    {
        # teniendo
        $credenciales = ['email'=> 222222, 'password'=> 'password'];

        # haciendo
        $response = $this->postJson("{$this->apiBase}/login", $credenciales);
        #$response->dd();
        # esperando
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['email']]);
        $response->assertJsonFragment([
            'errors' => [
                'email' => ['The email field must be a string.', 'The email field must be a valid email address.']
            ]
        ]);
    }

    public function test_password_must_be_required(): void
    {
        # teniendo
        $credenciales = ['email'=> 'prueba@example.com'];

        # haciendo
        $response = $this->postJson('/api/v1/login', $credenciales);
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
        # teniendo
        $credenciales = ['email'=> 'prueba@example.com', 'password'=> 'abcde'];

        # haciendo
        $response = $this->postJson('/api/v1/login', $credenciales);
        #$response->dd();
        # esperando
        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'data', 'status', 'errors' => ['password']]);
        $response->assertJsonFragment([
            'errors' => [
                'password' => ['The password field must be at least 6 characters.']
            ]
        ]);

    }
}
