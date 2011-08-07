<?php
class AirportsController extends AppController {
	var $name = 'Airports';
	var $uses = array();
	
	var $components = array('RequestHandler');
	
	function index()
	{
		$this->Enum =& ClassRegistry::init('Enum');
		$this->Ontime =& ClassRegistry::init('Ontime');
		
		$airports_used = $this->Ontime->find('all',
			array(
				'fields' => array(
					'origin'
				),
				'group' => array(
					'origin'
				)
			)
		);
		
		$airports_used_arr = array();
		$airports = array();
		
		foreach($airports_used as $airport)
		{
			if($airport['Ontime']['origin'] != '')
			{
				$airports_used_arr[] = $airport['Ontime']['origin'];
				$airports[$airport['Ontime']['origin']] = array(
					'name' => '',
					'geocode' => '',
					'timezone' => ''
				);
			}
		}
		
		$results = $this->Enum->find('all',
			array(
				'conditions' => array(
					'Enum.category' => array('AIRPORTS', 'AIRPORTS_GEOCODE', 'AIRPORTS_TIMEZONE'),
					'Enum.code' => $airports_used_arr
				),
				'order' => array(
					'Enum.description'
				)
			)
		);
		
		foreach($results as $result)
		{
			if($result['Enum']['code'] != '')
			{
				switch($result['Enum']['category'])
				{
					case 'AIRPORTS':
						$airports[$result['Enum']['code']]['name'] = $result['Enum']['description'];
						break;
					case 'AIRPORTS_GEOCODE':
						$airports[$result['Enum']['code']]['geocode'] = $result['Enum']['description'];
						break;
					case 'AIRPORTS_TIMEZONE':
						$airports[$result['Enum']['code']]['timezone'] = $result['Enum']['description'];
						break;
				}
			}
		}
		
		$this->set('Airports', $airports);
	}
	
	function view()
	{
		$this->Ontime =& ClassRegistry::init('Ontime');
		$this->Enum =& ClassRegistry::init('Enum');
		$this->Weather =& ClassRegistry::init('Weather');
		
		// Load parameters and pass on to the view
		
		$from = '';
		$to = '';
		$carrier = '';
		$flightnum = '0'; # database field is numeric so an empty value became 0
		
		if (isset($this->params['from'])) { $from = $this->params['from']; }
		if (isset($this->params['to'])) { $to = $this->params['to']; }
		if (isset($this->params['carrier'])) { $carrier = $this->params['carrier']; }
		if (isset($this->params['flightnum'])) { $flightnum = $this->params['flightnum']; }
		
		// if this is for a flight and from/to isn't specified in URL, load from and to from the database.
		if ($carrier != '' && $to == '') {
			$flight = $this->Ontime->find('all',
					array('conditions' => array(
							'carrier' => $carrier,
							'flightnum' => $flightnum)));
			$from = $flight[0]["Ontime"]["origin"];
			$to = $flight[0]["Ontime"]["dest"];
		}

		$this->set('From', $from);
		$this->set('To', $to);
		$this->set('Carrier', $carrier);
		$this->set('FlightNum', $flightnum);
		
		// Get the nice names of the airports and carrier

		$from_airport = $this->Enum->find('first',
				array('conditions' => array(
						'Enum.category' => 'AIRPORTS',
						'Enum.code' => $from)));
		$this->set('FromCity', $from_airport['Enum']['description']);
		
		if ($to != '') {
			$to_airport = $this->Enum->find('first',
					array('conditions' => array(
							'Enum.category' => 'AIRPORTS',
							'Enum.code' => $to)));
			$this->set('ToCity', $to_airport['Enum']['description']);
		}
		
		if ($carrier != '') {
			$carrier_rec = $this->Enum->find('first',
					array('conditions' => array(
							'Enum.category' => 'UNIQUE_CARRIERS',
							'Enum.code' => $carrier)));
			$carrier_name = $carrier_rec['Enum']['description'];
			$carrier_name = str_replace("Inc.", "", $carrier_name);
			$this->set('CarrierName', $carrier_name);
		}
		
		$summary = $this->GetSummary($from, $to, $carrier, $flightnum, 'all');
		$this->set('Summary', $summary);
		$this->set('SummaryGoodWeather', $this->GetSummary($from, $to, $carrier, $flightnum, 'origin_any_no'));
		$this->set('SummaryBadWeather', $this->GetSummary($from, $to, $carrier, $flightnum, 'origin_any_yes'));
		$this->set('SummaryDestBadWeather', $this->GetSummary($from, $to, $carrier, $flightnum, 'dest_any_yes'));
		$this->set('SummaryFog', $this->GetSummary($from, $to, $carrier, $flightnum, 'origin_fog_yes'));
		$this->set('SummaryRain', $this->GetSummary($from, $to, $carrier, $flightnum, 'origin_rain_yes'));
		$this->set('SummarySnow', $this->GetSummary($from, $to, $carrier, $flightnum, 'origin_snow_yes'));
		$this->set('SummaryHail', $this->GetSummary($from, $to, $carrier, $flightnum, 'origin_hail_yes'));
		$this->set('SummaryThunder', $this->GetSummary($from, $to, $carrier, $flightnum, 'origin_thunder_yes'));
		$this->set('SummaryTornado', $this->GetSummary($from, $to, $carrier, $flightnum, 'origin_tornado_yes'));

		if ($to == '') {
			$best_dests = $this->GetBestDestinations($from);
			$this->set('BestDestinations', $best_dests);
		} else {
			$best_flights = $this->GetBestFlights($from, $to);
			$this->set('BestFlights', $best_flights);
			
			$best_airlines = $this->GetBestAirlines($from, $to);
			$this->set('BestAirlines', $best_airlines);
			
			$airline_names = $this->GetAirlineNames($best_airlines, array());
			$this->set('AirlineNames', $airline_names);
		}
		
		if ($carrier != '') {
			$flight_other_count = 0;
			$flight_better_than = 0;
			$flight_worse_than = 0;
			foreach ($best_flights as $flight) {
				if ($summary['Ontime']['delay_median'] < $flight['Ontime']['delay_median']) { $flight_better_than++; }
				if ($summary['Ontime']['delay_median'] > $flight['Ontime']['delay_median']) { $flight_worse_than++; }
				$flight_other_count++;
			}
			$flight_other_count--; // don't count this flight
			if ($flight_other_count > 0) {
				$flight_better_than /= $flight_other_count;
				$flight_worse_than /= $flight_other_count;
			}
			if ($flight_other_count == 0) {
				$this->set('FlightComparison', '');
			} else if ($flight_better_than > $flight_worse_than) {
				$this->set('FlightComparison', "better than " . round($flight_better_than*100) . '%');
			} else {
				$this->set('FlightComparison', "worse than " . round($flight_worse_than*100) . '%');
			}
		}

		$this->set('DaysFrom', $this->GetDays($from, $to));
		$this->set('TimesFrom', $this->GetTimes($from, $to));
		$this->set('Holidays', $this->GetHolidays($from, $to));
		
		$WeatherInfo = $this->Weather->find('first', array('conditions' => array('airport' => $from)));
		$this->set('WeatherInfo', $WeatherInfo);
		
		$this->set('curaptdelays', $this->LoadAndCacheXml('faa_airport_status', 'http://fly.faa.gov/flyfaa/xmlAirportStatus.jsp'));
		if ($WeatherInfo['Weather']['station'] != '') {
			$this->set('curobs', $this->LoadAndCacheXml('nws_current_obs_' . $WeatherInfo['Weather']['station'], 'http://www.weather.gov/xml/current_obs/' . $WeatherInfo['Weather']['station']  . '.xml'));
		}
	}
	
