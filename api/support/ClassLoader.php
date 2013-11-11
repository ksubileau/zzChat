<?php
namespace ZZChat\Support;

/**
 * ZZChat PSR-0 auto loader.
 *
 * @package    ZZChat
 * @author     Kévin Subileau
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU GPLv3 (also in /LICENSE)
 * @link       https://github.com/ksubileau/zzChat
 */
class ClassLoader
{

	/**
	 * The registered directories.
	 *
	 * @var array
	 */
	protected static $directories = array();

	/**
	 * Indicates if a ClassLoader has been registered.
	 *
	 * @var bool
	 */
	protected static $registered = false;

    /**
	 * Get the normal file name for a class.
	 *
	 * @param string $class Class name
	 * @return string Class file name
	 * @throws Exception If class name is invalid
	 */
    protected static function getClassFileName($class)
    {
        if (!preg_match('/^[A-Za-z0-9_\\\\]+$/D', $class)) {
            throw new Exception("Invalid class name \"$class\".");
        }

        // prefixed class
        $class = str_replace('_', '/', $class);

        // namespace
        $class = str_replace('\\', '/', $class);

        $class = self::removePrefix($class, 'ZZChat/');
        return $class;
    }

    protected static function removePrefix($class, $prefixToRemove)
    {
        if (strpos($class, $prefixToRemove) === 0) {
            return substr($class, strlen($prefixToRemove));
        }

        return $class;
    }

    private static function usesZZChatNamespace($class)
    {
        return strpos($class, 'ZZChat\\') === 0 || strpos($class, '\ZZChat\\') === 0;
    }

   /**
	 * Load the given class file.
	 *
	 * @param  string  $class
	 * @return void
	 */
	public static function autoload($class)
    {
        $classPath = static::getClassFileName($class);

        if (static::usesZZChatNamespace($class)) {
            static::tryToLoadClass($class, '', $classPath);
        } else {
            // non-ZZChat classes (e.g., Slim) are in vendor/
            if (! static::tryToLoadClass($class, '/vendor/', $classPath)) {
            	// Try also others directories.
				foreach (static::$directories as $directory)
				{
					if (static::tryToLoadClass($class, $directory.DIRECTORY_SEPARATOR, $classPath, ''))
						return true;
				}
            }
        }
    }

    private static function tryToLoadClass($class, $dir, $classPath, $prefix = ABSPATH)
    {
    	if(strlen($prefix) > 0 && substr($prefix, -1) != '/') {
		    $prefix .= '/';
		}
        $path = $prefix . $dir . $classPath . '.php';

        if (file_exists($path)) {
            require_once $path;

            return class_exists($class, false) || interface_exists($class, false);
        }

        return false;
    }

	/**
	 * Register the given class loader on the auto-loader stack.
	 *
	 * @return void
	 */
	public static function register()
	{
		if ( ! static::$registered)
		{
			spl_autoload_register(__CLASS__."::autoload");

			// preserve any existing __autoload
			if (function_exists('__autoload')) {
			    spl_autoload_register('__autoload');
			}

			static::$registered = true;
		}
	}

	/**
	 * Add directories to the class loader search scope.
	 *
	 * @param  string|array  $directories
	 * @return void
	 */
	public static function addDirectories($directories)
	{
		static::$directories = array_merge(static::$directories, (array) $directories);

		static::$directories = array_unique(static::$directories);
	}

	/**
	 * Remove directories from the class loader.
	 *
	 * @param  string|array  $directories
	 * @return void
	 */
	public static function removeDirectories($directories = null)
	{
		if (is_null($directories))
		{
			static::$directories = array();
		}
		else
		{
			$directories = (array) $directories;

			static::$directories = array_filter(static::$directories, function($directory) use ($directories)
			{
				return ( ! in_array($directory, $directories));
			});
		}
	}

	/**
	 * Gets all the directories registered with the loader.
	 *
	 * @return array
	 */
	public static function getDirectories()
	{
		return static::$directories;
	}

}
