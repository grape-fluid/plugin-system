<?php

namespace Grapesc\GrapeFluid\Plugins\Helper;

/**
 * @author Mira Jakes <jakes@grapesc.cz>
 */
class Classes
{

	/**
	 * @internal
	 * @param string $filePath
	 * @return array
	 */
	public static function getClassesNameFromFile($filePath)
	{
		$tokens  = @token_get_all(file_get_contents($filePath));
		if (!is_array($tokens)) {
			return [];
		}

		$namespace = "";
		$classes   = [];

		for ($i = 0; $i < count($tokens); $i++) {
			if($tokens[$i][0] == T_NAMESPACE AND $tokens[$i + 1][0] == T_WHITESPACE) {
				$x = 2;
				while(in_array($tokens[$i + $x][0], [T_NS_SEPARATOR, T_STRING, T_NAME_QUALIFIED])) {
					$namespace.= $tokens[$i + $x][1];
					$x++;
				}
			}

			if ($tokens[$i][0] == T_CLASS && $tokens[$i + 1][0] == T_WHITESPACE && $tokens[$i + 2][0] == T_STRING) {
				$class_name = $tokens[$i + 2][1];
				$classes[] = $namespace . "\\" . $class_name;
			}
		}

		return $classes;
	}

}