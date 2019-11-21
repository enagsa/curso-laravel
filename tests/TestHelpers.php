<?php

namespace Tests;

use Illuminate\Support\Str;

trait TestHelpers
{
	protected function withData($custom = []){
        return array_filter(array_merge($this->defaultData(), $custom));
    }

    protected function defaultData(){
        return $this->defaultData;
    }

    protected function assertDatabaseCount($table, $expected, $connection = null){
    	$total = $this->getConnection($connection)->table($table)->count();
    	$this->assertSame($expected, $total, sprintf(
    		"Failed asserting the table [%s] has %s %s. %s %s found.",
    		$table, 
    		$expected, 
    		Str::plural('row',$expected),
    		$total,
    		Str::plural('row',$total)
    	));
    }
}