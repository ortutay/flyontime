<?php
class LinesController extends AppController {
	var $name = 'Lines';
	var $components = array('Cookie', 'Disambiguate');
	
	var $cookie_expires = 86400; // 1 days
	
	function beforeFilter()
	{
		$this->Cookie->name = 'airportlines';
		$this->Cookie->time =  $this->cookie_expires;
		$this->Cookie->path = '/'; 
		$this->Cookie->domain = '.flyontime.us';   
		$this->Cookie->secure = false;
		$this->Cookie->key = Configure::read('Security.salt');
	}
	
	function index()
	{
		
	}
	
	function security_mobile($Mode = '')
	{
		$this->layout = 'mobile';
		
		$this->security($Mode);
	}
	
	function security($Mode = '')
	{
		$this->Enum =& ClassRegistry::init('Enum');
		
		//detect mobile phone
		if(
			(
				!(stripos($_SERVER['HTTP_USER_AGENT'], 'iphone') === FALSE) ||		//is iphone or
				!(stripos($_SERVER['HTTP_USER_AGENT'], 'blackberry') === FALSE)		//is blackberry
			) &&
			stripos($this->params['url']['url'], 'm/lines/security') === FALSE //is not /m
		)
		{
			$this->redirect('/m/lines/security/'.$Mode);
		}
		
		//get params
		$airport = '';
		if(isset($this->params['url']['airport']))
			$airport = $this->params['url']['airport'];
			
		$in_js = '';
		if(isset($this->params['url']['in']))
			$in_js = $this->params['url']['in'];
		
		//continue getting data
		if($Mode == '')
		{
			$airports_list = array();
			$delays = array();
			
			//check if already entered line
			if($this->IsInLine())
			{
				$this->redirect('/m/lines/security/out');
			}
			
			$airports_used = $this->Disambiguate->GetAirportsUsed();
			
			$airports_list = $this->Disambiguate->GetAirports($airport, $airports_used);

			if(count($airports_list) == 1)
			{
				$airport = $airports_list[0]['Enum']['code'];
				
				$delays = $this->GetSecurityDelays($airport);
			}
			
			//set view vars
			$this->set('Mode', $Mode);
			$this->set('Airport', $airport);
			$this->set('Airports', $airports_list);
			$this->set('Delays', $delays);
		}
		elseif($Mode == 'in')
		{
			//check if already entered line
			if($this->IsInLine())
			{
				$this->redirect('/m/lines/security/out');
			}
			
			//user did not enter line -- look up airport if one is specified
			$airports_list = array();
			
			if($airport != '')
			{
				$airports_used = $this->Disambiguate->GetAirportsUsed();
			
				$airports_list = $this->Disambiguate->GetAirports($airport, $airports_used);

				if(count($airports_list) == 1)
				{
					$airport_name = $airports_list[0]['Enum']['description'];
					$userhash = $this->CreateUserHash();
					$airport = $airports_list[0]['Enum']['code'];
					$recid = $this->CreateNewLineEntry($userhash, $airport);
					
					$this->Cookie->write('in', 'in', true, $this->cookie_expires);
					$this->Cookie->write('airport', $airport, true, $this->cookie_expires);
					$this->Cookie->write('airport_name', $airport_name, true, $this->cookie_expires);
					$this->Cookie->write('userhash', $userhash, true, $this->cookie_expires);
					$this->Cookie->write('recid', $recid, true, $this->cookie_expires);
					$this->Cookie->write('in_js', $in_js, true, $this->cookie_expires);
					
					$this->redirect('/m/lines/security/out');
				}
			}
			
			//set view vars
			$this->set('Mode', $Mode);
			$this->set('Airport', $airport);
			$this->set('Airports', $airports_list);
		}
		elseif($Mode == 'out')
		{
			//check if already entered line
			if(!$this->IsInLine())
			{
				$this->redirect('/m/lines/security');
			}
			
			$line = $this->GetLineFromCookie();
			
			if(count($line) != 1)
			{
				$this->ResetCookies();
				$this->redirect('/m/lines/security');
			}
			
			if($airport != '' && $airport == $line['Line']['airportcode'])
			{
				$line = $this->ExitLine($line);
				$this->ResetCookies();
			}
			
			//set view vars
			$this->set('Mode', $Mode);
			$this->set('Airport', $line['Line']['airportcode']);
			$this->set('Diff', $line['Line']['diff']);
			$this->set('In_js', $this->Cookie->read('in_js'));
		}
	}
	
