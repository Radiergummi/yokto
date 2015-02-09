<? namespace System;

/**
 * yokto
 *
 * minimalist website framework designed for the most basic web apps and sites
 *
 * @package		yokto
 * @link		http://moritzfriedrich.com
 * @copyright	http://unlicense.org/
 */

use ErrorException;

 
 
// Error handling
set_exception_handler(array('System\Error', 'exception'));
set_error_handler(array('System\Error', 'native'));
register_shutdown_function(array('System\Error', 'shutdown'));

// Session handling
$session = new Session();

// Routing
$route = (new Router())->match();

// View rendering
$response = View::render($route);

$session->write();
$session->get('id');

echo $response;



class Error {

	/**
	 * Exception handler
	 *
	 * This will log the exception and output the exception properties
	 * formatted as html or a 500 response depending on your application config
	 *
	 * @param object The uncaught exception
	 */
	public static function exception($e, $m = '') {
		log($e, 2);
		$message = (is_object($e) ? $e->getMessage() : $m);
		$file = (is_object($e) ? $e->getFile() : $e[0]['file']);
		$line = (is_object($e) ? $e->getLine() : $e[0]['line']);
		$trace = (is_object($e) ? static::getExceptionTraceAsString($e) : static::getDebugTraceAsString($e));
		
		if(Config::debug('detailed_error_pages')) {
			// clear output buffer
			while(ob_get_level() > 1) ob_end_clean();
				echo '<html>
					<head>
						<title>Uncaught Exception</title>
						<style>
							body{font-family:"Open Sans",arial,sans-serif;background:#FFF;color:#333;margin:2em}
							code{background:rgba(31,20,218,.22);border-radius:4px;padding:2px 6px}
						</style>
						<style>
							pre{margin:1rem;padding:1rem;background:#fafafa;border:1px solid #c0c0c0;border-radius:3px;box-shadow:0 1px 5px rgba(0,0,0,.2);white-space:pre-line}
							h1{display:block;margin:0 0 20px;padding:0 0 .5rem;border-bottom:1px solid #ccc}
							.info_self{float:right;padding:4px 10px;background:#BCFF95;border-radius:3px;border:1px solid #ccc;}
							.dump{margin:5px 0;padding:5px 1rem;background:#eee;border:1px solid #ccc;border-radius:3px;}
							.dump>h3{margin:5px 0}
							.trace{margin-top:1rem;padding-left:22px;line-height:calc(1rem + 4px);text-indent:-22px;word-wrap:break-word}
							.trace>div:last-of-type{color:#999}.trace>div:last-of-type>span{background:rgba(100,200,105,.44);color:#555}
							.trace span{padding:2px 6px;background:rgba(31,20,218,.22);border-radius:3px}
						</style>
					</head>
					<body>
						<h1>Uncaught Exception</h1>
						<p><code>' . $message . '</code></p>
						<h3>Origin</h3>
						<p><code>' . substr($file, strlen(PATH)) . ' on line ' . $line . '</code></p>
						<h3>Trace</h3>
						<pre>' . $trace . '</pre>
					</body>
					</html>';
			}
		else {
			// issue a 500 response
			debug(array('exception' => $e));
		}

		exit(1);
	}

	public static function getDebugTraceAsString($exception) {
		$output = "";
		$stackLen = count($exception);
		for ($i = 1; $i < $stackLen; $i++) {
			$entry = $exception[$i];

			$func = $entry['function'] . '(';
			$argsLen = count($entry['args']);
			for ($j = 0; $j < $argsLen; $j++) {
				$func .= $entry['args'][$j];
				if ($j < $argsLen - 1) $func .= ', ';
			}
        $func .= ')';

        $output .= $entry['file'] . ':' . $entry['line'] . ' - ' . $func . PHP_EOL;
    }
    return $output;
	}
	
	public static function getExceptionTraceAsString($exception) {
		$output = "";
		$count = 0;
		foreach ($exception->getTrace() as $frame) {
			$args = "";
			if (isset($frame['args'])) {
				$args = array();
				foreach ($frame['args'] as $arg) {
					if (is_string($arg)) {
						$args[] = "'" . $arg . "'";
					} elseif (is_array($arg)) {
						$args[] = "Array";
					} elseif (is_null($arg)) {
						$args[] = 'NULL';
					} elseif (is_bool($arg)) {
						$args[] = ($arg) ? "true" : "false";
					} elseif (is_object($arg)) {
						$args[] = get_class($arg);
					} elseif (is_resource($arg)) {
						$args[] = get_resource_type($arg);
					} else {
						$args[] = $arg;
					}
				}
				$args = join(", ", $args);
			}
			$output .= sprintf( "#%s %s on line %s: %s%s(%s)\n",
				$count,
				substr($frame['file'], strlen(PATH)),
				$frame['line'],
				isset($frame['class']) ? $frame['class'] . '->' : '',
				$frame['function'],
				$args );
			$count++;
		}
		return $output;
	}
	
	/**
	 * Java like Exception handler (currently unused)
	 *
	 * @param object The uncaught exception
	 */
	
	public static function jTraceEx($e, $seen=null) {
		$starter = $seen ? 'Caused by: ' : '';
		$result = array();
		if (!$seen) $seen = array();
		$trace  = $e->getTrace();
		$prev   = $e->getPrevious();
		$result[] = sprintf('%s%s: %s', $starter, get_class($e), $e->getMessage());
		$file = $e->getFile();
		$line = $e->getLine();
		while (true) {
			$current = "$file:$line";
			if (is_array($seen) && in_array($current, $seen)) {
				$result[] = sprintf(' ... %d more', count($trace)+1);
				break;
			}
			$result[] = sprintf(' at %s%s%s(%s%s%s)',
			count($trace) && array_key_exists('class', $trace[0]) ? str_replace('\\', '.', $trace[0]['class']) : '',
			count($trace) && array_key_exists('class', $trace[0]) && array_key_exists('function', $trace[0]) ? '.' : '',
			count($trace) && array_key_exists('function', $trace[0]) ? str_replace('\\', '.', $trace[0]['function']) : '(main)',
			$line === null ? $file : basename($file),
			$line === null ? '' : ':',
			$line === null ? '' : $line);
			if (is_array($seen))
				$seen[] = "$file:$line";
			if (!count($trace))
				break;
			$file = array_key_exists('file', $trace[0]) ? $trace[0]['file'] : 'Unknown Source';
			$line = array_key_exists('file', $trace[0]) && array_key_exists('line', $trace[0]) && $trace[0]['line'] ? $trace[0]['line'] : null;
			array_shift($trace);
		}
		$result = join("\n", $result);
		if ($prev)
			$result  .= "\n" . jTraceEx($prev, $seen);

		return $result;
	}

	/**
	 * Error handler
	 *
	 * This will catch the php native error and treat it as a exception
	 * which will provide a full back trace on all errors
	 *
	 * @param int
	 * @param string
	 * @param string
	 * @param int
	 * @param array
	 */
	public static function native($code, $message, $file, $line, $context) {
		if($code & error_reporting()) {
			static::exception(new ErrorException($message, $code, 0, $file, $line));
		}
	}

	/**
	 * Shutdown handler
	 *
	 * This will catch errors that are generated at the
	 * shutdown level of execution
	 */
	public static function shutdown() {
		if($error = error_get_last()) {
			extract($error);

			static::exception(new ErrorException($message, $type, 0, $file, $line));
		}
	}
}



class Config {
	/**
	 * holds the configuration
	 * 
	 * @var array
	 */
	public static $data = array();
	
	/**
	 * set a file in the config array
	 * 
	 * @param string
	 * @param string
	 * @param string
	 * 
	 */
	public static function set($file, $key, $value){
			static::$data[$file][$key] = $value;
	}
	
	/**
	 * retrieve a value from the configuration by looking into
	 * the data array first and importing data from file if not yet present.
	 * 
	 * @param string
	 * @param string
	 * 
	 * @return string|array
	 */
	public static function get($file, $key){
		
		// check if config group exists
		if (!isset(static::$data[$file])) {
			if(is_readable($path = SYS . 'config' . DS . $file . EXT)) {
				static::$data[$file] = require $path;
			}
		} else {
			
			// check if config key exists in group
			if (!isset(static::$data[$file][$key])) {
				if(is_readable($path = SYS . 'config' . DS . $file . EXT)) {
					static::$data[$file] = array_merge(static::$data[$file], require $path);
				}
			}
		}			
		return (isset($key) ? static::$data[$file][$key] : static::$data[$file]);
	}
	
	/**
	 * Returns a value from the config array using the
	 * method call as the array key reference
	 *
	 * @example Config::app('url');
	 *
	 * @param string
	 * @param array
	 *
	 * @return string|array
	 */
	public static function __callStatic($method, $arguments = '') {
		$key = $method;
		$value = (is_array($arguments) && ($arguments > 1) ? array_shift($arguments) : (string) $arguments);

		return static::get($key, $value);
	}
	
}




class Router {
	
	/**
	 * the available routes
	 *
	 * @var array
	 */
	protected $routes;

	/**
	 * the verified route
	 *
	 * @var array
	 */
	protected $match;

	/**
	 * Constructor
	 *
	 * @param array
	 * @param string
	 */
	public function __construct($additional_routes = array()) {
		$routes = Config::routes();
		$this->routes = array_merge($additional_routes, $routes, $this->get_routes('content'));
	}

	/**
	 * match the request to an existing route
	 *
	 * @param array
	 * @param string
	 */
	public function match() {
		$uri = explode('?', rtrim(ltrim(htmlspecialchars($_SERVER['REQUEST_URI']),'/'),'/'));
		$request = array_shift($uri);
		Config::set('env', 'get', array_shift($uri));

		$routes = $this->routes;

		// if the request is contained in our routes, set match
		foreach ($routes as $route => $meta) {
			if ($request === $meta['slug']) {

				// store request in config 
				Config::set('env', 'request', $request);
				
				$this->match = $routes[$route];

				break;

			} else {

				// if no route is found, return the array for our error page
				$this->match = $routes[0];
			}
		}

		if ($this->match === 'error' && Config::debug('loglevel') === 'development') {
		
			// if we've got an error and debugging enabled, log the routing error
			log('The request ' . $request . ' could not be matched.', 1);
		}		

		return $this->match;
	}

	/**
	 * get all files in content dir recursively
	 *
	 * @param array
	 * @param string
	 */
	public function get_routes($directory, $ext = '') {
	    $array_items = array();
	    if($handle = opendir($directory)){
	        while(false !== ($file = readdir($handle))){
	            if(preg_match("/^(^\.)/", $file) === 0){
	                if(is_dir($directory. "/" . $file)){
	                    $array_items = array_merge($array_items, $this->get_routes($directory. "/" . $file, $ext));
	                } else {
	                    $file = ltrim($directory . "/" . $file,'content/');
						$array_items[]['slug'] = preg_replace('/\\.[^.\\s]{2,3}$/', '', $file);
	                }
	            }
	        }
	        closedir($handle);
	    }
	    return $array_items;
	}
}


class Parser {

	public static function markdown($input) {
		if (is_readable($file = SYS . 'thirdparty' . DS . Config::thirdparty('markdown-dir') . DS . Config::thirdparty('markdown-file') . EXT)) {
			require $file;
			
			return \Parsedown::instance()->text($input);

		} else {
			throw new \Exception('Thirdparty library "' . Config::thirdparty('markdown') . '" could not be located.');
		}
	}
	
	public static function json($input){
		if (is_readable($file = SYS . 'thirdparty' . DS . Config::thirdparty('json-dir') . DS . Config::thirdparty('json-file') . EXT)) {
			require $file;
			
			return \Parsedown::instance()->text($input);

		} else {
			throw new \Exception('Thirdparty library "' . Config::thirdparty('json') . '" could not be located.');
		}
	}
}



class View {

	/**
	 * replacement variables
	 *
	 * @var array
	 */
	public static $vars = array();

	/**
	 * prepares the html content and delivers it to the client.
	 * 
	 * @param boolean
	 */
	public static function render($route, $ajax = false) {
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') $ajax = true;
		//sdebug(Config::env('get'));
		if(isset($_GET['inline'])) $ajax = true;
		
		$template_dir = PATH . 'themes' . DS . Config::env('theme') . DS;
		
		$request = Config::routes('request');

		if ($route['slug'] === 'error') {

			// append to log channel messages
			log('Error 404 - Page not found: "' . URL . DS . $request . '" requested from client ' . $_SERVER['REMOTE_ADDR'] . '.', 0);

			// set correct headers
			header('Status: 404 Not Found');
			
			// set error template
			$template = 'http_404';
			
		} else {
			$template = (!empty($route['template']) ? $route['template'] : Config::env('default_template'));
		}

		$theme = self::get_theme();
		$vars = (!empty($route['variables']) ? array_merge(self::$vars, $route['variables']) : self::$vars);

		$vars['sitename'] = Config::env('sitename');
		$vars['title'] = ((!empty($request)) && (!empty($route)) ? $vars['sitename'] . ' | ' . $route['name'] : $vars['sitename']);
		$vars['name'] = $route['name'];
		$vars['slug'] = $route['slug'];
		
		// start collecting the output
		ob_start();
		
			extract($vars);
			require SYS . 'theme_functions'. EXT;
		
			if (!$ajax) require $template_dir . 'inc_header' . EXT;

			require $template_dir . $template . EXT;
			
			if (!$ajax) require $template_dir . 'inc_footer' . EXT;
		
		return ob_get_clean();
	}
	
	public static function get_theme(){
		if (is_readable($file = PATH . 'themes' . DS . Config::env('theme') . DS . 'theme.json')){
			return json_decode(file_get_contents($file),true);
		} else {
			Error::exception(debug_backtrace(), 'Theme file not found at ' . $file);

			// append to log channel error
			log('Error 404 - Page not found: "' . URL . DS . $request . '" requested from client ' . $_SERVER['REMOTE_ADDR'] . '.', 2);
		}
	}
}


class Session {
	/**
	 * the current session
	 *
	 * @var array
	 */
	public static $cookies = array();

	public function __construct() {
		self::read('yokto');
		static::$cookies['id'] = 'test';
		static::$cookies['timestamp'] = time();
	}

	public static function set($key, $value) {
		static::$cookies[$key] = $value;
	}

	public static function get($key, $value = null) {
		if(array_key_exists($key, static::$cookies)) {
			return (isset($value) ? static::$cookies[$key][$value] : static::$cookies[$key]);
		} else {
			return 'not set';
		}
	}

	/**
	 * Reads data from cookie
	 *
	 * @param string
	 * @param mixed
	 * @param int
	 * @param string
	 * @param string
	 * @param bool
	 */
	public static function read($name) {
		if (array_key_exists($name, $_COOKIE)) {
			static::$cookies = unserialize(base64_decode($_COOKIE[$name]));
		} else {
			// TODO: throw error
			log('The cookie content could not be read.');
		}
	}

	/**
	 * Adds a cookie to be written
	 *
	 * @param string
	 * @param int
	 */
	public static function write($name = 'yokto', $expiration = 0) {
		$path = '/';
		$domain = '.' . $_SERVER['SERVER_NAME']; // .site.example.com
		$secure = (Config::env('protocol') === 'https://' ? true : false); // make security dependent of used protocol
		$httponly = false; // for future restful CLI request
		
		if ($expiration !== 0) $expiration = time() + $expiration; // expiration date defaults to session
		
		$payload = base64_encode(serialize(static::$cookies));
		
		\setcookie($name, $payload, $expiration, $path, $domain, $secure, $httponly);
	}

	/**
	 * Returns a value from the cookie array using the
	 * method call as the array key reference
	 *
	 * @param string
	 * @param array
	 *
	 * @return string|array
	 */
	public static function __callStatic($method, $arguments = '') {
		$key = $method;
		$value = (is_array($arguments) && ($arguments > 1) ? array_shift($arguments) : (string) $arguments);
		return static::get($key, $value);
	}
}

 
 
/**
 * appends a message to the log. 
 * 
 * @param string $message the message to log
 * @param int $channel [0,1,2] the channel to log to, defaults to 1 = debug.
 */
function log($message,$channel = 1) {
	$file = array('messages','debug','error');
	error_log(date('r',time()) . ': ' . $message . PHP_EOL, 3, Config::debug('logdir') . $file[$channel] . '.log');
}
/**
 * stops the code whereever inserted, dumps given variable in readable form
 * ! neat formatting stolen from http://php.net/manual/de/function.debug-backtrace.php#111355
 * 
 * @param mixed $data the variable to dump
 */
 function debug($data = 'no data given') {
	$debug = array_reverse(debug_backtrace());
 if (Config::debug('loglevel') == 'production') return;

	$trace;
    foreach ($debug as $k => $v) { 
        array_walk($v['args'], function (&$item, $key) { 
            $item = var_export($item, true); 
        }); 
        $trace .= '<div><span>#' . $k . '    ' . $v['file'] . ' (<b>' . $v['line'] . '</b>):</span> ' . (isset($v['class']) ? $v['class'] . '->' : '') . $v['function'] . '(' . implode(', ', $v['args']) . ')' . "</div>\n"; 
    }
	echo '<style>pre{margin:1rem;padding:1rem;background:#fafafa;border:1px solid #c0c0c0;border-radius:3px;box-shadow:0 1px 5px rgba(0,0,0,.2);white-space:pre-line}h1{display:block;margin:0 0 20px;padding:0 0 .5rem;border-bottom:1px solid #ccc}.info_self{float:right;padding:4px 10px;background:#BCFF95;border-radius:3px;border:1px solid #ccc;}.dump{margin:5px 0;padding:5px 1rem;background:#eee;border:1px solid #ccc;border-radius:3px;}.dump>h3{margin:5px 0}.trace{margin-top:1rem;padding-left:22px;line-height:calc(1rem + 4px);text-indent:-22px;word-wrap:break-word}.trace>div:last-of-type{color:#999}.trace>div:last-of-type>span{background:rgba(100,200,105,.44);color:#555}.trace span{padding:2px 6px;background:rgba(31,20,218,.22);border-radius:3px}</style>';
	echo '<pre><div class="info_self"> Halted at line ' . $debug[0]['line'] . '.</div><h1>Debug Info:</h1>';
	echo '';
	echo '<div class="dump"><h3>Dumped ' . gettype($data) . ':</h3>';
	print_r($data);
	echo '</div><div class="trace">';
	print_r($trace);
	echo '</div></pre>';
	exit();
 }
