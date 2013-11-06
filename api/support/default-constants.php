<?php
/**
 * Defines constants and global variables that can be overridden, generally in config.php.
 *
 * @package ZZChat
 */

/**
 * Defines initial ZZChat constants
 */
function set_initial_constants( ) {

    // Debug constants
    set_constant( 'ZC_DEBUG', false );
    set_constant( 'ZC_DEBUG_DISPLAY', true);
    set_constant( 'ZC_DEBUG_LOG', false);

    // Path constants
    set_constant( 'ZC_STORAGE_DIR', ABSPATH . '/storage/');

    // Security constants
    set_constant( 'AUTH_HEADER_KEY', 'X-AUTH-TOKEN' );
    set_constant( 'AUTH_PARAM_KEY', 'auth_token' );

}
