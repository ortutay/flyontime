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
	
}
?>