<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_shows_the_users_list(){
        factory(User::class)->create([
            'name' => 'User 1'
        ]);

        factory(User::class)->create([
            'name' => 'User 2'
        ]);

        $this->get(route('users'))
             ->assertStatus(200)
             ->assertSee('Listado de usuarios')
             ->assertSee('User 1')
             ->assertSee('User 2');
    }

    /** @test */
    function it_shows_a_default_message_if_there_are_no_users(){
        $this->get(route('users'))
             ->assertStatus(200)
             ->assertSee('No hay usuarios registrados');
    }

    /** @test */
    function it_shows_the_deleted_users(){
        factory(User::class)->create([
            'name' => 'User 1',
            'deleted_at' => now()
        ]);

        factory(User::class)->create([
            'name' => 'User 2'
        ]);

        $this->get(route('users.trashed'))
             ->assertStatus(200)
             ->assertSee('Listado de usuarios en papelera')
             ->assertSee('User 1')
             ->assertDontSee('User 2');
    }
}
