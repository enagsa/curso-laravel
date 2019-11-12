<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, TestHelpers;

    protected $defaultData = [];

    public function setUp():void{
    	parent::setUp();
    	$this->withoutExceptionHandling();
    }
}
