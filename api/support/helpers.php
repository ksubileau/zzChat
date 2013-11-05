<?php
/**
 * Utility functions.
 * Some of these functions are taken from or inspired
 * by the Laravel Framework or the Wordpress CMS.
 */


if ( ! function_exists('set_debug_mode'))
{
    /**
     * Sets PHP error handling and handles ZZChat debug mode.
     *
     * Uses three constants: ZZ_DEBUG, ZZ_DEBUG_DISPLAY, and ZZ_DEBUG_LOG. All three can be
     * defined in config.php. Example: <code> define( 'ZZ_DEBUG', true ); </code>
     *
     * ZZ_DEBUG_DISPLAY and ZZ_DEBUG_LOG perform no function unless ZZ_DEBUG is true.
     * ZZ_DEBUG defaults to false.
     *
     * When ZZ_DEBUG is true, all PHP notices are reported.
     *
     * When ZZ_DEBUG_DISPLAY is true, ZZChat will force errors to be displayed.
     * ZZ_DEBUG_DISPLAY defaults to true. Defining it as null prevents ZZChat from
     * changing the global configuration setting. Defining ZZ_DEBUG_DISPLAY as false
     * will force errors to be hidden.
     *
     * When ZZ_DEBUG_LOG is true, errors will be logged to storage/debug.log.
     * ZZ_DEBUG_LOG defaults to false.
     *
     */
    function set_debug_mode() {
        if ( ZC_DEBUG ) {

            error_reporting( E_ALL & ~E_DEPRECATED & ~E_STRICT );

            if ( ZC_DEBUG_DISPLAY ) {
                ini_set( 'display_errors', 1 );
            }
            elseif ( ZC_DEBUG_DISPLAY !== null) {
                ini_set( 'display_errors', 0 );
            }

            if ( ZC_DEBUG_LOG ) {
                ini_set( 'log_errors', 1 );
                ini_set( 'error_log', ZC_STORAGE_DIR . '/debug.log' );
            }
        } else {
            error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );
        }
    }
}

if ( ! function_exists('set_constant'))
{
    /**
     * Set a constant value if not already defined.
     *
     * @param   string  $key
     * @param   string  $value
     * @return  bool False if the constant was already defined previously.
     */
    function set_constant($key, $value)
    {
        if(!defined($key)) {
            define($key, $value);
            return true;
        }
        else {
            return false;
        }
    }
}

if ( ! function_exists('generate_token'))
{
    /**
     * Generate authentication token.
     *
     * @return string
     */
    function generate_token($lenght = 64)
    {
        if(function_exists('openssl_random_pseudo_bytes')) {
            return bin2hex(openssl_random_pseudo_bytes($lenght/2));
        }

        // Fallback to mt_rand(), very less secure !
        return substr(hash('sha256', uniqid(mt_rand(), true)), 0, $lenght);
    }
}

if ( ! function_exists('array_add'))
{
    /**
     * Add an element to an array if it doesn't exist.
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $value
     * @return array
     */
    function array_add($array, $key, $value)
    {
        if ( ! isset($array[$key])) $array[$key] = $value;

        return $array;
    }
}

if ( ! function_exists('array_divide'))
{
    /**
     * Divide an array into two arrays. One with keys and the other with values.
     *
     * @param  array  $array
     * @return array
     */
    function array_divide($array)
    {
        return array(array_keys($array), array_values($array));
    }
}

if ( ! function_exists('array_first'))
{
    /**
     * Return the first element in an array passing a given truth test.
     *
     * @param  array    $array
     * @param  Closure  $callback
     * @param  mixed    $default
     * @return mixed
     */
    function array_first($array, $callback, $default = null)
    {
        foreach ($array as $key => $value)
        {
            if (call_user_func($callback, $key, $value)) return $value;
        }

        return value($default);
    }
}

if ( ! function_exists('dd'))
{
    /**
     * Dump the passed variables and end the script.
     *
     * @param  dynamic  mixed
     * @return void
     */
    function dd()
    {
        array_map(function($x) { var_dump($x); }, func_get_args()); die;
    }
}