<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersModuleTest extends TestCase
{
    /** @test */
    function it_loads_the_users_list_page(){
        $this->get('/usuarios')
             ->assertStatus(200)
             ->assertSee('Usuarios');
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
