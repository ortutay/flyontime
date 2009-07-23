<?php
class MobileComponent extends Object {

	//==========START BOILER PLATE CODE================

	//called before Controller::beforeFilter()
	function initialize(&$controller, $settings = array()) {
		// saving the controller reference for later use
		$this->controller =& $controller;
		
		//$this->Enum =& ClassRegistry::init('Enum');
		//$this->Ontime =& ClassRegistry::init('Ontime');
	}

	//called after Controller::beforeFilter()
	function startup(&$controller) {
	}

	//called after Controller::beforeRender()
	function beforeRender(&$controller) {
	}

	//called after Controller::render()
	function shutdown(&$controller) {
	}

	//called before Controller::redirect()
	function beforeRedirect(&$controller, $url, $status=null, $exit=true) {
	}

	function redirectSomewhere($value) {
		// utilizing a controller method
		$this->controller->redirect($value);
	}
	//==========END BOILER PLATE CODE================


	public function IsMobileDevice()
	{
		if(
			(
				!(stripos($_SERVER['HTTP_USER_AGENT'], 'iphone') === FALSE) ||		//is iphone or
				!(stripos($_SERVER['HTTP_USER_AGENT'], 'blackberry') === FALSE)		//is blackberry
			)
		)
		{
			return true;
		}
		
		return false;
	}
	
	public function SetNoMobileCookie()
	{
		setcookie(
			'nomobile',
			'1',
			time() + 3600, //expire in 1 hour
			'/',
			'.flyontime.us'
		);
	}
	
	public function IsNoMobileCookieSet()
	{
		if(isset($_COOKIE['nomobile']) && $_COOKIE['nomobile'] == '1')
			return true;
		
		return false;
	}
	
	public function ClearNoMobileCookie()
	{
		setcookie(
			'nomobile',
			'',
			time() - 3600, //a time in the past
			'/',
			'.flyontime.us'
		);
	}
	
}
?>