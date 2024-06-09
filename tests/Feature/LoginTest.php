<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

// php artisan test --filter test_an_exixting_user_can_login
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
        $response = $this->post("{$this->apiBase}/login", $credenciales);
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
        $credenciales = ['email'=> 'prueba@nonexting.com', 'password'=> 'asdsad'];

        # haciendo
        $response = $this->post("/api/v1/login", $credenciales);
        # dump($response->json());
        # esperando
        $response->assertStatus(401);
        $response->assertJsonFragment(['status' => 401, 'message' => 'Unauthorized']);

    }

    
    public function test_mail_must_be_required(): void
    {
        # teniendo
        $credenciales = ['password'=> '1234hjk'];

        # haciendo
        $response = $this->post('/api/v1/login', $credenciales);

        # esperando
        $response->assertStatus(200);
        $response->assertJsonStruture(['data' => ['token']]);

    }

    public function test_password_must_be_required(): void
    {
        # teniendo
        $credenciales = ['email'=> 'prueba@example.com'];

        # haciendo
        $response = $this->post('/api/v1/login', $credenciales);

        # esperando
        $response->assertStatus(200);
        $response->assertJsonStruture(['data' => ['token']]);

    }
}
