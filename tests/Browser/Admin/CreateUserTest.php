<?php

namespace Tests\Browser\Admin;

use App\Models\{User,Profession,Skill};
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CreateUserTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_user_can_be_created(){
        $profession = factory(Profession::class)->create();
        $skillA = factory(Skill::class)->create();
        $skillB = factory(Skill::class)->create();

        $this->browse(function(Browser $browser) use ($profession,$skillA,$skillB){
            $browser->visit(route('users.create'))
                ->type('first_name', 'Enrique')
                ->type('last_name', 'Aguilar')
                ->type('email', 'enriqueaguilar@expacioweb.com')
                ->type('password', '123456')
                ->type('bio', 'Developer')
                ->select('profession_id', $profession->id)
                ->type('twitter', 'https://twitter.com/sintwitter')
                ->check('skills['.$skillA->id.']')
                ->check('skills['.$skillB->id.']')
                ->radio('role', 'user')
                ->press('CREAR USUARIO')
                ->assertUrlIs(route('users'))
                ->assertSee('Enrique')
                ->assertSee('enriqueaguilar@expacioweb.com');
        });

        $this->assertCredentials([
            'first_name' => 'Enrique',
            'last_name' => 'Aguilar',
            'email' => 'enriqueaguilar@expacioweb.com',
            'password' => '123456',
            'role' => 'user'
        ]);

        $user = User::first();

        $this->assertDatabaseHas('user_profiles', [
            'bio' => 'Developer',
            'twitter' => 'https://twitter.com/sintwitter',
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
    }
}
