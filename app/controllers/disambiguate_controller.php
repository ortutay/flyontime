<?php
class DisambiguateController extends AppController {
	var $name = 'Disambiguate';
	var $uses = array();
	
	function index()
	{
		$this->redirect('/');
	}
	
	function airports()
	{
		$this->Enum =& ClassRegistry::init('Enum');
		$this->Log =& ClassRegistry::init('Log');

		//get params
		$from = "";
		if(isset($this->params['url']['from']))
			$from = $this->params['url']['from'];
		
		$to = "";
		if(isset($this->params['url']['to']))
			$to = $this->params['url']['to'];
		
		$day = "";
		if(isset($this->params['url']['day']))
			$day = $this->params['url']['day'];
			
		//get data
		if($from != '' && $to != '')
		{
			$airports_used = null;
			
			if(strlen($from) != 3 || strlen($to) != 3)
			{
				$airports_used = $this->GetAirportsUsed();
			}
			
			$airports_from = $this->GetAirports($from, $airports_used);
			
			$airports_to = $this->GetAirports($to, $airports_used);
			
			if(count($airports_from) == 1 && count($airports_to) == 1)
			{
				$url = '/airports?from='.$airports_from[0]['Enum']['code'].'&to='.$airports_to[0]['Enum']['code'].'&day='.$day;
				$this->redirect($url);
			}
			else
			{
				$this->set('AirportsFrom', $airports_from);
				$this->set('AirportsTo', $airports_to);
				$this->set('Day', $day);
			}
		}
		else
		{
			$this->redirect('/');
		}
	}
	
	function flights()
	{
		$this->Log =& ClassRegistry::init('Log');
		
		//get params
		$airline = "";
		if(isset($this->params['url']['airline']))
			$airline = $this->params['url']['airline'];
		
		$flight_num = "";
		if(isset($this->params['url']['flight_num']))
			$flight_num = $this->params['url']['flight_num'];
		
		$day = "";
		if(isset($this->params['url']['day']))
			$day = $this->params['url']['day'];
		
		$from = "";
		if(isset($this->params['url']['from']))
			$from = $this->params['url']['from'];
		
		$to = "";
		if(isset($this->params['url']['to']))
			$to = $this->params['url']['to'];
			
		//get data
		if($airline != '' && $flight_num != '')
		{
			$conditions = array(
				'Log.UniqueCarrier' => $airline,
				'Log.FlightNum' => $flight_num
			);
			
			if($from != '' && $to != '')
			{
				$conditions['Log.Origin'] = $from;
				$conditions['Log.Dest'] = $to;
			}
			
			$flights = $this->Log->find('all',
				array(
					'conditions' => $conditions,
					'group' => array(
						'Log.Origin',
						'Log.Dest'
					)
				)
			);
			
			if(count($flights) == 1)
			{
				$url = '/flights?airline='.$airline.'&flight_num='.$flight_num.'&day='.$day.'&from='.$from.'&to='.$to;
				
				$this->redirect($url);
			}
			else
			{
				$this->set('Flights', $flights);
				$this->set('Airline', $airline);
				$this->set('FlightNum', $flight_num);
				$this->set('Day', $day);
				$this->set('From', $from);
				$this->set('To', $to);
			}
		}
		elseif($airline != '')
		{
			$this->redirect('/airlines/'.$airline);
		}
		else
		{
			$this->redirect('/');
		}
	}
	
	private function GetAirports($name, $airports_used = null)
	{
		$airports = array();
		
		if(strlen($name) == 3)
		{
			$airports = $this->Enum->find('all',
				array(
					'conditions' => array(
						'Enum.category' => 'AIRPORTS',
						'Enum.code' => strtoupper($name)
					)
				)
			);
		}
		elseif($name != "")
		{
			$keywords = $this->ParseCityName($name);
			
			$like = '';
			foreach($keywords as $keyword)
			{
				$like .= '%'.$keyword;
			}
			$like .= '%';
			
			$conditions = array(
				'Enum.category' => 'AIRPORTS',
				'Enum.description LIKE' => $like
			);
			
			if($airports_used != null)
			{
				$conditions['Enum.code'] = $airports_used;
			}
			
			$airports = $this->Enum->find('all',
				array(
					'conditions' => $conditions
				)
			);
		}
		
		return $airports;
	}
	
	private function ParseCityName($name)
	{
		$parts = explode(' ', $name);
		
		$keywords = array();
		
		foreach($parts as $part)
		{
			$keyword = preg_replace("/[^a-zA-Z]/", '', $part);
			
			if($keyword != '')
				$keywords[] = $keyword;
		}
		
		return $keywords;
	}
	
	private function GetAirportsUsed()
	{
		$airports_used = $this->Log->find('all',
			array(
				'fields' => array(
					'Log.Origin'
				),
				'group' => array(
					'Log.Origin'
				)
			)
		);
		
		$airports_used_arr = array();
		
		foreach($airports_used as $airport)
		{
			$airports_used_arr[] = $airport['Log']['Origin'];
		}
		
		return $airports_used_arr;
	}
	
}
?>