<?php
class NomobileController extends AppController {
	var $name = 'Nomobile';
	var $uses = array();
	var $components = array('Mobile');
	
	function index() {
		
		$this->Mobile->SetNoMobileCookie();
		$this->redirect('/');
		
	}
	
}
?>