	private function GetSummary($from, $to, $carrier, $flightnum, $condition)
	{
		$key = 'airports_GetSummary_'.$from.'_'.$to.'_'.$carrier.'_'.$flightnum.'_'.$condition;
		
		//if (($result = Cache::read($key, 'long')) === false)
		{
			$result = $this->Ontime->find('first',
				array(
					'fields' => array('firstdate', 'lastdate', 'count', 'pct_cancel', 'pct_20mindelay', 'pct_ontime', 'delay_15thpctile', 'delay_median', 'delay_85thpctile'),
					'conditions' => array('origin' => $from, 'dest' => $to, 'condition' => $condition, 'carrier' => $carrier, 'flightnum' => $flightnum, 'dayofweek' => '0', 'hour' => '', 'holiday' => ''),
				)
			);
			
			//Cache::write($key, $result, 'long');
		}
		
		return $result;
	}
	
	private function GetBestDestinations($from)
	{
		$key = 'airports_GetBestDestinations_'.$from;
		
		//if (($result = Cache::read($key, 'long')) === false)
		{
			$result = $this->Ontime->find('all',
				array(
					'fields' => array('dest', 'count', 'pct_cancel', 'pct_20mindelay', 'pct_ontime', 'delay_15thpctile', 'delay_median', 'delay_85thpctile'),
					
					'conditions' => array('origin' => $from, 'dest != ""', 'condition' => 'all', 'dayofweek' => '0', 'hour' => '', 'holiday' => '', 'carrier' => '', flightnum => 0),
					
					'order' => array('count DESC'),
					
					'limit' => 10,
				)
			);
			
			//Cache::write($key, $result, 'long');
		}
		
		return $result;
	}

	private function GetBestFlights($from, $to)
	{
		$key = 'airports_GetBestFlights_'.$from.'_'.$to;
		
		//if (($result = Cache::read($key, 'long')) === false)
		{
			$result = $this->Ontime->find('all',
				array(
					'fields' => array('carrier', 'flightnum', 'count', 'pct_cancel', 'pct_20mindelay', 'pct_ontime', 'delay_15thpctile', 'delay_median', 'delay_85thpctile'),
					
					'conditions' => array('origin' => $from, 'dest' => $to, 'condition' => 'all', 'dayofweek' => '0', 'hour' => '', 'holiday' => '', "carrier != ''"),
					
					'order' => array('delay_median ASC')
				)
			);
			
			//Cache::write($key, $result, 'long');
		}
		
		return $result;
	}
	