	private function IsInLine()
	{
		return (
			$this->Cookie->read('in') != null &&
			$this->Cookie->read('airport') != null &&
			$this->Cookie->read('airport_name') != null &&
			$this->Cookie->read('userhash') != null &&
			$this->Cookie->read('recid') != null &&
			$this->Cookie->read('in_js') != null
		);
	}
	
	private function GetLineFromCookie()
	{
		if($this->Cookie->read('recid') > 0 && $this->Cookie->read('userhash') != '' && $this->Cookie->read('airport') != '')
		{
			$line = $this->Line->findById($this->Cookie->read('recid'));
			
			if(
				$this->Cookie->read('userhash') == $line['Line']['userhash'] &&
				$this->Cookie->read('airport') == $line['Line']['airportcode']
			)
			{
				return $line;
			}
		}
		
		return array();
	}
	
	private function ResetCookies()
	{
		$this->Cookie->del('in');
		$this->Cookie->del('airport');
		$this->Cookie->del('airport_name');
		$this->Cookie->del('userhash');
		$this->Cookie->del('recid');
		$this->Cookie->del('in_js');
	}
	
	private function CreateUserHash($username = '')
	{
		$salt = Configure::read('Security.salt');
		
		$cleartext = $username;
		
		if($cleartext == '')
		{
			$cleartext = rand().'-'.time();	
		}
		
		return base64_encode(crypt($cleartext, $salt));
	}
	
	private function CreateNewLineEntry($userhash, $airport)
	{
		//set timezone
		$timezone = $this->GetAirportTimeZone($airport);
		$timezone_old = date_default_timezone_get();
		if($timezone != '')
			date_default_timezone_set($timezone);
		
		$this->Line->deleteAll(
			array(
				'Line.linetype' => 'security',
				'Line.userhash' => $userhash,
				'Line.out' => '0000-00-00 00:00:00'
			),
			false
		);
		
		$now = time();
		
		$this->Line->create();
		$result = $this->Line->save(
			array(
				'Line' => array(
					'userhash' => $userhash,
					'linetype' => 'security',
					'airportcode' => $airport,
					'airlinecode' => '',
					'linename' => '',
					'in' => date('Y-m-d H:i:s', $now),
					'out' => '0000-00-00 00:00:00',
					'diff' => 0,
					'inyear' => date('Y', $now),
					'inmonth' => date('n', $now),
					'indayofmonth' => date('j', $now),
					'indayofweek' => date('N', $now),
					'intimeblk15' => $this->GetTimeBlk($now, 15),
					'intimeblk30' => $this->GetTimeBlk($now, 30),
					'intimeblk60' => $this->GetTimeBlk($now, 60),
					'source' => 'web',
					'useragent' => $_SERVER['HTTP_USER_AGENT'],
					'timezone' => $timezone
				)
			)
		);
		
		//restore timezone
		date_default_timezone_set($timezone_old);
		
		return $this->Line->id;
	}
	
	private function GetTimeBlk($now, $div)
	{
		$min1 = floor((date('i', $now)/$div))*$div;
		if($min1 == 0)
			$min1 = '00';
		$min2 = floor((date('i', $now+(60*$div))/$div))*$div;
		if($min2 == 0)
			$min2 = '00';
		$hour1 = date('H', $now);
		$hour2 = date('H', $now+(60*$div));
		
		return $hour1.$min1.'-'.$hour2.$min2;
	}
	
	private function ExitLine($line)
	{
		//set timezone
		$timezone = $line['Line']['timezone'];
		$timezone_old = date_default_timezone_get();
		if($timezone != '')
			date_default_timezone_set($timezone);
		
		$now = time();
		
		$line['Line']['out'] = date('Y-m-d H:i:s', $now);
		
		$line['Line']['diff'] = $now - strtotime($line['Line']['in']);
		
		$this->Line->save($line);
		
		//restore timezone
		date_default_timezone_set($timezone_old);
		
		return $line;
	}
	
