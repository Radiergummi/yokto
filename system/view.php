<?php

/**
 * General purpose view class
 * 
 * @package libview
 * @author Moritz Friedrich <m@9dev.de>
 */
class View
{
	/**
	 * holds the current template
	 * 
	 * @var string
	 */
	private $template = '';


	/**
	 * holds the template variables
	 * 
	 * @var array
	 */
	private $variables = array();


	/**
	 * the default path to the template directory, shared among all views
	 * 
	 * @var string
	 */
	public static $templateDir = '';


	/**
	 * Constructor
	 * 
	 * @param string $template  the template file to work with
	 * @param array $variables (optional)  the variables to replace in the template
	 * @param string $templateDir (optional)  a custom template directory for this view
	 * @param string $defaultTemplate (optional)  a fallback template in case the spicified isn't to be found
	 */
	public function __construct($template, array $variables = array())
	{
			$this->template = $template;
			$this->variables = $variables;
	}


	/**
	 * Sets a custom template directory
	 * 
	 * @param string $templateDir  a custom template directory for this view
	 */
	public static function setTemplateDir($templateDir)
	{
		static::$templateDir = $templateDir;
	}


	/**
	 * Adds a partial view as a variable to the parent template
	 * 
	 * @param string $name  the variable name to use
	 * @param string $template  the template file for the partial
	 * @param array $variables (optional)  the template variables to use within the partial
	 */
	public function partial($name, $template = '', array $variables = array())
	{
		if (empty($template)) $template = $name;
		$this->variables[$name] = (new View($template, $variables))->render();

		return $this;
	}


	/**
	 * Sets a variable for the template
	 * 
	 * @param string $name  the variable name to use
	 * @param mixed $value  the template file for the partial
	 */
	public function set($name, $value)
	{
		$this->variables[$name] = $value;
	}


	/**
	 * Merges the variable array with another given one
	 * 
	 * @param array $values  the template variables array to merge
	 */
	public function mergeVariables(array $values)
	{
		$this->variables = array_merge($this->variables, $values);
	}


	/**
	 * Retrieve the template directory
	 * 
	 * @return the full path to the template directory
	 */
	private function getTemplatePath()
	{
		return (empty(self::$templateDir)
			? PUB
			: rtrim(self::$templateDir, '/') . DS
		);
	}


	public function render()
	{
		// start collecting the output
		ob_start();

		// make the variables available in the template
		extract($this->variables);
		
		// include the theme functions file, if any
		if (is_readable($theme_functions = SYS . 'theme_functions.php')) require_once $theme_functions;

		// require the actual template
		require $this->getTemplatePath() . $this->template . EXT;

		// returb the collected output
		return ob_get_clean();
	}
}
