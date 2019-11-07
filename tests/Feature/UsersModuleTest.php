<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{User,Profession,Skill};

class UsersModuleTest extends TestCase
{
    use RefreshDatabase;

    protected $profession;

    protected function getValidData($custom = []){
        $this->profession = factory(Profession::class)->create();

        return array_filter(array_merge([
            'name' => 'Enrique Aguilar',
            'email' => 'enriqueaguilar@expacioweb.com',
            'password' => '123456789',
            'profession_id' => $this->profession->id,
            'bio' => 'Descripción del usuario en cuestión',
            'twitter' => 'https://twitter.com/notengouser',
            'role' => 'user'
        ], $custom));
    }

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

        $this->get(route('users.show', $user))
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
        $profession = factory(Profession::class)->create();

        $skillA = factory(Skill::class)->create();
        $skillB = factory(Skill::class)->create();

        $this->get(route('users.create'))
             ->assertStatus(200)
             ->assertSee('Crear Nuevo Usuario')
             ->assertViewHas('professions', function($professions) use($profession){
                return $professions->contains($profession);
             })
             ->assertViewHas('skills', function($skills) use($skillA,$skillB){
                return $skills->contains($skillA) && $skills->contains($skillB);
             });
    }

    /** @test */
    function it_creates_a_new_user(){
        $this->withoutExceptionHandling();

        $skillA = factory(Skill::class)->create();
        $skillB = factory(Skill::class)->create();
        $skillC = factory(Skill::class)->create();

        $this->from(route('users.create'))
            ->post(route('users.store'), $this->getValidData([
                'skills' => [$skillA->id, $skillB->id]
            ]))
            ->assertRedirect(route('users'));

        $this->assertCredentials([
            'name' => 'Enrique Aguilar',
            'email' => 'enriqueaguilar@expacioweb.com',
            'password' => '123456789',
            'role' => 'user'
        ]);

        $user = User::first();

        $this->assertDatabaseHas('user_profiles', [
            'bio' => 'Descripción del usuario en cuestión',
            'twitter' => 'https://twitter.com/notengouser',
            'user_id' => $user->id,
            'profession_id' => $this->profession->id
        ]);

        $this->assertDatabaseHas('user_skill', [
            'user_id' => $user->id,
            'skill_id' => $skillA->id
        ]);

        $this->assertDatabaseHas('user_skill', [
            'user_id' => $user->id,
            'skill_id' => $skillB->id
        ]);

        $this->assertDatabaseMissing('user_skill', [
            'user_id' => $user->id,
            'skill_id' => $skillC->id
        ]);
    }

    /** @test */
    function the_twitter_field_is_optional(){
        $this->from(route('users.create'))
            ->post(route('users.store'), $this->getValidData([
                'twitter' => null
            ]))
            ->assertRedirect(route('users'));

        $this->assertCredentials([
            'name' => 'Enrique Aguilar',
            'email' => 'enriqueaguilar@expacioweb.com',
            'password' => '123456789'
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'bio' => 'Descripción del usuario en cuestión',
            'twitter' => null,
            'user_id' => User::first()->id
        ]);
    }

    /** @test */
    function the_profession_id_field_is_optional(){
        $this->from(route('users.create'))
            ->post(route('users.store'), $this->getValidData([
                'profession_id' => null
            ]))
            ->assertRedirect(route('users'));

        $this->assertCredentials([
            'name' => 'Enrique Aguilar',
            'email' => 'enriqueaguilar@expacioweb.com',
            'password' => '123456789'
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'bio' => 'Descripción del usuario en cuestión',
            'twitter' => 'https://twitter.com/notengouser',
            'user_id' => User::first()->id,
            'profession_id' => null
        ]);
    }

    /** @test */
    function the_profession_must_be_valid(){
        $this->from(route('users.create'))
            ->post(route('users.store'), $this->getValidData([
                'profession_id' => '999'
            ]))
            ->assertRedirect(route('users.create'))
            ->assertSessionHasErrors(['profession_id' => 'El campo profesión es obligatorio']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function only_not_deleted_profession_are_valid(){
        $nonSelectableProfession = factory(Profession::class)->create([
            'deleted_at' => now()->format('Y-m-d')
        ]);

        $this->from(route('users.create'))
            ->post(route('users.store'), $this->getValidData([
                'profession_id' => $nonSelectableProfession->id
            ]))
            ->assertRedirect(route('users.create'))
            ->assertSessionHasErrors(['profession_id' => 'El campo profesión es obligatorio']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function the_name_is_required(){
        $this->from(route('users.create'))
            ->post(route('users.store'), $this->getValidData([
                'name' => ''
            ]))
            ->assertRedirect(route('users.create'))
            ->assertSessionHasErrors(['name' => 'El campo nombre es obligatorio']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function the_email_is_required(){
        $this->from(route('users.create'))
            ->post(route('users.store'), $this->getValidData([
                'email' => ''
            ]))
            ->assertRedirect(route('users.create'))
            ->assertSessionHasErrors(['email' => 'El campo email es obligatorio']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function the_email_must_be_valid(){
        $this->from(route('users.create'))
            ->post(route('users.store'), $this->getValidData([
                'email' => 'email no valido'
            ]))
            ->assertRedirect(route('users.create'))
            ->assertSessionHasErrors(['email' => 'El email insertado no es válido']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function the_email_must_be_unique(){
        $user = factory(User::class)->create();

        $this->from(route('users.create'))
            ->post(route('users.store'), $this->getValidData([
                'email' => $user->email
            ]))
            ->assertRedirect(route('users.create'))
            ->assertSessionHasErrors(['email' => 'Email en uso']);

        $this->assertEquals(1, User::count());
    }

    /** @test */
    function the_password_is_required(){
        $this->from(route('users.create'))
            ->post(route('users.store'), $this->getValidData([
                'password' => ''
            ]))
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
                'password' => '12345',
                'bio' => 'Descripción del usuario en cuestión',
                'twitter' => 'https://twitter.com/notengouser'
            ])
            ->assertRedirect(route('users.create'))
            ->assertSessionHasErrors(['password' => 'El campo password es demasiado corto']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function the_bio_is_required(){
        $this->from(route('users.create'))
            ->post(route('users.store'), [
                'name' => 'Enrique',
                'email' => 'enriqueaguilar@expacioweb.com',
                'password' => '123456',
                'bio' => '',
                'twitter' => 'https://twitter.com/notengouser'
            ])
            ->assertRedirect(route('users.create'))
            ->assertSessionHasErrors(['bio' => 'El campo biografia es obligatorio']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function the_twitter_is_optional(){
        $this->from(route('users.create'))
            ->post(route('users.store'), [
                'name' => 'Enrique Aguilar',
                'email' => 'enriqueaguilar@expacioweb.com',
                'password' => '123456789',
                'bio' => 'Descripción del usuario en cuestión',
                'twitter' => ''
            ])
            ->assertRedirect(route('users'));

        $this->assertCredentials([
            'name' => 'Enrique Aguilar',
            'email' => 'enriqueaguilar@expacioweb.com',
            'password' => '123456789'
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'bio' => 'Descripción del usuario en cuestión',
            'twitter' => null,
            'user_id' => User::first()->id
        ]);
    }

    /** @test */
    function the_twitter_must_be_valid(){
        $this->from(route('users.create'))
            ->post(route('users.store'), [
                'name' => 'Enrique',
                'email' => 'enriqueaguilar@expacioweb.com',
                'password' => '123456',
                'bio' => 'Descripción del usuario en cuestión',
                'twitter' => 'twitter no valido'
            ])
            ->assertRedirect(route('users.create'))
            ->assertSessionHasErrors(['twitter' => 'La url de twitter insertada no es válida']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function the_skills_must_be_an_array(){
        $this->from(route('users.create'))
            ->post(route('users.store'), $this->getValidData([
                'skills' => 'PHP, OOP, JS'
            ]))
            ->assertRedirect(route('users.create'))
            ->assertSessionHasErrors(['skills' => 'El campo skills debe ser un array']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function the_skills_must_be_valid(){
        $skillA = factory(Skill::class)->create();
        $skillB = factory(Skill::class)->create();

        $this->from(route('users.create'))
            ->post(route('users.store'), $this->getValidData([
                'skills' => [$skillA->id, $skillB->id + 1]
            ]))
            ->assertRedirect(route('users.create'))
            ->assertSessionHasErrors(['skills' => 'El campo skills contiene valores no válidos']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function the_role_field_is_optional(){
        $this->from(route('users.create'))
            ->post(route('users.store'), $this->getValidData([
                'role' => null
            ]))
            ->assertRedirect(route('users'));

        $this->assertCredentials([
            'name' => 'Enrique Aguilar',
            'email' => 'enriqueaguilar@expacioweb.com',
            'password' => '123456789',
            'role' => 'user'
        ]);
    }

    /** @test */
    function the_role_field_must_be_valid(){
        $this->from(route('users.create'))
            ->post(route('users.store'), $this->getValidData([
                'role' => 'invalid-role'
            ]))
            ->assertRedirect(route('users.create'))
            ->assertSessionHasErrors(['role' => 'El campo rol debe ser válido']);

        $this->assertEquals(0, User::count());
    }

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
    function the_name_is_required_when_updating_user(){
        $user= factory(User::class)->create();

        $this->from(route('users.edit', $user))
            ->put(route('users.update', $user), [
                'name' => '',
                'email' => 'enriqueaguilar@expacioweb.com',
                'password' => '123456'
            ])
            ->assertRedirect(route('users.edit', $user))
            ->assertSessionHasErrors(['name' => 'El campo nombre es obligatorio']);

        $this->assertDatabaseMissing('users', ['email' => 'enriqueaguilar@expacioweb.com']);
    }

    /** @test */
    function the_email_is_required_when_updating_user(){
        $user= factory(User::class)->create([
            'name' => 'No es Enrique'
        ]);

        $this->from(route('users.edit', $user))
            ->put(route('users.update', $user), [
                'name' => 'Enrique Aguilar',
                'email' => '',
                'password' => '123456'
            ])
            ->assertRedirect(route('users.edit', $user))
            ->assertSessionHasErrors(['email' => 'El campo email es obligatorio']);

        $this->assertDatabaseMissing('users', ['name' => 'Enrique Aguilar']);
    }

    /** @test */
    function the_email_must_be_valid_when_updating_user(){
        $user= factory(User::class)->create();

        $this->from(route('users.edit', $user))
            ->put(route('users.update', $user), [
                'name' => 'Enrique Aguilar',
                'email' => 'email-no-valido',
                'password' => '123456'
            ])
            ->assertRedirect(route('users.edit', $user))
            ->assertSessionHasErrors(['email' => 'El email insertado no es válido']);

        $this->assertDatabaseMissing('users', ['email' => 'email-no-valido']);
    }

    /** @test */
    function the_email_must_be_unique_when_updating_user(){
        $randomUser = factory(User::class)->create();
        $user= factory(User::class)->create();

        $this->from(route('users.edit', $user))
            ->put(route('users.update', $user), [
                'name' => 'Enrique Aguilar',
                'email' => $randomUser->email,
                'password' => '123456'
            ])
            ->assertRedirect(route('users.edit', $user))
            ->assertSessionHasErrors(['email' => 'Email en uso']);


    }

    /** @test */
    function the_user_email_can_stay_the_same_when_updating_user(){
        $user= factory(User::class)->create([
            'email' => 'enriqueaguilar@expacioweb.com'
        ]);

        $this->from(route('users.edit', $user))
            ->put(route('users.update', $user), [
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
    function the_password_is_optional_when_updating_user(){
        $pass = 'CONTRASEÑA';

        $user= factory(User::class)->create([
            'password' => bcrypt($pass)
        ]);

        $this->from(route('users.edit', $user))
            ->put(route('users.update', $user), [
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

    /** @test */
    function it_deletes_a_user(){
        $user = factory(User::class)->create();

        $this->delete(route('users.destroy', $user))
            ->assertRedirect(route('users'));

        $this->assertSame(0, User::count());
    }
}
