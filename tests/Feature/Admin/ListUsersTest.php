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
            'first_name' => 'User 1'
        ]);

        factory(User::class)->create([
            'first_name' => 'User 2'
        ]);

        $this->get(route('users'))
             ->assertStatus(200)
             ->assertSee('Listado de usuarios')
             ->assertSee('User 1')
             ->assertSee('User 2');

        $this->assertNotRepeatedQueries();
    }

    /** @test */
    function it_paginates_the_users(){
        factory(User::class)->create([
            'first_name' => 'Tercer usuario',
            'created_at' => now()->subDays(5)
        ]);

        factory(User::class)->create([
            'first_name' => 'Primer usuario',
            'created_at' => now()->subWeek()
        ]);

        factory(User::class)->times(12)->create([
            'created_at' => now()->subDays(4)
        ]);

        factory(User::class)->create([
            'first_name' => 'Decimoséptimo usuario',
            'created_at' => now()->subDays(2)
        ]);

        factory(User::class)->create([
            'first_name' => 'Segundo usuario',
            'created_at' => now()->subDays(6)
        ]);

        factory(User::class)->create([
            'first_name' => 'Decimosexto usuario',
            'created_at' => now()->subDays(3)
        ]);

        $this->get(route('users'))
             ->assertStatus(200)
             ->assertSee('Listado de usuarios')
             ->assertSeeInOrder([
                'Decimoséptimo usuario',
                'Decimosexto usuario',
                'Tercer usuario'
             ])
             ->assertDontSee('Segundo usuario')
             ->assertDontSee('Primer usuario');

        $this->get('/usuarios?page=2')
            ->assertSeeInOrder([
                'Segundo usuario',
                'Primer usuario'
            ])
            ->assertDontSee('Tercer usuario')
            ->assertDontSee('Decimosexto usuario')
            ->assertDontSee('Decimoséptimo usuario');
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
            'first_name' => 'User 1',
            'deleted_at' => now()
        ]);

        factory(User::class)->create([
            'first_name' => 'User 2'
        ]);

        $this->get(route('users.trashed'))
             ->assertStatus(200)
             ->assertSee('Listado de usuarios en papelera')
             ->assertSee('User 1')
             ->assertDontSee('User 2');
    }
}
