<?php

namespace Developion\FieldParser;

use Craft;
use Developion\FieldParser\Models\Settings;
use Developion\FieldParser\Services\FieldParsers;
use craft\base\Model;
use craft\base\Plugin as BasePlugin;
use craft\elements\Entry;
use Developion\FieldParser\Events\DefineFieldParsersEvent;
use Developion\FieldParser\Traits\Services;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use yii\base\Event;

/**
 * Field Parser plugin
 *
 * @method static Plugin getInstance()
 * @method Settings getSettings()
 * @author Developion <marko.gajic@developion.com>
 * @copyright Developion
 * @license MIT
 * @property-read FieldParsers $parsers
 */
class Plugin extends BasePlugin
{
	use Services;

	public string $schemaVersion = '1.0.0';
	public bool $hasCpSettings = true;

	public static function config(): array
	{
		return [
			'components' => [
				'fieldParsers' => FieldParsers::class,
			],
		];
	}

	public function init()
	{
		parent::init();

		// Defer most setup tasks until Craft is fully initialized
		Craft::$app->onInit(function () {
			if (!Craft::$app->getRequest()->getIsConsoleRequest()) {
				/** @var HtmlDumper $dumper */
				$dumper = Craft::$app->getDumper();
				$dumper->setTheme('dark');
			}
			$this->attachEventHandlers();
			$this->getFieldParsers();
		});
	}

	protected function createSettingsModel(): ?Model
	{
		return Craft::createObject(Settings::class);
	}

	protected function settingsHtml(): ?string
	{
		return Craft::$app->view->renderTemplate('field-parser/_settings.twig', [
			'plugin' => $this,
			'settings' => $this->getSettings(),
		]);
	}

	private function attachEventHandlers(): void
	{
		Event::on(
			FieldParsers::class,
			FieldParsers::DEFINE_FIELD_PARSERS,
			function(DefineFieldParsersEvent $event) {
				$event->fieldParsers['pera'] = 'Mika';
			}
		);
	}
}
