<?php

namespace Tests\Feature\Admin;

use App\Models\{User,Profession,Skill};
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateUsersTest extends TestCase
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
        $profession = factory(Profession::class)->create();

        $skillA = factory(Skill::class)->create();
        $skillB = factory(Skill::class)->create();
        $skillC = factory(Skill::class)->create();

        $this->from(route('users.create'))
            ->post(route('users.store'), $this->withData([
                'skills' => [$skillA->id, $skillB->id],
                'profession_id' => $profession->id
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
            'profession_id' => $profession->id
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
    function the_user_is_redirected_to_create_if_data_is_not_valid(){
        $this->handleValidationExceptions();
        $this->from(route('users.create'))
            ->post(route('users.store'), [])
            ->assertRedirect(route('users.create'));

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function the_name_is_required(){
        $this->handleValidationExceptions();
        $this->post(route('users.store'), $this->withData([
                'name' => ''
            ]))
            ->assertSessionHasErrors(['name' => 'El campo nombre es obligatorio']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function the_email_is_required(){
        $this->handleValidationExceptions();
        $this->post(route('users.store'), $this->withData([
                'email' => ''
            ]))
            ->assertSessionHasErrors(['email' => 'El campo email es obligatorio']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function the_email_must_be_valid(){
        $this->handleValidationExceptions();
        $this->post(route('users.store'), $this->withData([
                'email' => 'email no valido'
            ]))
            ->assertSessionHasErrors(['email' => 'El email insertado no es válido']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function the_email_must_be_unique(){
        $this->handleValidationExceptions();
        $user = factory(User::class)->create();

        $this->post(route('users.store'), $this->withData([
                'email' => $user->email
            ]))
            ->assertSessionHasErrors(['email' => 'Email en uso']);

        $this->assertEquals(1, User::count());
    }

    /** @test */
    function the_password_is_required(){
        $this->handleValidationExceptions();
        $this->post(route('users.store'), $this->withData([
                'password' => ''
            ]))
            ->assertSessionHasErrors(['password' => 'El campo password es obligatorio']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function the_password_must_be_greater_than_6_characters(){
        $this->handleValidationExceptions();
        $this->post(route('users.store'), [
                'name' => 'Enrique',
                'email' => 'enriqueaguilar@expacioweb.com',
                'password' => '12345',
                'bio' => 'Descripción del usuario en cuestión',
                'twitter' => 'https://twitter.com/notengouser'
            ])
            ->assertSessionHasErrors(['password' => 'El campo password es demasiado corto']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function the_bio_is_required(){
        $this->handleValidationExceptions();
        $this->post(route('users.store'), [
                'name' => 'Enrique',
                'email' => 'enriqueaguilar@expacioweb.com',
                'password' => '123456',
                'bio' => '',
                'twitter' => 'https://twitter.com/notengouser'
            ])
            ->assertSessionHasErrors(['bio' => 'El campo biografia es obligatorio']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function the_twitter_is_optional(){
        $this->post(route('users.store'), [
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
        $this->handleValidationExceptions();
        $this->post(route('users.store'), [
                'name' => 'Enrique',
                'email' => 'enriqueaguilar@expacioweb.com',
                'password' => '123456',
                'bio' => 'Descripción del usuario en cuestión',
                'twitter' => 'twitter no valido'
            ])
            ->assertSessionHasErrors(['twitter' => 'La url de twitter insertada no es válida']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function the_skills_must_be_an_array(){
        $this->handleValidationExceptions();
        $this->post(route('users.store'), $this->withData([
                'skills' => 'PHP, OOP, JS'
            ]))
            ->assertSessionHasErrors(['skills' => 'El campo skills debe ser un array']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function the_skills_must_be_valid(){
        $this->handleValidationExceptions();
        $skillA = factory(Skill::class)->create();
        $skillB = factory(Skill::class)->create();

        $this->post(route('users.store'), $this->withData([
                'skills' => [$skillA->id, $skillB->id + 1]
            ]))
            ->assertSessionHasErrors(['skills' => 'El campo skills contiene valores no válidos']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function the_role_field_is_optional(){
        $this->handleValidationExceptions();
        $this->post(route('users.store'), $this->withData([
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
        $this->handleValidationExceptions();
        $this->post(route('users.store'), $this->withData([
                'role' => 'invalid-role'
            ]))
            ->assertSessionHasErrors(['role' => 'El campo rol debe ser válido']);

        $this->assertEquals(0, User::count());
    }



    /** @test */
    function the_twitter_field_is_optional(){
        $this->post(route('users.store'), $this->withData([
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
        $this->post(route('users.store'), $this->withData([
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
        $this->handleValidationExceptions();
        $this->post(route('users.store'), $this->withData([
                'profession_id' => '999'
            ]))
            ->assertSessionHasErrors(['profession_id' => 'El campo profesión es obligatorio']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function only_not_deleted_profession_are_valid(){
        $this->handleValidationExceptions();
        $nonSelectableProfession = factory(Profession::class)->create([
            'deleted_at' => now()->format('Y-m-d')
        ]);

        $this->post(route('users.store'), $this->withData([
                'profession_id' => $nonSelectableProfession->id
            ]))
            ->assertSessionHasErrors(['profession_id' => 'El campo profesión es obligatorio']);

        $this->assertEquals(0, User::count());
    }
}