	private function GetAirportTimeZone($code)
	{
		$result = $this->Enum->find('first',
			array(
				'conditions' => array(
					'Enum.category' => 'AIRPORTS_TIMEZONE',
					'Enum.code' => $code
				)
			)
		);
		
		return $result['Enum']['description'];
	}
	
	private function GetSecurityDelays($airport)
	{
		//set timezone
		$timezone = $this->GetAirportTimeZone($airport);
		$timezone_old = date_default_timezone_get();
		if($timezone != '')
			date_default_timezone_set($timezone);
		
		$now = time();
		
		$start = $now - (30*60); //30 minutes before now
		$end = $now + (120*60);  //2 hours after now

		$years = array();
		$years[] = date('Y', $start);
		$years[] = date('Y', $end);
		
		$months = array();
		$months[] = date('n', $start);
		$months[] = date('n', $end);
		
		$daysofweek = array();
		$daysofweek[] = date('N', $start);
		$daysofweek[] = date('N', $end);
		
		//full search
		$delays = $this->Line->find('all',
			array(
				'fields' => array(
					'Line.intimeblk30',
					'AVG(Line.diff) as AvgDiff',
					'COUNT(Line.diff) as NumEntries'
				),
				'conditions' => array(
					'Line.linetype' => 'security',
					'Line.airportcode' => $airport,
					'Line.inyear' => $years,
					'Line.inmonth' => $months,
					'Line.indayofweek' => $daysofweek,
					'Line.in >=' => date('Y-m-d H:i:s', $start), 
					'Line.in <=' => date('Y-m-d H:i:s', $end),
				),
				'group' => array(
					'Line.intimeblk30'
				),
				'order' => array(
					'Line.intimeblk30 ASC'
				)
			)
		);
		
		//full search minus in
		if(count($delays) == 0)
		{
			$delays = $this->Line->find('all',
				array(
					'fields' => array(
						'Line.intimeblk30',
						'AVG(Line.diff) as AvgDiff',
						'COUNT(Line.diff) as NumEntries'
					),
					'conditions' => array(
						'Line.linetype' => 'security',
						'Line.airportcode' => $airport,
						'Line.inyear' => $years,
						'Line.inmonth' => $months,
						'Line.indayofweek' => $daysofweek
					),
					'group' => array(
						'Line.intimeblk30'
					),
					'order' => array(
						'Line.intimeblk30 ASC'
					)
				)
			);
		}
		
		//full search minus in, dayofweek
		if(count($delays) == 0)
		{
			$delays = $this->Line->find('all',
				array(
					'fields' => array(
						'Line.intimeblk30',
						'AVG(Line.diff) as AvgDiff',
						'COUNT(Line.diff) as NumEntries'
					),
					'conditions' => array(
						'Line.linetype' => 'security',
						'Line.airportcode' => $airport,
						'Line.inyear' => $years,
						'Line.inmonth' => $months
					),
					'group' => array(
						'Line.intimeblk30'
					),
					'order' => array(
						'Line.intimeblk30 ASC'
					)
				)
			);
		}
		
		//full search minus in, dayofweek, month
		if(count($delays) == 0)
		{
			$delays = $this->Line->find('all',
				array(
					'fields' => array(
						'Line.intimeblk30',
						'AVG(Line.diff) as AvgDiff',
						'COUNT(Line.diff) as NumEntries'
					),
					'conditions' => array(
						'Line.linetype' => 'security',
						'Line.airportcode' => $airport,
						'Line.inyear' => $years
					),
					'group' => array(
						'Line.intimeblk30'
					),
					'order' => array(
						'Line.intimeblk30 ASC'
					)
				)
			);
		}
		
		//full search minus in, dayofweek, month, year
		if(count($delays) == 0)
		{
			$delays = $this->Line->find('all',
				array(
					'fields' => array(
						'Line.intimeblk30',
						'AVG(Line.diff) as AvgDiff',
						'COUNT(Line.diff) as NumEntries'
					),
					'conditions' => array(
						'Line.linetype' => 'security',
						'Line.airportcode' => $airport
					),
					'group' => array(
						'Line.intimeblk30'
					),
					'order' => array(
						'Line.intimeblk30 ASC'
					)
				)
			);
		}
		
		//restore timezone
		date_default_timezone_set($timezone_old);
		
		return $delays;
	}
}
?>