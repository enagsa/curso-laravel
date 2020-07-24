<?php

namespace Tests\Feature\Admin;

use App\Models\{User,Team};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function search_users_by_name(){
        $user1 = factory(User::class)->create([
            'name' => 'User 1'
        ]);

        $user2 = factory(User::class)->create([
            'name' => 'User 2'
        ]);

        $this->get(route('users', [
                'search' => 'User 1'
            ]))
            ->assertStatus(200)
            ->assertViewHas('users', function($users) use($user1, $user2){
                return $users->contains($user1) && !$users->contains($user2);
            });
    }

    /** @test */
    function show_results_with_a_partial_search_by_name(){
        $user1 = factory(User::class)->create([
            'name' => 'User 1'
        ]);

        $user2 =factory(User::class)->create([
            'name' => 'User 2'
        ]);

        $user3 = factory(User::class)->create([
            'name' => 'Not in search'
        ]);

        $this->get(route('users', [
                'search' => 'User'
            ]))
            ->assertStatus(200)
            ->assertSee('Listado de usuarios')
            ->assertViewHas('users', function($users) use($user1, $user2, $user3){
                return $users->contains($user1) && $users->contains($user2) && !$users->contains($user3);
            });
    }

    /** @test */
    function search_users_by_email(){
        $user1 = factory(User::class)->create([
            'email' => 'user1@example.com'
        ]);

        $user2 = factory(User::class)->create([
            'email' => 'user2@example.net'
        ]);

        $this->get(route('users', [
                'search' => 'user1@example.com'
            ]))
            ->assertStatus(200)
            ->assertSee('Listado de usuarios')
            ->assertViewHas('users', function($users) use($user1, $user2){
                return $users->contains($user1) && !$users->contains($user2);
            });
    }

    /** @test */
    function show_results_with_a_partial_search_by_email(){
        $user1 = factory(User::class)->create([
            'email' => 'user1@example.com'
        ]);

        $user2 = factory(User::class)->create([
            'email' => 'user2@example.com'
        ]);

        $user3 = factory(User::class)->create([
            'email' => 'user3@example.net'
        ]);

        $this->get(route('users', [
                'search' => '@example.com'
            ]))
            ->assertStatus(200)
            ->assertSee('Listado de usuarios')
            ->assertViewHas('users', function($users) use($user1, $user2, $user3){
                return $users->contains($user1) && $users->contains($user2) && !$users->contains($user3);
            });
    }

    /** @test */
    function search_users_by_team_name(){
        $user1 = factory(User::class)->create([
            'name' => 'User 1',
            'team_id' => factory(Team::class)->create(['name' => 'Smuggler'])->id
        ]);

        $user2 = factory(User::class)->create([
            'name' => 'User 2',
            'team_id' => null
        ]);

        $user3 = factory(User::class)->create([
            'name' => 'User 3',
            'team_id' => factory(Team::class)->create(['name' => 'Firefly'])->id
        ]);

        $response = $this->get(route('users', [
                'search' => 'Firefly'
            ]))
            ->assertStatus(200);

        $response->assertViewCollection('users')
            ->contains($user3)
            ->notContains($user1)
            ->notContains($user2);
    }

    /** @test */
    function show_results_with_a_partial_search_by_team_name(){
        $user1 = factory(User::class)->create([
            'name' => 'User 1',
            'team_id' => factory(Team::class)->create(['name' => 'Smuggler'])->id
        ]);

        $user2 = factory(User::class)->create([
            'name' => 'User 2',
            'team_id' => null
        ]);

        $user3 = factory(User::class)->create([
            'name' => 'User 3',
            'team_id' => factory(Team::class)->create(['name' => 'Firefly'])->id
        ]);

        $response = $this->get(route('users', [
                'search' => 'iref'
            ]))
            ->assertStatus(200);

        $response->assertViewCollection('users')
            ->contains($user3)
            ->notContains($user1)
            ->notContains($user2);
    }
}
