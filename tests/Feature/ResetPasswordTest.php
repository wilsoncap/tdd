<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\ResetPasswodNotification;
use Database\Seeders\UserSeeder;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected $token = '';

    protected function setUp():void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }

    # [Test]
    public function test_an_exixting_user_can_reset_their_password(): void
    {
        //$this->withoutExceptionHandling();
        Notification::fake();
        # teniendo
        $data = ['email'=> 'example@example.com'];

        # haciendo
        $response = $this->postJson("{$this->apiBase}/reset-password", $data);
        # esperando
        #dd($response->json());
        # Verificando que el usuario fue creado en la base de datos en memoria
        # $users = \App\Models\User::all();
        # dump($users);  // O usa dd($users) para detener la ejecución y ver los datos

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'OK']);
        $user = User::find(1);
        Notification::assertSentTo([$user], function(ResetPasswodNotification $notification){
            $url = $notification->url;
            $parts = parse_url($url);
            parse_str($parts['query'], $query);
            $this->token = $query['token'];
            return str_contains($url, 'http://front.app/reset-password?token=');
        });
        dd($this->token);
    }


}
