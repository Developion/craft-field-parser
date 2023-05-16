<?php

namespace Developion\FieldParser\Base;

use craft\base\Component;
use craft\base\ElementInterface;
use craft\base\FieldInterface;

class FieldParser extends Component implements FieldParserInterface
{
	public FieldInterface $field;

	public function parseValue(FieldInterface $field, array $values)
	{
		return $values[$field->handle];
	}

	public function parseField(FieldInterface $field, ElementInterface $element)
	{
		return $element->{$field->handle};
	}
}
