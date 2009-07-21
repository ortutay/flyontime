<?php
class LinesController extends AppController {
	var $name = 'Lines';
	var $components = array('Cookie', 'Mobile');
	
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
	
	//================================
	//Mobile Entry Points
	//================================
	
	//entry point for /m/lines/security
	function security_mobile()
	{
		$this->layout = 'mobile';
		
		if($this->IsInLine())
		{
			$airport = $this->Cookie->read('airport');
			
			if($airport != '')
			{
				$this->redirect('/m/lines/security/wait/'.$airport);
			}
			else
			{
				$this->ResetCookies();
			}
		}
	}
	
	//entry point for /m/lines/security/:airport
	function security_mobile_search($airport)
	{
		$this->layout = 'mobile';
		
		if($this->IsInLine())
		{
			$this->redirect('/m/lines/security/wait/'.$airport);
		}
		else
		{
			$this->security_search($airport);
		}
	}
	
	//entry point for /m/lines/security/in/:airport
	function security_mobile_in($airport)
	{
		$this->Enum =& ClassRegistry::init('Enum');
		
		$this->layout = 'mobile';
		
		if($this->IsInLine())
		{
			$this->redirect('/m/lines/security/wait/'.$airport);
		}
		
		//look up airport
		$airport_name = $this->GetAirportName($airport);
		$timezone = $this->GetAirportTimeZone($airport);
		
		if($airport_name == '' || $timezone == '')
		{
			$this->redirect('/m/lines/security');
		}
		
		//set timezone
		$timezone_old = date_default_timezone_get();
		date_default_timezone_set($timezone);
		
		//user is entering line now
		$userhash = $this->CreateUserHash();
		$recid = $this->CreateNewLineEntry($userhash, $airport, $timezone);
		
		//set cookies
		$this->Cookie->write('in', 'in', false, $this->cookie_expires);
		$this->Cookie->write('airport', $airport, false, $this->cookie_expires);
		$this->Cookie->write('userhash', $userhash, false, $this->cookie_expires);
		$this->Cookie->write('recid', $recid, false, $this->cookie_expires);
		
		//set view vars
		$this->set('Airport', $airport);
		
		//restore timezone
		date_default_timezone_set($timezone_old);
	}
	
	//entry point for /m/lines/security/wait/:airport
	function security_mobile_wait($airport)
	{
		$this->Enum =& ClassRegistry::init('Enum');
		
		$this->layout = 'mobile';
		
		//get params
		$in_js = '';
		if(isset($this->params['url']['in_js']))
		{
			$in_js = $this->params['url']['in_js'];
			$this->Cookie->write('in_js', $in_js, false, $this->cookie_expires);
		}
		elseif($this->Cookie->read('in_js') != null)
		{
			$in_js = $this->Cookie->read('in_js');
		}
		
		//check if already entered line
		if(!$this->IsInLine())
		{
			$this->redirect('/m/lines/security');
		}
		
		$line = $this->GetLineFromCookie();
		
		if(count($line) != 1 || $airport == '' || $airport != $line['Line']['airportcode'] || $line['Line']['timezone'] == '')
		{
			$this->ResetCookies();
			$this->redirect('/m/lines/security');
		}
		
		//set time zone
		$timezone = $line['Line']['timezone'];
		$timezone_old = date_default_timezone_get();
		date_default_timezone_set($timezone);
		
		//set view vars
		$this->set('Airport', $line['Line']['airportcode']);
		$this->set('In_js', $in_js);
		
		//restore timezone
		date_default_timezone_set($timezone_old);
	}
	
	//entry point for /m/lines/security/out/:airport
	function security_mobile_out($airport)
	{
		$this->Enum =& ClassRegistry::init('Enum');
		
		$this->layout = 'mobile';
		
		//check if already entered line
		if(!$this->IsInLine())
		{
			$this->redirect('/m/lines/security');
		}
		
		$line = $this->GetLineFromCookie();
		
		if(count($line) != 1 || $airport == '' || $airport != $line['Line']['airportcode'] || $line['Line']['timezone'] == '')
		{
			$this->ResetCookies();
			$this->redirect('/m/lines/security');
		}
		
		//set time zone
		$timezone = $line['Line']['timezone'];
		$timezone_old = date_default_timezone_get();
		date_default_timezone_set($timezone);
		
		//exit line now
		$line = $this->ExitLine($line);
		$this->ResetCookies();
		
		//set view vars
		$this->set('Airport', $line['Line']['airportcode']);
		$this->set('Diff', $line['Line']['diff']);
		
		//restore timezone
		date_default_timezone_set($timezone_old);
	}
	
	//================================
	//Normal Entry Points
	//================================
	
	//entry point for /m/lines
	function index()
	{
		
	}
	
	//entry point for /lines/security
	function security()
	{
		//detect mobile phone
		if(
			$this->Mobile->IsMobileDevice() &&
			stripos($this->params['url']['url'], 'm/lines/security') === FALSE //is not already in /m/lines/security
		)
			$this->redirect('/m/lines/security/');
	}
	
