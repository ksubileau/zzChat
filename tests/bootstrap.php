<?php

define('ZZCHAT_TEST_MODE', true);

set_include_path(dirname(__FILE__) . '/../api' . PATH_SEPARATOR . get_include_path());

if ( !defined('ABSPATH') )
    define('ABSPATH', dirname(__FILE__) . '/../api');

require( './ZZChatTestCase.php' );

require( ABSPATH . '/index.php' );
