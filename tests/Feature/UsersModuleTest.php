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
    function it_displays_the_users_details(){
        $user = factory(User::class)->create([
            'name' => 'Enrique Aguilar'
        ]);

        $this->get(route('users.show', compact('user')))
             ->assertStatus(200)
             ->assertSee('Enrique Aguilar');
    }

    /** @test */
    function it_displays_a_404_error_if_the_user_is_not_found(){
        $this->get(route('users.show', ['user' => 999]))
             ->assertStatus(404)
             ->assertSee('Página no encontrada');
    }

    /** @test */
    function it_loads_the_new_user_page(){
        $this->get(route('users.create'))
             ->assertStatus(200)
             ->assertSee('Crear Nuevo Usuario');
    }

    /** @test */
    function it_creates_a_new_user(){
        $this->from(route('users.create'))
            ->post(route('users.store'), [
                'name' => 'Enrique Aguilar',
                'email' => 'enriqueaguilar@expacioweb.com',
                'password' => '123456789'
            ])
            ->assertRedirect(route('users'));

        $this->assertCredentials([
            'name' => 'Enrique Aguilar',
            'email' => 'enriqueaguilar@expacioweb.com',
            'password' => '123456789'
        ]);
    }

    /** @test */
    function the_name_is_required(){
        $this->from(route('users.create'))
            ->post(route('users.store'), [
                'name' => '',
                'email' => 'enriqueaguilar@expacioweb.com',
                'password' => '123456'
            ])
            ->assertRedirect(route('users.create'))
            ->assertSessionHasErrors(['name' => 'El campo nombre es obligatorio']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function the_email_is_required(){
        $this->from(route('users.create'))
            ->post(route('users.store'), [
                'name' => 'Enrique',
                'email' => '',
                'password' => '123456'
            ])
            ->assertRedirect(route('users.create'))
            ->assertSessionHasErrors(['email' => 'El campo email es obligatorio']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function the_email_must_be_valid(){
        $this->from(route('users.create'))
            ->post(route('users.store'), [
                'name' => 'Enrique',
                'email' => 'correo no valido',
                'password' => '123456'
            ])
            ->assertRedirect(route('users.create'))
            ->assertSessionHasErrors(['email' => 'El email insertado no es válido']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function the_email_must_be_unique(){
        $user = factory(User::class)->create();

        $this->from(route('users.create'))
            ->post(route('users.store'), [
                'name' => 'Enrique',
                'email' => $user->email,
                'password' => '123456'
            ])
            ->assertRedirect(route('users.create'))
            ->assertSessionHasErrors(['email' => 'Email en uso']);

        $this->assertEquals(1, User::count());
    }

    /** @test */
    function the_password_is_required(){
        $this->from(route('users.create'))
            ->post(route('users.store'), [
                'name' => 'Enrique',
                'email' => 'enriqueaguilar@expacioweb.com',
                'password' => ''
            ])
            ->assertRedirect(route('users.create'))
            ->assertSessionHasErrors(['password' => 'El campo password es obligatorio']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function the_password_must_be_greater_than_6_characters(){
        $this->from(route('users.create'))
            ->post(route('users.store'), [
                'name' => 'Enrique',
                'email' => 'enriqueaguilar@expacioweb.com',
                'password' => '12345'
            ])
            ->assertRedirect(route('users.create'))
            ->assertSessionHasErrors(['password' => 'El campo password es demasiado corto']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function it_loads_the_edit_user_detail_page(){
        $user = factory(User::class)->create([
            'name' => 'Enrique Aguilar'
        ]);

        $this->get(route('users.edit', compact('user')))
             ->assertStatus(200)
             ->assertViewIs('user.edit')
             ->assertSee('Editar Usuario #'.$user->id)
             ->assertViewHas('user', $user);
    }

    /** @test */
    function it_does_not_load_the_edit_user_detail_page_with_text_id(){
        $this->get(route('users.edit', ['user' => 'texto']))
             ->assertStatus(404);
    }
}
