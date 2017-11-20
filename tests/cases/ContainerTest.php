<?php

namespace Tests\Cases;

require __DIR__ . '/../bootstrap.php';

use Grapesc\GrapeFluid\Plugins\Container;
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

}

(new ContainerTest)->run();