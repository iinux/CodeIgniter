<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2017, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2017, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {

	/**
	 * Reference to the CI singleton
	 *
	 * @var	object
	 */
	private static $instance;

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		self::$instance =& $this;

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');
		$this->load->initialize();
		log_message('info', 'Controller Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Get the CI singleton
	 *
	 * @static
	 * @return	object
	 */
	public static function &get_instance()
	{
		return self::$instance;
	}

	/**
	 * @var Smarty
	 */
	protected $smarty = null;

	/**
	 * @return $this
	 */
	public function smartyLoad()
	{
		$this->load->library('CI_Smarty');
		$this->smarty = $this->ci_smarty;
		
		return $this;
	}

	/**
	 * @param string $name
	 * @param string $value
	 * @return $this
	 */
	public function smartyAssign($name, $value)
	{
		if (empty($this->smarty)) {
			$this->smartyLoad();
		}
		
		$this->smarty->assign($name, $value);
		
		return $this;
	}

	/**
	 * @param string $fileName
	 * @param string $extensionName
	 * @return $this
	 */
	public function smartyDisplay($fileName, $extensionName = 'tpl')
	{
		if (empty($this->smarty)) {
			$this->smartyLoad();
		}
		
		$this->smarty->display("$fileName.$extensionName");
		
		return $this;
	}

	/**
	 * @param $name
	 * @param null $param
	 * @return mixed
	 */
	public function loadService($name, $param = null)
	{
		load_class('Service', 'core');
		
		static $_services= array();

		// Does the class exist? If so, we're done...
		if (isset($_services[$name]))
		{
			return $_services[$name];
		}
		
		require_once(APPPATH.'services/'.$name.'.php');
		$_services[$name] = isset($param) ? new $name($param) : new $name();

		$_services[$name]->setController($this);
		
		return $_services[$name];
	}

	/**
	 * @return CI_Session
	 */
	protected function session()
	{
		static $session = null;
		if (empty($session)) {
			$this->load->library('session');
			$session = $this->session;
		}
		return $session;
	}

	protected function outputJson($arr)
	{
		$this->output
			  ->set_content_type('application/json')
			  ->set_output(json_encode($arr));
	}
}
