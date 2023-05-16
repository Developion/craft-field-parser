<?php

namespace Developion\FieldParser\Traits;

use Developion\FieldParser\Services\FieldParsers;

trait Services
{
	public function getFieldParsers(): FieldParsers
	{
		return $this->get('fieldParsers');
	}
}
