<?php

namespace Grapesc\GrapeFluid\Plugins;


/**
 * @author Mira Jakes <jakes@grapesc.cz>
 */
interface IPlugin
{

	/**
	 * @param string $directory
	 */
	public function __construct($directory);

	/**
	 * @return string
	 */
	public function getPluginDirectory();

	/**
	 * @return array
	 */
	public function getNeonConfigFilesPaths();

	/**
	 * @return string|null
	 */
	public function getCommandsDirectory();

	/**
	 * @return bool
	 */
	public function isEnable();

	/**
	 * @return string
	 */
	public function getName();

}