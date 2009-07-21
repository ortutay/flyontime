<?php
class DisambiguateController extends AppController {
	var $name = 'Disambiguate';
	var $uses = array();
	var $components = array('Disambiguate');
	
	function index()
	{
		$this->redirect('/');
	}
	
	//entry point for /m/disambiguate/airports
	function airports_mobile()
	{
		$this->layout = 'mobile';
		
		$this->airports();
	}
	
	function airports()
	{
		$this->Enum =& ClassRegistry::init('Enum');

		//get params
		$basepath = '';
		if(isset($this->params['url']['basepath']))
			$basepath = $this->params['url']['basepath'];
		
		$from = '';
		if(isset($this->params['url']['from']))
			$from = $this->params['url']['from'];
		
		$to = '';
		if(isset($this->params['url']['to']))
			$to = $this->params['url']['to'];
		
		$day = '';
		if(isset($this->params['url']['day']))
			$day = $this->params['url']['day'];
		
		$time = '';
		if(isset($this->params['url']['time']))
			$time = $this->params['url']['time'];
			
		$noflash = '';
		if(isset($this->params['url']['noflash']))
			$noflash = $this->params['url']['noflash'];
		
		//get data
		if($from != '')
		{
			$airports_used = $this->Disambiguate->GetAirportsUsed();
			
			$airports_from = $this->Disambiguate->GetAirports($from, $airports_used);
			
			if ($to != '') {
				$airports_to = $this->Disambiguate->GetAirports($to, $airports_used);
			} else {
				$airports_to = array(array('Enum'=>array('code'=>'')));
			}
			
			if(count($airports_from) == 1 && count($airports_to) == 1)
			{
				if ($to == '')
				{
					if($basepath == '')
						$url = '/airports/'.$airports_from[0]['Enum']['code'];
					else
						$url = $basepath.$airports_from[0]['Enum']['code'];
				}
				else
				{
					if($basepath == '')
						$url = '/routes/'.$airports_from[0]['Enum']['code'].'/'.$airports_to[0]['Enum']['code'];
					else
						$url = $basepath.$airports_from[0]['Enum']['code'].'/'.$airports_to[0]['Enum']['code'];
				}
				
				if($day != '' || $time != '')
					$url .= '?';
				
				if($day != '')
					$url .= 'day='.$day.'&';
					
				if($time != '')
					$url .= 'time='.$time;
				
				if($noflash == '1')
					$this->redirect($url);
				else
					$this->flash('Loading...', $url, 0);
			}
			else
			{
				$this->set('AirportsFrom', $airports_from);
				$this->set('AirportsTo', $airports_to);
				$this->set('From', $from);
				$this->set('To', $to);
				$this->set('Day', $day);
				$this->set('Time', $time);
				$this->set('Basepath', $basepath);
			}
		}
		else
		{
			$this->redirect('/');
		}
	}
	
	function flights()
	{
		$this->Ontime =& ClassRegistry::init('Ontime');
		
		//get params
		$airline = "";
		if(isset($this->params['url']['airline']))
			$airline = $this->params['url']['airline'];
		
		$flight_num = "";
		if(isset($this->params['url']['flight_num']))
			$flight_num = $this->params['url']['flight_num'];
		
		//get data
		if($airline != '' && $flight_num != '')
		{
			if (isset($this->params['url']['from_to'])) {
				$from_to = split("_", $this->params['url']['from_to']);
				$url = '/flights/'.$airline.'/'.$flight_num.'/'.$from_to[0].'/'.$from_to[1];
				$this->flash('Loading...', $url, 0);
			} else {
				$conditions = array(
					'carrier' => $airline,
					'flightnum' => $flight_num,
					'dayofweek' => '0',
					'hour' => '',
					'holiday' => '',
					'condition' => 'all'
				);
				
				$flights = $this->Ontime->find('all',
					array(
						'conditions' => $conditions,
						'group' => array(
							'origin',
							'dest'
						)
					)
				);
				
				if(count($flights) == 1)
				{
					$url = '/flights/'.$airline.'/'.$flight_num;
					$this->flash('Loading...', $url, 0);
				}
				else
				{
					$this->set('Flights', $flights);
					$this->set('Airline', $airline);
					$this->set('FlightNum', $flight_num);
				}
			}
		}
		elseif($airline != '')
		{
			$url = '/airlines/'.$airline;
			$this->flash('Loading...', $url, 0);
		}
		else
		{
			$this->redirect('/');
		}
	}
	
}
?>