<?php

namespace Grapesc\GrapeFluid\Plugins;

use Grapesc\GrapeFluid\BaseParametersRepository;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Nette\Utils\Finder;


/**
 * @author Mira Jakes <jakes@grapesc.cz>
 */
class Container
{

	/** @var Plugin[] $plugins */
	private $plugins = [];

	/** @var Cache $cache */
	private $cache;

	/** @var bool */
	private $enable = true;

	/** @var array */
	private $directories = [];

	/** @var bool */
	private $isBuilt;

	/** @var IStorage|null */
	private $cacheStorage = null;

	/** @var bool */
	private $debug = false;


	/**
	 * Container constructor.
	 * @param IStorage|null $cacheStorage
	 */
	public function __construct(IStorage $cacheStorage = null)
	{
		$this->cacheStorage = $cacheStorage;
	}


	/**
	 * @param bool $enable
	 * @return void
	 */
	public function enable($enable = true)
	{
		$this->enable = $enable;
	}


	/**
	 * @param array $directories
	 * @return void
	 */
	public function addPluginsDirectories(array $directories)
	{
		foreach ($directories AS $directory) {
			$this->addPluginDirectory($directory);
		}
	}

	
	/**
	 * @param string $directory
	 * @return void
	 */
	public function addPluginDirectory($directory)
	{
		if (is_dir($directory)) {
			$this->directories[] = $directory;
		}
	}


	/**
	 * @return Plugin[]
	 */
	public function getPlugins()
	{
		$this->build();
		return $this->plugins;
	}


	/**
	 * @param bool $enable
	 * @return void
	 */
	public function enableDebug($enable = true)
	{
		$this->debug = (bool) $enable;
	}


	/**
	 * @return void
	 */
	private function build()
	{
		if (!$this->isBuilt AND $this->enable) {
			$pluginsDirectories = $this->getCache()->load('plugins.directories', function(&$dependencies) {
				$dirs = $this->getPluginsDirectories();
				if ($this->debug) {
					$dependencies[Cache::FILES] = $this->directories;
				}
				return $dirs;
			});

			$plugins = $this->getCache()->load('plugins.items.' . md5(serialize($pluginsDirectories)), function(&$dependencies) use ($pluginsDirectories) {
				$output = [];
				$dependencies[Cache::ITEMS] = ['plugins.directories'];

				foreach ($pluginsDirectories AS $dir) {
					if (is_file($dir . DIRECTORY_SEPARATOR . 'Plugin.php')) {
						$classesInFile = Helper\Classes::getClassesNameFromFile($dir . DIRECTORY_SEPARATOR . 'Plugin.php');
						if (count($classesInFile) === 1) {
							require $dir . DIRECTORY_SEPARATOR . 'Plugin.php';
							$refl = new \ReflectionClass($classesInFile[0]);
							if ($refl->implementsInterface(IPlugin::class)) {
								$output[$dir] = $refl->getName();
								continue;
							}
						}
					}

					$output[$dir] = Plugin::class;
				}
				return $output;
			});

			foreach ($plugins AS $directory => $class) {
				if (!class_exists($class)) {
					require $directory . DIRECTORY_SEPARATOR . 'Plugin.php';
				}
				$this->plugins[] = new $class($directory);
			}

			$this->isBuilt = true;
		}
	}


	/**
	 * @return array
	 */
	private function getPluginsDirectories()
	{
		$folders = [];
		foreach ($this->directories AS $folder) {
			foreach (Finder::findDirectories()->from($folder)->limitDepth(0) AS $dir) {
				$folders[] = $dir->getPathname();
			}
		}

		return $folders;
	}


	/**
	 * @return Cache
	 */
	private function getCache()
	{
		if (!$this->cache) {
			$this->cache = new Cache($this->cacheStorage, "Fluid.Plugins");
		}

		return $this->cache;
	}

}