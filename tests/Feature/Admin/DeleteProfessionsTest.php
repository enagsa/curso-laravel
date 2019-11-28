<?php

namespace Tests\Feature\Admin;

use App\Models\Profession;
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
}
