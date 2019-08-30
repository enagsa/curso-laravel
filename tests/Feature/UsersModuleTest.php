<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class UsersModuleTest extends TestCase
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

        $this->get('/usuarios')
             ->assertStatus(200)
             ->assertSee('Listado de usuarios')
             ->assertSee('User 1')
             ->assertSee('User 2');
    }

    /** @test */
    function it_shows_a_default_message_if_there_are_no_users(){
        $this->get('/usuarios')
             ->assertStatus(200)
             ->assertSee('No hay usuarios registrados');
    }

    /** @test */
    function it_displays_the_users_details(){
        $user = factory(User::class)->create([
            'name' => 'Enrique Aguilar'
        ]);

        $this->get('/usuarios/'.$user->id)
             ->assertStatus(200)
             ->assertSee('Enrique Aguilar');
    }

    /** @test */
    function it_displays_a_404_error_if_the_user_is_not_found(){
        $this->get('/usuarios/999')
             ->assertStatus(404)
             ->assertSee('Página no encontrada');
    }

    /** @test */
    function it_loads_the_new_user_page(){
        $this->get('/usuarios/nuevo')
             ->assertStatus(200)
             ->assertSee('Crear Nuevo Usuario');
    }

    /** @test */
    function it_loads_the_edit_user_detail_page(){
        $this->get('/usuarios/5/edit')
             ->assertStatus(200)
             ->assertSee('Editando detalles del usuario: 5');
    }

    /** @test */
    function it_does_not_load_the_edit_user_detail_page_with_text_id(){
        $this->get('/usuarios/texto/edit')
             ->assertStatus(404);
    }
}