	private function GetBestAirlines($from, $to)
	{
		$key = 'airports_GetBestAirlines_'.$from.'_'.$to;
		
		//if (($result = Cache::read($key, 'long')) === false)
		{
			$result = $this->Ontime->find('all',
				array(
					'fields' => array('carrier', 'sum(count) as carrier_count', 'sum(count*pct_ontime) as carrier_ontime'),
					
					'conditions' => array('origin' => $from, 'dest' => $to, 'condition' => 'all', 'dayofweek' => '0', 'hour' => '', 'holiday' => '', "carrier != ''"),
					
					'group' => array('carrier'),
					
					'order' => array('sum(count*pct_ontime)/sum(count) DESC')
				)
			);
			
			//Cache::write($key, $result, 'long');
		}
		
		return $result;
	}
	
	private function GetDays($from, $to)
	{
		$key = 'airports_GetDays_'.$from.'_'.$to;
		
		//if (($result = Cache::read($key, 'long')) === false)
		{
			$result = $this->Ontime->find('all',
				array(
					'fields' => array('dayofweek', 'count', 'pct_cancel', 'pct_20mindelay', 'pct_ontime', 'delay_15thpctile', 'delay_median', 'delay_85thpctile'),
					
					'conditions' => array('origin' => $from, 'dest' => '', 'carrier' => '', 'flightnum' => 0, 'condition' => 'all', 'dayofweek != 0', 'hour' => '', 'holiday' => ''),
					
					'order' => array('dayofweek ASC')
				)
			);
			
			//Cache::write($key, $result, 'long');
		}
		
		return $result;
	}
	
	private function GetTimes($from, $to)
	{
		$key = 'airports_GetTimes_'.$from.'_'.$to;
		
		//if (($result = Cache::read($key, 'long')) === false)
		{
			$result = $this->Ontime->find('all',
				array(
					'fields' => array('hour', 'count', 'pct_cancel', 'pct_20mindelay', 'pct_ontime', 'delay_15thpctile', 'delay_median', 'delay_85thpctile'),
					
					'conditions' => array('origin' => $from, 'dest' => '', 'carrier' => '', 'flightnum' => 0, 'condition' => 'all', 'dayofweek' => 0, 'hour != ""', 'holiday' => ''),
					
					'order' => array('hour ASC')
				)
			);
			
			//Cache::write($key, $result, 'long');
		}
		
		return $result;
	}
	
	private function GetHolidays($from, $to)
	{
		$key = 'airports_GetHolidays_'.$from.'_'.$to;
		
		//if (($result = Cache::read($key, 'long')) === false)
		{
			$result = $this->Ontime->find('all',
				array(
					'fields' => array('holiday', 'count', 'pct_cancel', 'pct_20mindelay', 'pct_ontime', 'delay_15thpctile', 'delay_median', 'delay_85thpctile'),
					
					'conditions' => array('origin' => $from, 'dest' => '', 'carrier' => '', 'flightnum' => 0, 'condition' => 'all', 'dayofweek' => 0, 'hour' => '', 'holiday != ""'),
				)
			);
			
			//Cache::write($key, $result, 'long');
		}
		
		return $result;
	}

	private function GetAirlineNames($airlines1, $airlines2)
	{
		$unique_carriers = array();
		
		foreach($airlines1 as $airline)
		{
			$unique_carriers[] = $airline['Ontime']['carrier'];
		}
		
		foreach($airlines2 as $airline)
		{
			$unique_carriers[] = $airline['Ontime']['carrier'];
		}

		$result = $this->Enum->find('all',
			array(
				'conditions' => array(
					'Enum.category' => 'UNIQUE_CARRIERS',
					'Enum.code' => $unique_carriers
				)
			)
		);
		
		$airline_names = array();
		
		foreach($result as $item)
		{
			$fullname = $item['Enum']['description'];
			$abrev = '';
			
			if($fullname != '')
			{
				$words = explode(' ', $fullname);
				$abrev = $words[0];
				
				if(count($words) > 1)
				{
					if($words[1] != 'Airlines' && $words[1] != 'Air' && $words[1] != 'Inc.')
						$abrev .= ' '.$words[1];
				}
			}
			
			$airline_names[$item['Enum']['code']] = $abrev;
		}
		
		return $airline_names;
	}
	
	private function LoadAndCacheXml($key, $url) {
		if (($xml = Cache::read($key)) === false) {
			$xml = simplexml_load_file($url);
			if($xml == null)
				$xml = new SimpleXMLElement("<AIRPORT_STATUS_INFORMATION></AIRPORT_STATUS_INFORMATION>");
			else
				Cache::write($key, $xml->asXML());
		} else {
			$xml = simplexml_load_string($xml); 
		}
		return $xml;
	}
}
?>