	//entry point for /lines/security/:airport
	function security_search($airport)
	{
		//detect mobile phone
		if(
			$this->Mobile->IsMobileDevice() &&
			stripos($this->params['url']['url'], 'm/lines/security') === FALSE //is not already in /m/lines/security
		)
			$this->redirect('/m/lines/security/');
		
		$this->Enum =& ClassRegistry::init('Enum');
		
		//get params
		$day = '';
		if(isset($this->params['url']['day']))
			$day = $this->params['url']['day'];
		
		$time = '';
		if(isset($this->params['url']['time']))
			$time = $this->params['url']['time'];
		
		
		$this->set('Airport', $airport);
		$this->set('Day', $day);
		$this->set('Time', $time);
		
		//set timezone
		$timezone = $this->GetAirportTimeZone($airport);
		$timezone_old = date_default_timezone_get();
		if($timezone != '')
			date_default_timezone_set($timezone);
		
		//Get Airport Name/City
		$city = $this->GetAirportName($airport);
		$this->set('City', $city);
		
		//Get Real-Time Delay
		$realtime = $this->GetRealTimeDelay($airport);
		$this->set('Realtime', $realtime);
		
		//Get average over days
		$days = $this->GetDays($airport, $day, $time);
		$this->set('Days', $days);
		
		//Get average over times
		$times = $this->GetTimes($airport, $day, $time);
		$this->set('Times', $times);
		
		//restore timezone
		date_default_timezone_set($timezone_old);
	}
	
	//================================
	//Helper Functions
	//================================
	
	private function IsInLine()
	{
		return (
			$this->Cookie->read('in') != null &&
			$this->Cookie->read('airport') != null &&
			$this->Cookie->read('userhash') != null &&
			$this->Cookie->read('recid') != null
		);
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
	
	private function GetAirportName($code)
	{
		$result = $this->Enum->find('first',
			array(
				'conditions' => array(
					'Enum.category' => 'AIRPORTS',
					'Enum.code' => $code
				)
			)
		);
		
		return $result['Enum']['description'];
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
	
	private function CreateNewLineEntry($userhash, $airport, $timezone)
	{
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

		return $this->Line->id;
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
	
	private function ExitLine($line)
	{
		$now = time();
		
		$line['Line']['out'] = date('Y-m-d H:i:s', $now);
		
		$line['Line']['diff'] = $now - strtotime($line['Line']['in']);
		
		$this->Line->save($line);
		
		return $line;
	}
	
	private function GetRealTimeDelay($airport)
	{
		$now = time();
		
		$start = $now - (30*60); //30 minutes before now
		
		//full search
		$delay = $this->Line->find('all',
			array(
				'fields' => array(
					'AVG(Line.diff) as AvgDiff',
					'STD(Line.diff) as StdDiff',
					'COUNT(Line.diff) as NumEntries'
				),
				'conditions' => array(
					'Line.linetype' => 'security',
					'Line.airportcode' => $airport,
					'Line.in >=' => date('Y-m-d H:i:s', $start)
				)
			)
		);

		return $delay;
	}
	
	private function GetDays($airport, $day, $time)
	{
		$conditions = array(
			'Line.airportcode' => $airport
		);
		
		if($day != '')
		{
			$conditions['Line.indayofweek'] = $day;
		}
		
		if($time != '')
		{
			$blks = array();
			
			$blks[] = $this->GetTimeBlk60FromIntTime($time, -1);
			$blks[] = $this->GetTimeBlk60FromIntTime($time, 0);
			$blks[] = $this->GetTimeBlk60FromIntTime($time, 1);
			$blks[] = $this->GetTimeBlk60FromIntTime($time, 2);
			$blks[] = $this->GetTimeBlk60FromIntTime($time, 3);
			
			$conditions['Line.intimeblk60'] = $blks;
		}
		
		$days = $this->Line->find('all',
			array(
				'fields' => array(
					'Line.indayofweek',
					'AVG(Line.diff) as AvgDiff'
				),
				'conditions' => $conditions,
				'group' => array(
					'Line.indayofweek'
				),
				'order' => array(
					'Line.indayofweek ASC'
				)
			)
		);
		
		return $days;
	}
	
	private function GetTimes($airport, $day, $time)
	{
		$conditions = array(
			'Line.airportcode' => $airport
		);
		
		if($day != '')
		{
			$conditions['Line.indayofweek'] = $day;
		}
		
		if($time != '')
		{
			$blks = array();
			
			$blks[] = $this->GetTimeBlk60FromIntTime($time, -1);
			$blks[] = $this->GetTimeBlk60FromIntTime($time, 0);
			$blks[] = $this->GetTimeBlk60FromIntTime($time, 1);
			$blks[] = $this->GetTimeBlk60FromIntTime($time, 2);
			$blks[] = $this->GetTimeBlk60FromIntTime($time, 3);
			
			$conditions['Line.intimeblk60'] = $blks;
		}
		
		$times = $this->Line->find('all',
			array(
				'fields' => array(
					'Line.intimeblk60',
					'AVG(Line.diff) as AvgDiff'
				),
				'conditions' => $conditions,
				'group' => array(
					'Line.intimeblk60'
				),
				'order' => array(
					'Line.intimeblk60 ASC'
				)
			)
		);
		
		return $times;
	}
	
	private function GetTimeBlk60FromIntTime($time, $change = 0)
	{
		$change = ($change % 24);
		
		$start = $time + $change;
		$end = $start + 1;
		
		if($start < 0)
			$start += 24;
		
		$start = ($start % 24);
		
		if($end < 0)
			$end += 24;
		
		$end = ($end % 24);
		
		if($start < 10)
			$start = '0'.$start;
		
		if($end < 10)
			$end = '0'.$end;
		
		return $start.'00-'.$end.'00';
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
}
?>