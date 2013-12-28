<?php

define('ZZCHAT_TEST_MODE', true);

set_include_path(dirname(__FILE__) . '/../api' . PATH_SEPARATOR . get_include_path());

if ( !defined('ABSPATH') )
    define('ABSPATH', dirname(__FILE__) . '/../api');

if ( !defined('TESTABSPATH') )
    define('TESTABSPATH', dirname(__FILE__));

require( TESTABSPATH . '/ZZChatTestCase.php' );

require( ABSPATH . '/index.php' );
