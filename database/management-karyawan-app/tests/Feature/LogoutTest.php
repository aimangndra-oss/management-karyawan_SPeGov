<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_logout()
    {
        // buat user
        $user = User::factory()->create([
            'password' => 'password',
        ]);

        // login via post
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');

        // cek akses dashboard
        $this->get('/dashboard')->assertStatus(200);

        // logout
        $logout = $this->post('/logout');
        $logout->assertRedirect('/');

        // setelah logout, akses dashboard harus redirect ke login
        $this->get('/dashboard')->assertRedirect('/login');
    }
}
