<?php

/**
 * Theme functions
 */
 
function site_name() {
	return System\Config::env('sitename');
}

function site_text($page) {
	$md = (is_readable($file = PATH . 'content' . DS . $page . '.md')) ? file_get_contents($file) : '';
	return System\Parser::markdown($md);
}

function site_url() {
	return URL . '/';
}

function theme_url($extra = '') {
	return site_url() . 'themes/' . System\Config::env('theme') . '/' . ltrim($extra, '/');
}

function is_homepage() {
	
}

// include custom theme functions
if (is_readable($file = theme_url() . 'functions' . EXT)) {
	include($file);
} else if (System\Config::debug('loglevel') === 'development') {
	System\log('No functions.php found in theme folder.', 1);
}
