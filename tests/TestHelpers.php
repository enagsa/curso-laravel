<?php

namespace Tests;

trait TestHelpers
{
	protected function withData($custom = []){
        return array_filter(array_merge($this->defaultData(), $custom));
    }

    protected function defaultData(){
        return $this->defaultData;
    }
}