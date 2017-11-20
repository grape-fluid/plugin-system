<?php

namespace Tests\Cases;

require __DIR__ . '/../bootstrap.php';

use Grapesc\GrapeFluid\Plugins\Container;
use Grapesc\GrapeFluid\Plugins\Plugin;
use Nette\Caching\Storages\DevNullStorage;
use Tester\TestCase;
use Tester\Assert;


class ContainerTest extends TestCase
{

	/** @var Container */
	private $container = [];


	public function setUp()
	{
		$this->container = new Container(new DevNullStorage());
	}


	public function testGetEmptyPlugins()
	{
		Assert::count(0, $this->container->getPlugins());
	}


	public function testAddPluginWithoutOwnPluginClass()
	{
		$this->container->addPluginDirectory(__DIR__ . '/../fixtures/plugins_one');
		$plugins = $this->container->getPlugins();

		Assert::count(1, $this->container->getPlugins());

		$plugin = current($plugins);

		Assert::same('grapesc.grapefluid.plugins.plugin.testpluginone', $plugin->getName());
		Assert::true($plugin->isEnable());
		Assert::type(Plugin::class, $plugin);
	}


	public function testAddPluginWithOwnPluginClass()
	{
		$this->container->addPluginDirectory(__DIR__ . '/../fixtures/plugins_two');
		$plugins = $this->container->getPlugins();

		Assert::count(1, $this->container->getPlugins());

		$plugin = current($plugins);

		Assert::same('tests.fixtures.plugin', $plugin->getName());
		Assert::true($plugin->isEnable());
		Assert::type(Plugin::class, $plugin);
		Assert::type(\Tests\Fixtures\Plugin::class, $plugin);
	}

}

(new ContainerTest)->run();