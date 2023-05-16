<?php

namespace Developion\FieldParser\Base;

use craft\base\ElementInterface;
use craft\base\FieldInterface;

interface FieldParserInterface
{
	/**
	 * Returns a scalar value for the field in an appropriate form for the given field type.
	 * @param FieldInterface $field
	 * @param array $values An array of values within which there should be a key matching the `$field->handle`
	 * @return mixed
	 */
	public function parseValue(FieldInterface $field, array $values);

	/**
	 * Returns the `API-usable` values from the given element's field.
	 * @param FieldInterface $field
	 * @param ElementInterface $element
	 * @return mixed
	 */
	public function parseField(FieldInterface $field, ElementInterface $element);
}
