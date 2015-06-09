<?php

/**
 * General purpose config class
 * 
 * @package php-config
 * @author Moritz Friedrich <m@9dev.de>
 */
class Config
{

	/**
	 * holds the configuration data
	 * 
	 * (default value: array())
	 * 
	 * @var array
	 * @static
	 * @access private
	 */
	private static $data = array();


	/**
	 * populate function.
	 * Populates the data array with the values injected at runtime
	 *
	 * @access public
	 * @static
	 * @param mixed $data  The raw input data
	 * @return void
	 */
	public static function populate($data)
	{
		// make sure we don't accidentially overwrite the configuration
		if (count(static::$data)) throw new RuntimeException('Config is already populated');
	  
		// if the data provided is not an array, parse it
		static::add($data);
	}
  
  
	/**
	 * parseJSON function.
	 * parses the input given as a json string
	 * 
	 * @access private
	 * @static
	 * @param string $input  a JSON string
	 * @return array $data  the parsed data
	 */
	private static function parseJSON($input)
	{
		// JSON error message strings
		$errorMessages = array(
		  'No error has occurred',
		  'The maximum stack depth has been exceeded',
		  'Invalid or malformed JSON',
		  'Control character error, possibly incorrectly encoded',
		  'Syntax Error',
		  'Malformed UTF-8 characters, possibly incorrectly encoded'
		);

		// decode JSON and throw exception on error
		$data = json_decode($input, true);
		if (($error = json_last_error()) != 0) {
		   throw new \RuntimeException('Error while parsing JSON: ' . $errorMessages[$error] . ' ("' . substr($input, 0, 20) . '...")');
		}

		return $data;
	}


	/**
	 * add function.
	 * merges the config data with another array
	 * 
	 * @access public
	 * @static
	 * @param string|array $input  the data to add
	 * @return void
	 */
	public static function add($input)
	{
		// if we got a string, it can be either JSON, a filename or a folder path.
		if (is_string($input)) {

			// if we got a directory, add each file separately again (except . and ..).
			if (is_dir($input)) {
				if (! is_readable($input)) throw new \RuntimeException('Directory "' . $input . '" is not readable.');
				
				// add each file in a directory to the config
				foreach (array_diff(scandir($input), array('..', '.')) as $file) {
					static::add(rtrim($input, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file);
				}
				
				return;
			}
			
			// if we got a filename, decide how to handle it based on the extension, then add its parsed content again
			if (is_file($input)) {
				if (! is_readable($input)) throw new \RuntimeException('File "' . $input . '" is not readable.');

				switch(pathinfo($input, PATHINFO_EXTENSION)) {
					case 'php':
						$content = require($input);
						break;

					case 'json':
						$content = static::parseJSON(file_get_contents($input));
						break;

					// example INI implementation
					#case 'ini':
					#	$content = $this->parseINI(file_get_contents($input));
					#	break;
				}
        
				static::add($content);

				return;
			} 

			// string input is generally treated as JSON
			static::add(static::parse($input));

			return;
		}

		// if we got an array, it has either been injected as one or parsed already. Either way it will be merged now.
		if (is_array($input)) {
			static::$data = array_replace_recursive(static::$data, $input);

			return;
		}

		// if we have no match, throw an exception (this happens if neither a string nor an array was given).
		throw new \RuntimeException('Provided data could not be parsed ("' . substr($input, 0, 20) . '...").');

		return;
	}



	/**
	 * get function.
	 * gets a value from the config array
	 *
	 * @access public
	 * @static
	 * @param string $key (default: null)  the config key in question
	 * @param mixed $fallback (default: null)  a fallback value in case the config is empty
	 * @return  the value of $data[$key]
	 */
	public static function get($key = null, $fallback = null)
	{
		// return the whole config if no key specified
		if (! $key) return static::$data;
		
		$keys = explode('.', $key);
		$values = static::$data;

		if (count($keys) == 1) {

			return (array_key_exists($keys[0], $values) ? $values[$keys[0]] : $fallback);
		} else {

			// search the array using the dot character to access nested array values
			foreach($keys as $key) {

				// when a key is not found or we didnt get an array to search return a fallback value
				if(! array_key_exists($key, $values)) {

					return $fallback;
				}

				$values =& $values[$key];
			}

			return $values;
		}
	}


	/**
	 * has function.
	 * checks wether a key is set or not
	 *
	 * @access public
	 * @static
	 * @param string $key  the config key in question
	 * @return bool wether the key exists or not
	 */
	public static function has($key)
	{
		return (! is_null(static::get($key))) ? true : false;	
	}

	/**
	 * set function.
	 * sets a value in the config array
	 * 
	 * @access public
	 * @static
	 * @param string $key the config key in question
	 * @param mixed $value the value to set 
	 * @return void;
	 */
	public static function set($key, $value)
	{
		$array =& static::$data;
		$keys = explode('.', $key);
		// traverse the array into the second last key
		while(count($keys) > 1) {
			$key = array_shift($keys);
			// make sure we have an array to set our new key in
			if( ! array_key_exists($key, $array)) {
				$array[$key] = array();
			}
			$array =& $array[$key];
		}
		$array[array_shift($keys)] = $value;
	}


	/**
	 * erase function.
	 * erases a key from the array
	 *
	 * @access public
	 * @static
	 * @param string $key the config key in question
	 */
	public function erase(string $key)
	{
		$array =& static::$data;
		$keys = explode('.', $key);
		// traverse the array into the second last key
		while(count($keys) > 1) {
			$key = array_shift($keys);
			$array =& $array[$key];
		}
		unset($array[array_shift($keys)]);
	}
}
