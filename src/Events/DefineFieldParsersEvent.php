<?php

namespace Developion\FieldParser\Events;

use Developion\FieldParser\Base\FieldParser;
use yii\base\Event;

class DefineFieldParsersEvent extends Event
{
	/** @var FieldParser[] */
	public array $fieldParsers = [];
}
