<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WelcomeUsersTest extends TestCase
{
    /** @test */
    function it_welcomes_users_with_nickname(){
        $this->get('/saludo/nombre/nick')
             ->assertStatus(200)
             ->assertSee('Bienvenido Nombre, tu apodo es nick');
    }

    /** @test */
    function it_welcomes_users_without_nickname(){
        $this->get('/saludo/Nombre')
             ->assertStatus(200)
             ->assertSee('Bienvenido Nombre');
    }
}
