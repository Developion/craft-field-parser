<?php

namespace Developion\FieldParser\FieldParsers;

use Craft;
use craft\base\ElementInterface;
use craft\base\FieldInterface;
use craft\errors\MissingAssetException;
use craft\fields\Assets as AssetsField;
use Developion\FieldParser\Base\FieldParser;
use Developion\FieldParser\Errors\InvalidParserException;
use Developion\FieldParser\Plugin;
use Illuminate\Support\Arr;

class Assets extends FieldParser
{
	public function parseValue(FieldInterface $field, array $value): ?array
	{
		if (!$field instanceof AssetsField) {
			throw new InvalidParserException(
				basename(get_class($field)) . ' can\'t be parsed as an Asset.',
			);
		}
		if (empty($values[$field->handle])) return null;

		$assets = [];
		$volumes = Craft::$app->getVolumes()->getAllVolumes();
		$assetIndexer = Craft::$app->getAssetIndexer();
		$indexingSession = $assetIndexer->startIndexingSession($volumes);

		foreach (Arr::wrap($values[$field->handle]) as $filename) {
			if (empty($filename)) continue;
			foreach ($volumes as $volume) {
				try {
					$assets[] = $assetIndexer->indexFile(
						$volume,
						basename($filename),
						$indexingSession->id,
					)->id;
				} catch (MissingAssetException $th) {
					Craft::warning("An asset with filename $filename hasn't been found in any of the available asset volumes.", Plugin::getInstance()->handle);
				} catch (\Throwable $th) {
					Craft::warning("Error", Plugin::getInstance()->handle);
				}
			}
		}
		$assetIndexer->stopIndexingSession($indexingSession);

		return $assets;
	}

	public function parseField(FieldInterface $field, ElementInterface $element)
	{
		return $element->{$field->handle}->all();
	}
}
