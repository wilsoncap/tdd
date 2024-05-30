<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_an_exixting_user_can_login(): void
    {
        $this->withoutExceptionHandling();
        # teniendo
        $credenciales = ['email'=> 'prueba@example.com', 'password'=> 'pasword'];

        # haciendo
        $response = $this->post("{$this->apiBase}/login", $credenciales);

        // dd($response->getContent());
        $response->dump();

        # esperando
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['token']]);

    }

    public function test_a_no_exixting_user_can_login(): void
    {
        # teniendo
        $credenciales = ['email'=> 'prueba@nonexting.com', 'password'=> '1234hjk'];

        # haciendo
        $response = $this->post('/api/v1/login', $credenciales);

        # esperando
        $response->assertStatus(200);
        $response->assertJsonStruture(['data' => ['token']]);

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
