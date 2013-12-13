<?php
namespace ZZChat\Support;

/**
 * Utility functions.
 * Some of these functions are taken from or inspired
 * by the Laravel Framework or the Wordpress CMS.
 *
 * @package    ZZChat
 * @author     Kévin Subileau
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU GPLv3 (also in /LICENSE)
 * @link       https://github.com/ksubileau/zzChat
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

if ( !function_exists( __NAMESPACE__.'\hex2bin' ) ) {
    /**
     * Hex2bin PHP < 5.4 fallback.
     *
     * @return string
     * @see http://www.php.net/manual/function.hex2bin.php
     */
    function hex2bin( $str ) {
        if (function_exists('\hex2bin')) {
            // Use PHP function if available (PHP > 5.4).
            return \hex2bin($str);
        }

        $sbin = "";
        $len = strlen( $str );
        for ( $i = 0; $i < $len; $i += 2 ) {
            $sbin .= pack( "H*", substr( $str, $i, 2 ) );
        }

        return $sbin;
    }
}

if (!function_exists(__NAMESPACE__.'\json_last_error_msg')) {
    /**
     * json_last_error_msg PHP < 5.5 fallback.
     *
     * @return string
     * @see http://www.php.net/manual/function.json-last-error-msg.php
     */
    function json_last_error_msg()
    {
        if (function_exists('\json_last_error_msg')) {
            // Use PHP function if available (PHP > 5.5).
            return \json_last_error_msg();
        }
        switch (json_last_error()) {
            case JSON_ERROR_DEPTH:
                $error = 'The maximum stack depth has been exceeded';
            break;
            case JSON_ERROR_STATE_MISMATCH:
                $error = 'Invalid or malformed JSON';
            break;
            case JSON_ERROR_CTRL_CHAR:
                $error = 'Control character error, possibly incorrectly encoded';
            break;
            case JSON_ERROR_SYNTAX:
                $error = 'Syntax error, malformed JSON';
            break;
            case JSON_ERROR_UTF8:
                $error = 'Malformed UTF-8 characters, possibly incorrectly encoded';
            break;
            default:
                return;
        }
        return $error;
    }
}

if ( ! function_exists('rrmdir'))
{
    /**
     * Recursively delete a directory that is not empty.
     *
     * @param  string   $dir
     * @return boolean
     */
    function rrmdir($dir) {
        // Prevent deleting all files in host directory :D
        if (empty($dir) || !is_dir($dir)) {
            return false;
        }

        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            $res = false;
            if (is_dir("$dir/$file"))
                $res = rrmdir("$dir/$file");
            else
                $res = unlink("$dir/$file");
            if(!$res)
                return false;
        }
        return rmdir($dir);
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