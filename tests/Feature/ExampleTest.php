<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * @test.
     */
    public function test_hello_world_route_return_success(): void
    {

        #teniendo
        // teniendo unas credenciales
        
        
        #haciendo
        // una peticion con las credenciales
        $response = $this->get('api/hello-world');



        #Esperando
        // yo espetando a que el usario se loguee




        $response->assertJson(['msg' => 'hello-word']);
    }
}
