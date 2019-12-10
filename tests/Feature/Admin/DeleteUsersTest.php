<?php

namespace Tests\Feature\Admin;

use App\Models\{User,UserProfile,Skill};
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_sends_a_user_to_the_trash(){
        $user = factory(User::class)->create();
    	$user->profile()->save(factory(UserProfile::class)->make());
    	$skill1 = factory(Skill::class)->create();
        $skill2 = factory(Skill::class)->create();
        $user->skills()->attach([$skill1->id,$skill2->id]);

        $this->patch(route('users.trash', $user))
            ->assertRedirect(route('users'));

        $this->assertSoftDeleted('users', [
        	'id' => $user->id
        ]);

        $this->assertSoftDeleted('user_profiles',[
        	'user_id' => $user->id
        ]);

        $this->assertDatabaseCount('user_skill', 2);

        $user->refresh();
        $this->assertTrue($user->trashed());
    }

    /** @test */
    function it_completely_deletes_a_user(){
        $user = factory(User::class)->create([
        	'deleted_at' => now()
        ]);
    	
    	factory(UserProfile::class)->create([
    		'user_id' => $user->id
    	]);

    	$skill1 = factory(Skill::class)->create();
        $skill2 = factory(Skill::class)->create();
        $user->skills()->attach([$skill1->id,$skill2->id]);

        $this->delete(route('users.destroy', $user->id))
            ->assertRedirect(route('users.trashed'));

        $this->assertDatabaseEmpty('users');
        $this->assertDatabaseEmpty('user_profiles');
        $this->assertDatabaseEmpty('user_skill');
    }

    /** @test */
    function it_cannot_deletes_a_user_that_is_not_in_the_trash(){
    	$this->withExceptionHandling();

        $user = factory(User::class)->create([
        	'deleted_at' => null
        ]);
    	
    	factory(UserProfile::class)->create([
    		'user_id' => $user->id
    	]);

    	$skill1 = factory(Skill::class)->create();
        $skill2 = factory(Skill::class)->create();
        $user->skills()->attach([$skill1->id,$skill2->id]);

        $this->delete(route('users.destroy', $user->id))
            ->assertStatus(404);

        $this->assertDatabaseHas('users',[
        	'id' => $user->id,
        	'deleted_at' => null
        ]);

        $this->assertDatabaseHas('user_profiles',[
        	'user_id' => $user->id,
        	'deleted_at' => null
        ]);

        $this->assertDatabaseCount('user_skill', 2);
    }

    /** @test */
    function it_restores_a_user_in_the_trash(){
    	$user = factory(User::class)->create([
        	'deleted_at' => now()
        ]);
    	
    	factory(UserProfile::class)->create([
    		'user_id' => $user->id,
    		'deleted_at' => now()
    	]);

    	$skill1 = factory(Skill::class)->create();
        $skill2 = factory(Skill::class)->create();
        $user->skills()->attach([$skill1->id,$skill2->id]);

        $this->patch(route('users.restore', $user->id))
            ->assertRedirect(route('users'));

           $this->assertDatabaseHas('users',[
        	'id' => $user->id,
        	'deleted_at' => null
        ]);

        $this->assertDatabaseHas('user_profiles',[
        	'user_id' => $user->id,
        	'deleted_at' => null
        ]);

        $this->assertDatabaseCount('user_skill', 2);
    }
}
