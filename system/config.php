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

// available routes for this site. every route has its own array which is extendable
$routes = array(
	array(
		'slug' => '',
		'name' => 'Start',
		'template' => 'page_start'
	),
	array(
		'slug' => 'foo',
		'name' => 'foo bar!',
		'template' => 'page'
	),
	array(
		'slug' => 'kind-of-rediculous',
		'name' => 'Example Page',
		'template' => 'page_portfolio'
	)
);

// available error messages
$error = array(
	'http_404' => 'Error: The requested page could not be found.',
	'template_not_found' => 'Error: The requested template could not be found.'
);

// available content variables to replace
$vars = array(
	'{$title}' => SITENAME,
	'{$siteurl}' => URL,
	'{$assetdir}' => 'assets',
	'{$name}' => '',
	'{$file}' => '',
	'{$description}' => '',
);
