<?php

namespace Developion\FieldParser\Errors;

use yii\base\Exception;

class ParserException extends Exception
{
	public function getName(): string
	{
		return 'Field Parser Error.';
	}
}
