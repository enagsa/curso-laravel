<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_deletes_a_user(){
        $user = factory(User::class)->create();

        $this->delete(route('users.destroy', $user))
            ->assertRedirect(route('users'));

        $this->assertSame(0, User::count());
    }
}
