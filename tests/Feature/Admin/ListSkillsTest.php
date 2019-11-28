<?php

namespace Tests\Feature\Admin;

use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListSkillsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_shows_the_professions_lists(){
        factory(Skill::class)->create(['title' => 'PHP']);
        factory(Skill::class)->create(['title' => 'HTML']);
        factory(Skill::class)->create(['title' => 'CSS']);

        $this->get(route('skill.index'))
            ->assertStatus(200)
            ->assertSeeInOrder([
                'CSS',
                'HTML',
                'PHP'
            ]);
    }
}
