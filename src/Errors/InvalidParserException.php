<?php

namespace Developion\FieldParser\Errors;

class InvalidParserException extends ParserException
{
	public function getName(): string
	{
		return 'Invalid parser used for the field type.';
	}
}
