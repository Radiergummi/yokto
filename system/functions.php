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
 
 
/**
 * appends a message to the log. 
 * 
 * @param string $message the message to log
 * @param int $channel [0,1,2] the channel to log to, defaults to 1 = debug.
 */
function log($message,$channel = 1) {
	$file = array('messages','debug','error');
	error_log(date('r',time()) . ': ' . $message . PHP_EOL, 3, LOGDIR . $file[$channel] . '.log');
}
/**
 * stops the code whereever inserted, dumps given variable in readable form
 * ! neat formatting stolen from http://php.net/manual/de/function.debug-backtrace.php#111355
 * 
 * @param mixed $data the variable to dump
 */
 function debug($data = 'no data given') {
	$debug = array_reverse(debug_backtrace());
	$trace;
    foreach ($debug as $k => $v) { 
        array_walk($v['args'], function (&$item, $key) { 
            $item = var_export($item, true); 
        }); 
        $trace .= '<div><span>#' . $k . '    ' . $v['file'] . ' (<b>' . $v['line'] . '</b>):</span> ' . (isset($v['class']) ? $v['class'] . '->' : '') . $v['function'] . '(' . implode(', ', $v['args']) . ')' . "</div>\n"; 
    } 
	echo '<style>pre{margin:1rem;padding:1rem;background:#fafafa;border:1px solid #c0c0c0;border-radius:3px;box-shadow:0 1px 5px rgba(0,0,0,.2);white-space:pre-line}h1{display:block;margin:0 0 20px;padding:0 0 .5rem;border-bottom:1px solid #ccc}.info_self{float:right;padding:4px 10px;background:#BCFF95;border-radius:3px;border:1px solid #ccc;}.dump{margin:5px 0;padding:5px 1rem;background:#eee;border:1px solid #ccc;border-radius:3px;}.dump>h3{margin:5px 0}.trace{margin-top:1rem;padding-left:22px;line-height:calc(1rem + 4px);text-indent:-22px;word-wrap:break-word}.trace>div:last-of-type{color:#999}.trace>div:last-of-type>span{background:rgba(100,200,105,.44);color:#555}.trace span{padding:2px 6px;background:rgba(31,20,218,.22);border-radius:3px}</style>';
	echo '<pre><div class="info_self"> Halted at line ' . $debug[0][line] . '.</div><h1>Debug Info:</h1>';
	echo '';
	echo '<div class="dump"><h3>Dumped ' . gettype($data) . ':</h3>';
	print_r($data);
	echo '</div><div class="trace">';
	print_r($trace);
	echo '</div></pre>';
	exit();
 }
