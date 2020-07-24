<?php

namespace Tests;

use Illuminate\Support\Collection;
use Illuminate\Pagination\AbstractPaginator;
use PHPUnit\Framework\Assert as PHPUnit;

class TestCollectionData
{
	private $collection;

	public function __construct($collection){
		if(! $collection instanceof Collection && ! $collection instanceof AbstractPaginator)
			PHPUnit::fail('The data is not a collection');

		$this->collection = $collection;
	}

	public function contains($data){
		PHPUnit::assertTrue($this->collection->contains($data));

		return $this;
	}

	public function notContains($data){
		PHPUnit::assertFalse($this->collection->contains($data));

		return $this;
	}
}