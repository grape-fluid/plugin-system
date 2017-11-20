<?php

namespace Grapesc\GrapeFluid\Plugins\Bridge;

use Grapesc\GrapeFluid\Plugins\Container;
use Nette\Configurator;


/**
 * @author Mira Jakes <jakes@grapesc.cz>
 */
class ConfiguratorBridge
{

	/**
	 * @param Container $container
	 * @param Configurator $configurator
	 * @return void
	 */
	public static function loadConfigurations(Container $container, Configurator $configurator)
	{
		foreach ($container->getPlugins() AS $plugin) {
			if ($plugin->isEnable()) {
				foreach ($plugin->getNeonConfigFilesPaths() AS $file) {
					$configurator->addConfig($file->getPathname());
				}
			}
		}
	}

}