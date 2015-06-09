<?php

/**
 * getSiteMeta function.
 * Compiles the configured meta elements for the page
 *
 * @return array
 */
function getSiteMeta() {
	$metadata = array();
	$metadata[] = '<meta charset="utf-8">' . PHP_EOL;
	$metadata[] = '<meta http-equiv="x-ua-compatible" content="ie=edge">' . PHP_EOL;
	$metadata[] = '<meta name="viewport" content="width=device-width, initial-scale=1">' . PHP_EOL;

	foreach (Config::get('metadata') as $key => $value) {
		$metadata[] = '<meta name="' . $key . '" content="' . $value . '">' . PHP_EOL;
	}

	return implode(' ', $metadata);
}

/**
 * meta function. (alias for getSiteMeta)
 * 
 * @access public
 * @return void
 */
function meta() {
	return getSiteMeta();
}


/**
 * getSiteTitle function.
 * returns the concatenated page title: "{app name} | {page name}"
 * 
 * @access public
 * @return string
 */
function getSiteTitle() {
	return Config::get('app.name') . ' | ' . getPageTitle();
}

/**
 * title function. (alias for getSiteTitle)
 * 
 * @access public
 * @return void
 */
function title() {
	return getSiteTitle();
}


/**
 * pageTitle function.
 * returns the current pages title
 * 
 * @access public
 * @return string
 */
function getPageTitle() {
	if (empty(Config::get('app.uri'))) Config::set('app.uri', 'start');
	return getTitleFromUri(Config::get('app.uri'));
}


/**
 * getTitleFromUri function.
 * returns a title for a link from its uri 
 * 
 * @access public
 * @param string $uri the uri to build a title from
 * @return string
 */
function getTitleFromUri($uri) {
	return ucwords(str_replace('-', ' ', end(@explode('/', $uri))));
}


/**
 * getCurrentUri function.
 * returns the current uri
 * 
 * @access public
 * @return string
 */
function getCurrentUri() {
	return (Config::get('app.uri') === '' ? 'start' : Config::get('app.uri'));
}


/**
 * getAbsoluteUrl function.
 * returns the absolute URL for a given uri
 * 
 * @access public
 * @param mixed $uri
 * @return void
 */
function getAbsoluteUrl($uri) {
	return getSiteUrl() . '/' . ltrim($uri, '/');
}



/**
 * siteUrl function.
 * returns the sites URL
 * 
 * @access public
 * @return string
 */
function getSiteUrl() {
	return (isset($_SERVER['HTTPS']) ? "https" : "http") . '://' . $_SERVER['HTTP_HOST'];
}

/**
 * url function. (alias for getSiteUrl)
 * 
 * @access public
 * @return void
 */
function url() {
	return getSiteUrl();
}

function getGoogleAnalyticsEmbedCode() {
	$propertyID = Config::get('app.analyticsID');
	if (empty($propertyID)) return false;
	
	$html = "<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '" . $propertyID . "', 'auto');
  ga('send', 'pageview');
	</script>";

	return $html;
}

/**
 * googleAnalytics function. (alias for getGoogleAnalyticsEmbedCode)
 * 
 * @access public
 * @return void
 */
function googleAnalytics() {
	return getGoogleAnalyticsEmbedCode();
}


/**
 * getGoogleRecaptchaPublicKey function.
 * 
 * @access public
 * @return void
 */
function getGoogleRecaptchaPublicKey() {
	return Config::get('app.recaptcha.public');
}


/**
 * recaptchaKey function. (alias for getGoogleRecaptchaPublicKey)
 * 
 * @access public
 * @return void
 */
function recaptchaKey() {
	return getGoogleRecaptchaPublicKey();
}

function isAjaxRequest() {
	return (! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
		? true
		: false
	);
}


/**
 * lastSearchTerm function.
 * returns the results for the current query, if any.
 *
 * @access public
 * @return array
 */
function searchResults() {
	if ($query = $_GET['query']) {
		// if the query is to short, abort search
		if (strlen($query) < 4) return '<div class="container results">Please enter a longer search term.</div>';
		
		// retrieve results
		$results = Search::find($query);

		// if no results found, return info
		if (empty($results)) return '<div class="container results"><span class="noresults">No hits for "' . $query . '".</span></div>';
		
		// if we have results, compile container for them
		$html = '<div class="container results">' . PHP_EOL;
		$html .= '<h1>Search results:</h1>' . PHP_EOL;

		foreach ($results as $result => $data) {
			$html .= '<a data-navlink href="' . $data['url'] . '" class="container"><article class="result">' . PHP_EOL;
			$html .= '<h2>' . $data['title'] . '</h2>' . PHP_EOL;

			if (! empty($data['hits'])) {
				$html .= '<p>' . $data['hits'][$result] . '</p>' . PHP_EOL;
			}

			$html .= '</article></a>' . PHP_EOL;
		}
		
		$html .= '</div>';
		
		return $html;
	}
}

/**
 * lastSearchTerm function.
 * returns the last term searched for.
 *
 * @access public
 * @return string
 */
function lastSearchTerm() {
	return (! empty($_GET['query']) ? $_GET['query'] : Search);
}
