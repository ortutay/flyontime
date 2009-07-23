<?php
class HomeController extends AppController {
	var $name = 'Home';
	var $uses = array();
	var $components = array('Mobile');
	
	function index() {
		
		//detect mobile phone
		if(
			$this->Mobile->IsMobileDevice() &&
			!$this->Mobile->IsNoMobileCookieSet()
		)
			$this->redirect('/m/lines/security/');
			
		//continue as normal
		
		$this->pageTitle = 'FlyOnTime.us';
		$this->layout = 'blank';
		
		$this->Enum =& ClassRegistry::init('Enum');
		$this->Ontime =& ClassRegistry::init('Ontime');

		if (($top_routes = Cache::read('home_top_routes', 'long')) === false) {
		$top_routes = $this->Ontime->find('all',
			array(
				'fields' => array(
					'origin',
					'dest',
					'count',
					'pct_ontime',
					'delay_median',
					'delay_85thpctile',
				),
				'conditions' => array(
					'origin != ""',
					'dest != ""',
					'carrier' => '', 'flightnum' => 0, 'dayofweek' => 0, 'hour' => '', 'holiday' => '', 'condition' => 'all'
				),
				'order' => array(
					'count DESC'
				),
				'limit' => 15
			)
		);
		Cache::write('home_top_routes', $top_routes, 'long');
		}
		$this->set('TopRoutes', $top_routes);
		
		
		if (($airlines = Cache::read('home_airlines', 'long')) === false) {
		
		$airlines_used = $this->Ontime->find('all',
			array(
				'fields' => array(
					'carrier'
				),
				'group' => array(
					'carrier'
				)
			)
		);
		
		$airlines_used_arr = array();
		
		foreach($airlines_used as $airline)
		{
			$airlines_used_arr[] = $airline['Ontime']['carrier'];
		}
		
		$airlines = $this->Enum->find('all',
			array(
				'conditions' => array(
					'Enum.category' => 'UNIQUE_CARRIERS',
					'Enum.code' => $airlines_used_arr
				),
				'order' => array(
					'Enum.description'
				)
			)
		);
		
		Cache::write('home_airlines', $airlines, 'long');
		}
		
		$this->set('Airlines', $airlines);
	}
	
}
?>
