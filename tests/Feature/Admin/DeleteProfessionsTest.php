<?php

namespace Tests\Feature\Admin;

use App\Models\{UserProfile,Profession};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteProfessionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_deletes_a_profession(){
        $profession = factory(Profession::class)->create();

        $response = $this->delete(route('profession.delete', $profession));

        $response->assertRedirect();

        $this->assertDatabaseEmpty('professions');
    }

    /** @test */
    function a_profession_associated_to_a_profile_cannot_be_deleted(){
        $this->withExceptionHandling();
        $profession = factory(Profession::class)->create();
        $profile = factory(UserProfile::class)->create([
            'profession_id' => $profession->id
        ]);

        $response = $this->delete(route('profession.delete', $profession));

        $response->assertStatus(400);

        $this->assertDatabaseHas('professions', [
            'id' => $profession->id
        ]);
    }
}
