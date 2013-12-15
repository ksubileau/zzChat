<?php
namespace ZZChat\Support;

/**
 * Defines constants and global variables that can be overridden, generally in config.php.
 *
 * @package    ZZChat
 * @author     Kévin Subileau
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU GPLv3 (also in /LICENSE)
 * @link       https://github.com/ksubileau/zzChat
 */

if ( ! function_exists('set_initial_constants'))
{
    /**
     * Defines initial ZZChat constants
     */
    function set_initial_constants( ) {

        /************************************
         * Debug constants
         ************************************/
        set_constant( 'ZC_DEBUG', false );
        set_constant( 'ZC_DEBUG_DISPLAY', true);
        set_constant( 'ZC_DEBUG_LOG', false);

        /************************************
         * Path constants
         ************************************/
        set_constant( 'ZC_STORAGE_DIR', ABSPATH . '/Storage/');
        set_constant( 'ZC_STORAGE_DIR_PERM', 0777);
        set_constant( 'ZC_STORAGE_JSON_PRETTY_PRINT', true);
        // PHP < 5.4 does not support JSON pretty print. Define neutral constant to ignore the previous one.
        set_constant( 'JSON_PRETTY_PRINT', 0);

        /************************************
         * Security constants
         ************************************/
        // Name of the HTTP Header containing the authentication token.
        set_constant( 'ZC_AUTH_HEADER_KEY', 'X-Auth-Token' );
        // Name of the URL parameter containing the authentication token.
        set_constant( 'ZC_AUTH_PARAM_KEY', 'auth_token' );
        // ID string size
        set_constant( 'ZC_ID_LENGTH', 32 );
        // Authentication token key. Must be a 36-character random string.
        set_constant( 'ZC_AUTH_TOKEN_KEY', '04b457d2b8c996fe57ae92bf779e2847' );

        /************************************
         * Validation constants
         ************************************/
        // Forbid duplicate nicknames if set to true.
        set_constant( 'ZC_NICK_CHECK_UNICITY', true );

        /************************************
         * Server Sent Events constants
         ************************************/
        // Seconds to sleep between two events checking.
        set_constant( 'ZC_SSE_SLEEP_TIME', 1.5 );
        // The time limit of the script in seconds. The client will have to reconnect after this time.
        set_constant( 'ZC_SSE_EXEC_LIMIT', 120 );
        // The interval in seconds of sending a keep alive signal.
        set_constant( 'ZC_SSE_KEEP_ALIVE_DELAY', 20 );
        // The time for the client to reconnect after connection has lost in seconds.
        set_constant( 'ZC_SSE_RECONNECT_TIME', 1 );

    }
}
