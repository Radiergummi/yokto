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

class Error {

	/**
	 * Exception handler
	 *
	 * This will log the exception and output the exception properties
	 * formatted as html or a 500 response depending on your application config
	 *
	 * @param object The uncaught exception
	 */
	public static function exception($e) {
		log($e, 2);

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
						<p><code>' . $e->getMessage() . '</code></p>
						<h3>Origin</h3>
						<p><code>' . substr($e->getFile(), strlen(PATH)) . ' on line ' . $e->getLine() . '</code></p>
						<h3>Trace</h3>
						<pre>' . self::getExceptionTraceAsString($e) . '</pre>
					</body>
					</html>';
			}
		else {
			// issue a 500 response
			debug(array('exception' => $e));
		}

		exit(1);
	}

	
	public static function getExceptionTraceAsString($exception) {
		$rtn = "";
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
			$rtn .= sprintf( "#%s %s(%s): %s%s(%s)\n",
				$count,
				$frame['file'],
				$frame['line'],
				isset($frame['class']) ? $frame['class'] . '->' : '',
				$frame['function'],
				$args );
			$count++;
		}
		return $rtn;
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
