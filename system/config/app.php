<?php return array(
	'app' => [
		# The apps name
		'name' => 'KomBox',
		
		# Google Analytics Tracking Code (format: 'UA-12345678-9').
		# To disable analytics, set to 'null'
		'analyticsID' => null,
		
		# Google Recaptcha data
		'recaptcha' => [
			'public' => '',
			'secret' => ''
		],

		'search' => [

			# base search path
			'path' => PUB,
			
			# Files to exclude from search
			'excludes' => [
				'header.php',
				'footer.php',
				'error.php'
			],

			# Max. number of search results to go for
			# '0' = infinite
			'resultsPerFile' => 0,

			# Amount of words "around" search term to capture for snippets
			'surroundingTextLength' => 8
		]
	]
);
