<?php

namespace Grapesc\GrapeFluid\Plugins\Bridge;

use Grapesc\GrapeFluid\Plugins\Container;
use Grapesc\GrapeFluid\Plugins\Tracy\PluginsBarPanel;
use Tracy\Debugger;


/**
 * @author Mira Jakes <jakes@grapesc.cz>
 */
class TracyBridge
{

	/**
	 * @param Container $container
	 * @return void
	 */
	public static function registerPanelBar(Container $container)
	{
		Debugger::getBar()->addPanel(new PluginsBarPanel($container));
	}
	
}