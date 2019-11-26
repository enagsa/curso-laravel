<?php

namespace Tests\Feature\Admin;

use App\Models\{User,UserProfile};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsersProfileTest extends TestCase
{
    use RefreshDatabase;

    protected $defaultData = [
        'name' => 'Enrique Aguilar',
        'email' => 'enriqueaguilar@expacioweb.com',
        'password' => '123456789',
        'bio' => 'Descripción del usuario en cuestión',
        'twitter' => 'https://twitter.com/notengouser'
    ];

    /** @test */
    function a_user_can_edit_its_profile(){
        $user = factory(User::class)->create();
        $user->profile()->save(factory(UserProfile::class)->make());

        $newProfession = factory(Profession::class)->create();

        $response = $this->get(route('edit.profile'));

        $response->assertStatus(200);

        $response = $this->put(route('update.profile'), [
            'name' => 'Enrique',
            'email' => 'enriqueaguilar@expacioweb.com',
            'password' => '123456',
            'bio' => 'Full-stack developer',
            'twitter' => 'https://twitter.com/esfalso',
            'profession_id' => $newProfession->id
        ]);

        $response->assertRedirect();

        $this->assertCredentials([
            'name' => 'Enrique',
            'email' => 'enriqueaguilar@expacioweb.com',
            'password' => '123456'
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'bio' => 'Full-stack developer',
            'twitter' => 'https://twitter.com/esfalso',
            'profession_id' => $newProfession->id
        ]);
    }

    /** @test */
    function the_user_cannot_change_its_role(){
        user = factory(User::class)->create([
            'role' => 'user'
        ]);

        $resposne = $this->put(route('update.profile'), $this->withData([
            'role' => 'admin'
        ]));

        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'user'
        ]);
    }
}
