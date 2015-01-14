<!Doctype html>
<html>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<title><?=$title?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="description" content="Yokto test site">
	<meta name="keywords" content="This is a demo page to showcase Yokto, an open source CMS for developers.">
	<meta name="web_author" content="Moritz Friedrich">

	<meta property="og:title" content="<?=$title?>">
	<meta property="og:image" content="<?=theme_url('assets')?>/img/logo.png" />
	<meta property="og:site_name" content="Yokto test instance" />
	<meta property="og:description" content="This is a demo page to showcase Yokto, an open source CMS for developers." />

	<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">

	<link rel="stylesheet" href="<?=theme_url('assets')?>/css/reset.min.css" />
	<link rel="stylesheet" href="<?=theme_url('assets')?>/css/modulo.lib.css" />
	<link rel="stylesheet" href="<?=theme_url('assets')?>/css/style.css" />
	<link rel="stylesheet" href="<?=theme_url('assets')?>/css/night.css" />
	<?
		date_default_timezone_set('Europe/Berlin'); 

		$night = false;
		$summer = false;
		$m = date('m');
		$h = date('H');
		$summer = ($m > 4) && ($m < 10) ? true : false;
		if ($summer && (( $h >= 0 && $h < 7) || ($h > 20 ))) {
			$night = true;
		} else if (!$summer && (( $h >= 0 && $h < 8) || ($h > 17))) {
			$night = true;
		}
	?>
</head>
<body ontouchstart="" class="<? if (!$slug) echo 'home'; if ($night) echo ' night';?>">
    <header>
		<div id="themeSwitcher" role="button" title="Switch contrast">
			<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
				<path id="brightness-9-icon" d="M256,90c44.342,0,86.027,17.266,117.381,48.619S422,211.66,422,256s-17.266,86.027-48.619,117.379 C342.027,404.732,300.342,422,256,422V90z M256,50C142.23,50,50,142.229,50,256s92.23,206,206,206c113.771,0,206-92.229,206-206 S369.771,50,256,50z"></path>
			</svg>
		</div>
		<div class="heading">
		<h1><a href="<?=site_url()?>"><?=site_name();?></a></h1>
		</div>
		<nav>
			<ul>
				<li><a href="/markdown">Markdown</a></li>
				<li><a href="/themes_templates">Themes and Templates</a></li>
				<li><a href="/about">About Yokto</a></li>
				<li><a href="/portfolio">About me</a></li>
			</ul>
		</nav>
    </header>
	<main>
