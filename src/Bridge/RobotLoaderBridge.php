<?php

namespace Grapesc\GrapeFluid\Plugins\Bridge;

use Grapesc\GrapeFluid\Plugins\Container;
use Nette\Loaders\RobotLoader;


/**
 * @author Mira Jakes <jakes@grapesc.cz>
 */
class RobotLoaderBridge
{

	/**
	 * @param Container $container
	 * @param RobotLoader $loader
	 * @return void
	 */
	public static function registerInRobotLoader(Container $container, RobotLoader $loader)
	{
		foreach ($container->getPlugins() AS $plugin) {
			if ($plugin->isEnable()) {
				$loader->addDirectory($plugin->getPluginDirectory());
			}
		}
	}
	
}