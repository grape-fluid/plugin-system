<?php

namespace Tests\Cases;

require __DIR__ . '/../bootstrap.php';

use Grapesc\GrapeFluid\Plugins\Plugin;
use Tester\TestCase;
use Tester\Assert;


class PluginTest extends TestCase
{

	/**
	 * @dataProvider getPluginDirectory
	 */
	public function testCreatePlugin($directory)
	{
		$plugin = new Plugin($directory);

		Assert::same(realpath($directory), $plugin->getPluginDirectory());
		Assert::same(realpath($directory) . DIRECTORY_SEPARATOR . "commands", $plugin->getCommandsDirectory());

		Assert::count(1, $plugin->getNeonConfigFilesPaths());

		foreach ($plugin->getNeonConfigFilesPaths() AS $file) {
			Assert::same($file->getRealPath(), realpath($directory) . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "config.neon");
		}

	}


	public function getPluginDirectory()
	{
		return [
			[__DIR__ . '/../fixtures/plugins_one/TestPluginOne'],
			[__DIR__ . '/../fixtures/plugins_two/TestPluginTwo']
		];
	}

}

(new PluginTest)->run();