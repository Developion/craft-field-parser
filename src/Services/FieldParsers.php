<?php

namespace Developion\FieldParser\Services;

use Craft;
use craft\base\ElementInterface;
use craft\base\FieldInterface;
use craft\fields\{
	Assets as AssetsField,
	BaseOptionsField,
	BaseRelationField,
	PlainText as PlainTextField,
};
use craft\helpers\StringHelper;
use craft\models\FieldLayout;
use Developion\FieldParser\FieldParsers\{
	Assets as AssetsParser,
	SimpleField as SimpleFieldParser,
};
use Developion\FieldParser\Base\FieldParserInterface;
use Developion\FieldParser\Events\DefineFieldParsersEvent;
use yii\base\Component;
use yii\di\ServiceLocator;

/**
 * FieldParsers service
 */
final class FieldParsers extends ServiceLocator
{
	const DEFINE_FIELD_PARSERS = 'defineFieldParsersEvent';

	/** @var FieldParserInterface[] */
	private array $_defaultParsers = [
		AssetsField::class => AssetsParser::class,
		// Categories::class => '',
		// Checkboxes::class => '',
		// Color::class => '',
		// Date::class => '',
		// Dropdown::class => '',
		// Email::class => '',
		// Entries::class => '',
		// Lightswitch::class => '',
		// Matrix::class => '',
		// MissingField::class => '',
		// Money::class => '',
		// MultiSelect::class => '',
		// Number::class => '',
		PlainTextField::class => SimpleFieldParser::class,
		// RadioButtons::class => '',
		// Table::class => '',
		// Tags::class => '',
		// Time::class => '',
		// Url::class => '',
		// Users::class => '',
	];

	public function init(): void
	{
		$this->defineFieldParsers();
		$this->initiateFieldParsers();
		// dd($this->_defaultParsers);
		// dd($this->get($this->getComponentFromFieldFQCN(AssetsField::class)));
	}

	private function defineFieldParsers(): void
	{
		$fieldParsers = $this->_defaultParsers;
		$event = new DefineFieldParsersEvent([
			'fieldParsers' => $fieldParsers,
		]);

		$this->trigger(
			self::DEFINE_FIELD_PARSERS,
			$event,
		);

		$this->_defaultParsers = $event->fieldParsers;
	}

	private function initiateFieldParsers(): void
	{
		$components = [];
		foreach ($this->_defaultParsers as $fieldClass => $fieldParser) {
			$components[$this->getComponentFromFieldFQCN($fieldClass)] = $fieldParser;
		}
		$this->setComponents($components);
	}

	private function getComponentFromFieldFQCN(string $fqcn): string
	{
		return StringHelper::camelCase(StringHelper::replaceAll($fqcn, ['\\'], '-'));
	}

	private function getParserComponent(string $className): FieldParserInterface
	{
		return $this->get($this->getComponentFromFieldFQCN($className));
	}

	public function parseElementFields(ElementInterface $element): array
	{
		$elementCustomFields = $element->getFieldLayout()->getCustomFields();
		$fieldParsers = $this->getAllFieldParsers();
		$parsedValues = [];

		foreach ($elementCustomFields as $customField) {
			// $parsedValues[$customField->handle] = $fieldParsers[get_class($customField)]
		}

		return $parsedValues;
	}

	public function parseValue(FieldInterface $field, $value): mixed
	{
	}

	public function parseField(FieldInterface $field, ElementInterface $element): mixed
	{
		// if ($field::class === AssetsField::class) {
		// 	dd(
		// 		$this->_defaultParsers[$field::class],
		// 	);
		// }
		if (isset($this->_defaultParsers[$field::class])) {
			return $this->getParserComponent($field::class)
				->parseField($field, $element);
		}
		$value = $element->{$field->handle};
		// if ($field instanceof BaseRelationField) {
		// 	return $value->{$field->maxRelations === 1 ? 'one' : 'all'}();
		// }

		if ($field instanceof BaseOptionsField) {
			return $field->normalizeValue($element->{$field->handle})->value;
		}

		return $value;
	}
}
