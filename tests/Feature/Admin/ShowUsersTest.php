<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_displays_the_users_details(){
        $user = factory(User::class)->create([
            'name' => 'Enrique Aguilar'
        ]);

        $this->get(route('users.show', $user))
             ->assertStatus(200)
             ->assertSee('Enrique Aguilar');
    }

    /** @test */
    function it_displays_a_404_error_if_the_user_is_not_found(){
        $this->withExceptionHandling();
        $this->get(route('users.show', ['user' => 999]))
             ->assertStatus(404)
             ->assertSee('Página no encontrada');
    }
}
