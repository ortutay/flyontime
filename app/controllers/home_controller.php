<?php
class HomeController extends AppController {
	var $name = 'Home';
	var $uses = array();
	
	function index() {
		$this->pageTitle = 'FlyOnTime.us';
		$this->layout = 'blank';
		
		$this->Enum =& ClassRegistry::init('Enum');
		
		$airlines = $this->Enum->find('all',
			array(
				'conditions' => array(
					'Enum.category' => 'UNIQUE_CARRIERS',
				),
				'order' => array(
					'Enum.description'
				)
			)
		);
		
		$this->set('Airlines', $airlines);
	}
	
}
?>