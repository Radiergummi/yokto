<?php
/**
 * require base classes
 */
require SYS . 'config' . EXT;
require SYS . 'view' . EXT;
require SYS . 'search' . EXT;
require SYS . 'router' . EXT;


/**
 * populate configuration
 */
Config::add(SYS . 'config');


/**
 * define input
 */
$input = $_SERVER['REQUEST_URI'];


/**
 * route the request
 */
$response = Router::route($input);


/**
 * add header and footer partials to view
 */
$response->partial('header')->partial('footer');


/**
 * output content
 */
echo $response->render();
