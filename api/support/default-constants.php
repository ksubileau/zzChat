<?php
namespace ZZChat\Support;

/**
 * Defines constants and global variables that can be overridden, generally in config.php.
 *
 * @package ZZChat
 */

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
    set_constant( 'ZC_STORAGE_DIR', ABSPATH . '/storage/');

    /************************************
     * Security constants
     ************************************/
    // Name of the HTTP Header containing the authentication token.
    set_constant( 'ZC_AUTH_HEADER_KEY', 'X-AUTH-TOKEN' );
    // Name of the URL parameter containing the authentication token.
    set_constant( 'ZC_AUTH_PARAM_KEY', 'auth_token' );
    // User ID string size
    set_constant( 'ZC_UID_LENGTH', 32 );
    // Authentication token key. Must be a 36-character random string.
    set_constant( 'ZC_AUTH_TOKEN_KEY', '04b457d2b8c996fe57ae92bf779e2847' );

}
