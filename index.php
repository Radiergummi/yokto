<?php

// env definitions
define('DS', DIRECTORY_SEPARATOR);

define('PATH', dirname(__FILE__) . DS);

define('SYS', PATH . 'system' . DS);

define('PUB', PATH . 'public' . DS . 'content' . DS);

define('EXT', '.php');



// start the app
require SYS . 'start' . EXT;
