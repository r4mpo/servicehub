<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa o relacionamento 1:1 de perfil estendido.
     * Garante que dados sensíveis/adicionais (UserProfile) estão acessíveis via model User.
     */
    public function test_a_user_can_have_a_profile_with_phone_and_position()
    {
        $user = User::factory()->create();
        $user->profile()->create([
            'phone' => '11999999999',
            'position' => 'Developer'
        ]);

        $this->assertEquals('Developer', $user->refresh()->profile->position);
    }
}