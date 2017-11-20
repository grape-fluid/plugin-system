<?php

namespace Grapesc\GrapeFluid\Plugins;

use Nette\Utils\Finder;
use Nette\Utils\Strings;


/**
 * @author Mira Jakes <jakes@grapesc.cz>
 */
class Plugin implements IPlugin
{

	/** @var string */
	private $directory;

	/** @var string */
	protected $configDirectory = 'config';

	/** @var string */
	protected $commandsDirectory = 'commands';

	/** @var bool */
	protected $enable = true;


	/**
	 * Plugin constructor.
	 * @param string $directory
	 */
	public function __construct($directory)
	{
		$this->directory = $directory;
	}


	/**
	 * @return string
	 */
	public function getPluginDirectory()
	{
		return realpath($this->directory);
	}


	/**
	 * @return array
	 */
	public function getNeonConfigFilesPaths()
	{
		$files = [];

		if (is_dir($this->getPluginDirectory() . DIRECTORY_SEPARATOR . $this->configDirectory)) {
			foreach (Finder::findFiles('*.neon')->from($this->directory . DIRECTORY_SEPARATOR . $this->configDirectory) AS $file) {
				$files[] = $file;
			}
		}

		return $files;
	}


	/**
	 * @return bool|string
	 */
	public function getCommandsDirectory()
	{
		return $this->getPluginDirectory() . DIRECTORY_SEPARATOR . $this->commandsDirectory;
	}


	/**
	 * @return bool
	 */
	public function isEnable()
	{
		return $this->enable;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return str_replace('-', '.', Strings::webalize(get_called_class()));
	}

}