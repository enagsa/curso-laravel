<?php

namespace Tests\Feature\Admin;

use App\Models\Profession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListProfessionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_shows_the_professions_lists(){
        factory(Profession::class)->create(['title' => 'Diseñador']);
        factory(Profession::class)->create(['title' => 'Programador']);
        factory(Profession::class)->create(['title' => 'Administrador']);

        $this->get(route('profession.index'))
            ->assertStatus(200)
            ->assertSeeInOrder([
                'Administrador',
                'Diseñador',
                'Programador'
            ]);
    }
}
