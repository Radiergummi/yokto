<? namespace System;
/**
 * picoFrame v0.2
 *
 * minimalist website framework designed for the most basic web apps and sites
 *
 * @package		picoFrame
 * @link		http://moritzfriedrich.com
 * @copyright	http://unlicense.org/
 */

 
 
// site name
define('SITENAME', 'Website name');

// template folder path
define('TDIR', 'templates' . DS);

// asset folder path
define('ADIR', 'assets');

// available routes for this site
$routes = array(
	'1' => 'example',
	'2' => 'you can see where this is going'
);

$error = array(
	'http_404' => 'Error: The requested page could not be found.',
	'template_not_found' => 'Error: The requested template could not be found.'
);

// available content variables to replace
$vars = array(
	'{$title}' => SITENAME,
	'{$siteurl}' => URL,
	'{$assetdir}' => ADIR,
	'{$name}' => '',
	'{$file}' => '',
	'{$description}' => '',
);
