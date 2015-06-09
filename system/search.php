<?
class Search
{
	/**
	 * query
	 * 
	 * (default value: '')
	 * 
	 * @var string
	 * @access public
	 * @static
	 */
	public static $query = '';

	/**
	 * directory
	 * 
	 * (default value: '')
	 * 
	 * @var string
	 * @access public
	 * @static
	 */
	public static $directory = '';

	/**
	 * files
	 * 
	 * (default value: array())
	 * 
	 * @var array
	 * @access public
	 * @static
	 */
	public static $files = array();

	/**
	 * searchpath
	 * 
	 * (default value: array())
	 * 
	 * @var array
	 * @access public
	 * @static
	 */
	public static $searchpath = array();

	/**
	 * excludes
	 * 
	 * (default value: array())
	 * 
	 * @var array
	 * @access public
	 * @static
	 */
	public static $excludes = array();
	
	/**
	 * results
	 * 
	 * (default value: array())
	 * 
	 * @var array
	 * @access public
	 * @static
	 */
	public static $results = array();

	/**
	 * surroundingTextLength
	 * 
	 * (default value: 0)
	 * 
	 * @var int
	 * @access public
	 * @static
	 */
	public static $surroundingTextLength = 0;

	/**
	 * resultsPerFile
	 * 
	 * (default value: 0)
	 * 
	 * @var int
	 * @access public
	 * @static
	 */
	public static $resultsPerFile = 0;
	
	/**
	 * find function.
	 * attempts to find a string in a directory and returns an array
	 * 
	 * @access public
	 * @static
	 * @param mixed $query
	 * @return array
	 */
	public static function find($query)
	{
		// set path to search and excluded files from config respectively
		static::$query = $query;
		static::$searchpath = Config::get('app.search.path');
		static::$excludes = Config::get('app.search.excludes');
		static::$surroundingTextLength = Config::get('app.search.surroundingTextLength');
		static::$resultsPerFile = Config::get('app.search.resultsPerFile');
		
		// iterate over specified folder, skipping . and .. directories
		foreach(new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator(
				static::$searchpath,
				RecursiveDirectoryIterator::SKIP_DOTS
				)
		) as $file) {
			// skip excluded file
			if (in_array($file->getFileName(), static::$excludes)) continue;

			// add filepathpath relative to base search directory
			static::$files[] = substr($file->getPath() . DS . $file->getFileName(), strlen(static::$searchpath));

			// search file for term
			if (stristr($content = strip_tags(nl2br(file_get_contents($file)), '<br /><code><p>'), static::$query) !== false) {

				// build URL to resource
				$result['url'] = '/' . substr(end(static::$files), 0, -strlen(EXT));
					
				// build name for resource
				$result['title'] = static::getNameFromUri($result['url']);
			
				// generate snippet with search term
				if (preg_match_all('/((\s\S*){0,' . static::$surroundingTextLength . '})(' . static::$query . ')((\s?\S*){0,' . static::$surroundingTextLength . '})/im', $content, $matches, PREG_SET_ORDER)) {
					// limit results per file
					$resultLimit = (static::$resultsPerFile === 0 ? count($matches) : static::$resultsPerFile);

					for ($i = 0; $i < $resultLimit; $i++) {
						if (!empty($matches[$i][3])) {
							$result['hits'][] = vsprintf('[...] %s<span class="term">%s</span>%s [...]', [$matches[$i][1], $matches[$i][3], $matches[$i][4]]);
						} else {
							$result['hits'][] = 'keine Treffer';
						}
					}
				}

				// if found, append to results
				static::$results[] = $result;


				// match query against page names
			} else if (stristr($file->getFileName(), static::$query)) {

				// build URL to resource
				$result['url'] = '/' . substr(end(static::$files), 0, -strlen(EXT));

				// build name for resource
				$result['title'] = static::getNameFromUri($result['url']);

				// if found, append to results
				static::$results[] = $result;
			}
		}

		return static::$results;
	}
	
	/**
	 * getNameFromUri function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $uri
	 * @return string
	 */
	public static function getNameFromUri($uri) {
		return ucwords(str_replace('-', ' ', end(@explode('/', $uri))));
	}
}
