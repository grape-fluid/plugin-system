<?php

namespace Grapesc\GrapeFluid\Plugins\Tracy;

use Grapesc\GrapeFluid\Plugins\Container;
use Tracy;


/**
 * @author Mira Jakes <jakes@grapesc.cz>
 */
class PluginsBarPanel implements Tracy\IBarPanel
{

	/** @var Container */
	protected $container;


	/**
	 * PluginsBarPanel constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
	}


	/**
	 * @return string
	 */
	public function getTab()
	{
		return 	"<span title='Plugins'>" .
				"<img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH4QgdBjQpftqxLQAAAB1pVFh0Q29tbWVudAAAAAAAQ3JlYXRlZCB3aXRoIEdJTVBkLmUHAAACcUlEQVQ4y4WTTWxMURTHf+fcN9M3VWYwrUp8bTCNlIhYSCRWgmBj0liyIVYiERtJxUgsrSQSrOx878SiiUSQEBKfIbEgSHx1qkxLX+e9e49Fa7S1cHb3f8/5557fPUeYEtVqVVWju0DFzMpXr14O/CdkptDXt/srUL5y5ZICjF2Y3+3IuvN7fjxJzufbxaxXgn+fPxA+tQyq1b4uVf1iZrdFZNOk16skcz3XqreQ0KTOt6iU5ksaqIv5en6/7wRQAFVdCiAiG6Y8pmdp6SfqHF7a6LQ5URS1F1CHiXsH8Oss0mqht3eNVCqVEvDtj2YGKlAsZJzbdocs9SBCaI4kEmzUgjf9k/z8+VMDmoYQJtGIQBaUzyMxJg7TCBOHuLbYVMuirhRNglsNPFEsOdW4jphxuLiLUrvn9I6XZN6TeftLXnPP8nsba7LzsVMAg6FxiSTFFXLmabOMFMeb0Vkowst0HrmoQOI6uDu+CDSaCxDtS3yLwdstsQ3pLBb7YZwFPkYlOrNRKusP8SOaS33VRQbTHD2vd4GkUKtJ6xcAZlvyZlk2hLOJ2VnZ/M6CLKPLxkA8Yo6GxUAKIUGOHukAkPpm3olSxCja3zbp2tiPI5CKQ8ywkAMC1I5PG74IeGGBbmAF0DGpJz2/vsSpRnzIFxlzOdB0AmCtf7mF0MC5IY6dyLQ8wPbyAOsEuQdk8w8u0fIAhfuPzmWPH5xhWTI8bdQNXqP6WYNfO42BYZsAkZ3vrb7VybiiXvDDUaGBMThRSxMYBMbC8ZMP/9msZJtrGX7aHs+edlnr/0qt/8HMGp16iG/61vouvJGMzMh1QDzT4DdLO/sWlGDUuAAAAABJRU5ErkJggg==\" />" .
				" Plugins (" . count($this->container->getPlugins()) . ")" .
				"</span>";
	}


	/**
	 * @return string
	 */
	function getPanel()
	{
		$enabled   = 0;
		$disabled  = 0;
		$innerHtml = "";

		foreach ($this->container->getPlugins() AS $plugin) {
			if ($plugin->isEnable()) {
				$enabled++;
			} else {
				$disabled++;
			}

			$innerHtml.= "<tr><td>{$plugin->getName()}</td><td>{$plugin->getPluginDirectory()}</td><td>";
			if ($neons = $plugin->getNeonConfigFilesPaths()) {
				$innerHtml.= "<h2 class='tracy-toggle tracy-collapsed'>view (" . count($neons) . ")</h2>";
				$innerHtml.= "<div class='tracy-collapsed'>";
				$innerHtml.= Tracy\Debugger::dump($neons, true);
				$innerHtml.= "</div>";
			} else {
				$innerHtml.= '-';
			}
			$innerHtml.= "</td><td>" . ($plugin->isEnable() ? 'Yes' : 'No') . "</td></tr>";
		}

		$html = "<h1>Enabled plugins: $enabled, Disabled Plugins: $disabled</h1>";
		$html.= "<div class='tracy-inner'>";

		$html.= "<table>";
		$html.= "<tr><th>Name</th><th>Directory</th><th>Config Files</th><th>Enabled</th></tr>";

		$html.= $innerHtml;

		$html.= "<table>";
		$html.= "</div>";
		return $html;
	}

}