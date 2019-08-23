<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersModuleTest extends TestCase
{
    /** @test */
    function it_shows_the_users_list(){
        $this->get('/usuarios')
             ->assertStatus(200)
             ->assertSee('Listado de usuarios')
             ->assertSee('User 1')
             ->assertSee('User 2');
    }

    /** @test */
    function it_shows_a_default_message_if_there_are_no_users(){
        $this->get('/usuarios?empty')
             ->assertStatus(200)
             ->assertSee('No hay usuarios registrados');
    }

    /** @test */
    function it_loads_the_user_detail_page(){
        $this->get('/usuarios/5')
             ->assertStatus(200)
             ->assertSee('Mostrando detalle del usuario: 5');
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
