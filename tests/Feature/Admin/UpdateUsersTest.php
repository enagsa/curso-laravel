<?php

namespace Tests\Feature\Admin;

use App\Models\{User,UserProfile,Profession,Skill};
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUsersTest extends TestCase
{
    use RefreshDatabase;

    protected $defaultData = [
        'first_name' => 'Enrique',
        'last_name' => 'Aguilar',
        'email' => 'enriqueaguilar@expacioweb.com',
        'password' => '123456789',
        'bio' => 'Descripción del usuario en cuestión',
        'twitter' => 'https://twitter.com/notengouser',
        'role' => 'user'
    ];

    /** @test */
    function it_loads_the_edit_user_detail_page(){
        $user = factory(User::class)->create();

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
        $user = factory(User::class)->create();

        $oldProfession = factory(Profession::class)->create();
        $user->profile()->save(factory(UserProfile::class)->make([
            'profession_id' => $oldProfession->id
        ]));
        $oldSkill1 = factory(Skill::class)->create();
        $oldSkill2 = factory(Skill::class)->create();
        $user->skills()->attach([$oldSkill1->id,$oldSkill2->id]);

        $newProfession = factory(Profession::class)->create();
        $newSkill1 = factory(Skill::class)->create();
        $newSkill2 = factory(Skill::class)->create();

        $this->from(route('users.edit', $user))
            ->put(route('users.update', $user), [
                'first_name' => 'Enrique',
                'last_name' => 'Aguilar',
                'email' => 'enriqueaguilar@expacioweb.com',
                'password' => '123456789',
                'bio' => 'Descripción del usuario en cuestión',
                'twitter' => 'https://twitter.com/notengouser',
                'role' => 'admin',
                'profession_id' => $newProfession->id,
                'skills' => [$newSkill1->id,$newSkill2->id]
            ])
            ->assertRedirect(route('users.show', $user));

        $this->assertCredentials([
            'first_name' => 'Enrique',
            'last_name' => 'Aguilar',
            'email' => 'enriqueaguilar@expacioweb.com',
            'password' => '123456789',
            'role' => 'admin'
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $user->id,
            'bio' => 'Descripción del usuario en cuestión',
            'twitter' => 'https://twitter.com/notengouser',
            'profession_id' => $newProfession->id
        ]);

        $this->assertDatabaseCount('user_skill', 2);

        $this->assertDatabaseHas('user_skill',[
            'user_id' => $user->id,
            'skill_id' => $newSkill1->id
        ]);

        $this->assertDatabaseHas('user_skill',[
            'user_id' => $user->id,
            'skill_id' => $newSkill2->id
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
    function the_first_name_is_required(){
        $this->handleValidationExceptions();
        $user= factory(User::class)->create();

        $this->put(route('users.update', $user), $this->withData([
                'first_name' => ''
            ]))
            ->assertSessionHasErrors(['first_name' => 'El campo nombre es obligatorio']);

        $this->assertDatabaseMissing('users', ['email' => 'enriqueaguilar@expacioweb.com']);
    }

    /** @test */
    function the_last_name_is_required(){
        $this->handleValidationExceptions();
        $user= factory(User::class)->create();

        $this->put(route('users.update', $user), $this->withData([
                'last_name' => ''
            ]))
            ->assertSessionHasErrors(['last_name' => 'El campo apellido es obligatorio']);

        $this->assertDatabaseMissing('users', ['email' => 'enriqueaguilar@expacioweb.com']);
    }

    /** @test */
    function the_email_is_required(){
        $this->handleValidationExceptions();
        $user= factory(User::class)->create([
            'first_name' => 'No es Enrique'
        ]);

        $this->put(route('users.update', $user), $this->withData([
                'email' => ''
            ]))
            ->assertSessionHasErrors(['email' => 'El campo email es obligatorio']);

        $this->assertDatabaseMissing('users', ['first_name' => 'Enrique']);
    }

    /** @test */
    function the_email_must_be_valid(){
        $this->handleValidationExceptions();
        $user= factory(User::class)->create();

        $this->put(route('users.update', $user), $this->withData([
                'email' => 'email-no-valido'
            ]))
            ->assertSessionHasErrors(['email' => 'El email insertado no es válido']);

        $this->assertDatabaseMissing('users', ['email' => 'email-no-valido']);
    }

    /** @test */
    function the_email_must_be_unique(){
        $this->handleValidationExceptions();
        $randomUser = factory(User::class)->create();
        $user= factory(User::class)->create();

        $this->put(route('users.update', $user), $this->withData([
                'email' => $randomUser->email
            ]))
            ->assertSessionHasErrors(['email' => 'Email en uso']);

        $this->assertDatabaseMissing('users', [
            'first_name' => 'Enrique',
            'last_name' => 'Aguilar',
            'email' => $randomUser->email
        ]);
    }

    /** @test */
    function the_user_email_can_stay_the_same(){
        $user= factory(User::class)->create([
            'email' => 'enriqueaguilar@expacioweb.com'
        ]);

        $this->put(route('users.update', $user), $this->withData([
                'email' => 'enriqueaguilar@expacioweb.com'
            ]))
            ->assertRedirect(route('users.show', $user));

        $this->assertCredentials([
            'first_name' => 'Enrique',
            'last_name' => 'Aguilar',
            'email' => 'enriqueaguilar@expacioweb.com',
            'password' => '123456789',
        ]);
    }

    /** @test */
    function the_password_is_optional(){
        $pass = 'CONTRASEÑA';

        $user= factory(User::class)->create([
            'password' => bcrypt($pass)
        ]);

        $this->put(route('users.update', $user), $this->withData([
                'password' => ''
            ]))
            ->assertRedirect(route('users.show', $user));

        $this->assertCredentials([
            'first_name' => 'Enrique',
            'last_name' => 'Aguilar',
            'email' => 'enriqueaguilar@expacioweb.com',
            'password' => $pass
        ]);
    }

    /** @test */
    function it_detaches_all_the_skills_if_none_is_checked(){
        $user = factory(User::class)->create();

        $oldSkill1 = factory(Skill::class)->create();
        $oldSkill2 = factory(Skill::class)->create();
        $user->skills()->attach([$oldSkill1->id,$oldSkill2->id]);

        $this->from(route('users.edit', $user))
            ->put(route('users.update', $user), $this->withData())
            ->assertRedirect(route('users.show', $user));

        $this->assertDatabaseEmpty('user_skill');
    }

    /** @test */
    function the_role_is_required(){
        $this->handleValidationExceptions();
        $user= factory(User::class)->create();

        $this->put(route('users.update', $user), $this->withData([
                'role' => ''
            ]))
            ->assertSessionHasErrors(['role' => 'El campo rol es obligatorio']);

        $this->assertDatabaseMissing('users', ['email' => 'enriqueaguilar@expacioweb.com']);
    }
}
