<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUsersTest extends TestCase
{
    use RefreshDatabase;

    protected $defaultData = [
        'name' => 'Enrique Aguilar',
        'email' => 'enriqueaguilar@expacioweb.com',
        'password' => '123456789',
        'bio' => 'Descripción del usuario en cuestión',
        'twitter' => 'https://twitter.com/notengouser',
        'role' => 'user'
    ];

    /** @test */
    function it_loads_the_edit_user_detail_page(){
        $user = factory(User::class)->create([
            'name' => 'Enrique Aguilar'
        ]);

        $this->get(route('users.edit', $user))
             ->assertStatus(200)
             ->assertViewIs('user.edit')
             ->assertSee('Editar Usuario #'.$user->id)
             ->assertViewHas('user', $user);
    }

    /** @test */
    function it_does_not_load_the_edit_user_detail_page_with_text_id(){
        $this->withExceptionHandling();
        $this->get(route('users.edit', ['user' => 'texto']))
             ->assertStatus(404);
    }

    /** @test */
    function it_updates_a_new_user(){
        $user= factory(User::class)->create();

        $this->from(route('users.edit', $user))
            ->put(route('users.update', $user), [
                'name' => 'Enrique Aguilar',
                'email' => 'enriqueaguilar@expacioweb.com',
                'password' => '123456789'
            ])
            ->assertRedirect(route('users.show', $user));

        $this->assertCredentials([
            'name' => 'Enrique Aguilar',
            'email' => 'enriqueaguilar@expacioweb.com',
            'password' => '123456789'
        ]);
    }

    /** @test */
    function the_user_is_redirected_to_create_if_data_is_not_valid(){
        $this->handleValidationExceptions();
        $user= factory(User::class)->create();
        $this->from(route('users.edit', $user))
            ->put(route('users.update', $user), [])
            ->assertRedirect(route('users.edit', $user));
    }

    /** @test */
    function the_name_is_required(){
        $this->handleValidationExceptions();
        $user= factory(User::class)->create();

        $this->put(route('users.update', $user), [
                'name' => '',
                'email' => 'enriqueaguilar@expacioweb.com',
                'password' => '123456'
            ])
            ->assertSessionHasErrors(['name' => 'El campo nombre es obligatorio']);

        $this->assertDatabaseMissing('users', ['email' => 'enriqueaguilar@expacioweb.com']);
    }

    /** @test */
    function the_email_is_required(){
        $this->handleValidationExceptions();
        $user= factory(User::class)->create([
            'name' => 'No es Enrique'
        ]);

        $this->put(route('users.update', $user), [
                'name' => 'Enrique Aguilar',
                'email' => '',
                'password' => '123456'
            ])
            ->assertSessionHasErrors(['email' => 'El campo email es obligatorio']);

        $this->assertDatabaseMissing('users', ['name' => 'Enrique Aguilar']);
    }

    /** @test */
    function the_email_must_be_valid(){
        $this->handleValidationExceptions();
        $user= factory(User::class)->create();

        $this->put(route('users.update', $user), [
                'name' => 'Enrique Aguilar',
                'email' => 'email-no-valido',
                'password' => '123456'
            ])
            ->assertSessionHasErrors(['email' => 'El email insertado no es válido']);

        $this->assertDatabaseMissing('users', ['email' => 'email-no-valido']);
    }

    /** @test */
    function the_email_must_be_unique(){
        $this->handleValidationExceptions();
        $randomUser = factory(User::class)->create();
        $user= factory(User::class)->create();

        $this->put(route('users.update', $user), [
                'name' => 'Enrique Aguilar',
                'email' => $randomUser->email,
                'password' => '123456'
            ])
            ->assertSessionHasErrors(['email' => 'Email en uso']);

        $this->assertDatabaseMissing('users', [
            'name' => 'Enrique Aguilar',
            'email' => $randomUser->email
        ]);
    }

    /** @test */
    function the_user_email_can_stay_the_same(){
        $user= factory(User::class)->create([
            'email' => 'enriqueaguilar@expacioweb.com'
        ]);

        $this->put(route('users.update', $user), [
                'name' => 'Enrique Aguilar',
                'email' => 'enriqueaguilar@expacioweb.com',
                'password' => '123456'
            ])
            ->assertRedirect(route('users.show', $user));

        $this->assertCredentials([
            'name' => 'Enrique Aguilar',
            'email' => 'enriqueaguilar@expacioweb.com',
            'password' => '123456'
        ]);
    }

    /** @test */
    function the_password_is_optional(){
        $pass = 'CONTRASEÑA';

        $user= factory(User::class)->create([
            'password' => bcrypt($pass)
        ]);

        $this->put(route('users.update', $user), [
                'name' => 'Enrique Aguilar',
                'email' => 'enriqueaguilar@expacioweb.com',
                'password' => ''
            ])
            ->assertRedirect(route('users.show', $user));

        $this->assertCredentials([
            'name' => 'Enrique Aguilar',
            'email' => 'enriqueaguilar@expacioweb.com',
            'password' => $pass
        ]);
    }
}
