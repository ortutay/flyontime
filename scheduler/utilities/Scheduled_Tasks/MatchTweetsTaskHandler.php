<?php
	
	class MatchTweetsTaskHandler extends ScheduledTaskHandler
	{	
		function __construct()
		{
			
		}
		
		public function Execute($aoScheduledTask)
		{
			$params = parent::UnserializeParams($aoScheduledTask->GetValue("Params"));
			
			$tweets = new TweetCollection(50);
			$tweets->GetUsers();
			
			foreach($tweets->Items() as $tweet)
			{
				if($tweet->GetValue("numtweets") > 1)
				{
					$username = $tweet->GetValue("username");
					$this->MatchTweetsForUser($username);
				}
			}
			
			$this->EndTask($aoScheduledTask, $params, 1);
		}
		
		private function EndTask($aoScheduledTask, $params, $minutes = 10)
		{
			$aoScheduledTask->SetValue("Params", parent::SerializeParams($params));
			$aoScheduledTask->AdvanceExpirationBy(60*$minutes);
		}
		
		private function MatchTweetsForUser($username)
		{
			$tweets = new TweetCollection();
			
			$tweets->ReserveUnmatchedUserMsgs($username);
			
			$in_time = 0;
			$in_airport = "";
			$in_i = -1;
			$matches = array();
			
			$i = 0;
			foreach($tweets->Items() as $tweet)
			{
				$msg = $tweet->GetValue("msg");
				
				$result = $this->ParseMsg($msg);
				
				if($result !== false)
				{
					if($result["direction"] == "in")
					{
						$in_time = $tweet->GetValue("created");
						$in_airport = $result["airport"];
						$in_i = $i;
					}
					elseif( $in_airport != "" && $result["direction"] == "out" && ($result["airport"] == $in_airport || $result["airport"] == "") )
					{
						$out_time = $tweet->GetValue("created");
						$diff = ($out_time - $in_time);
						
						if($diff <= (3*60*60)) //diff is less than 3 hours
						{
							$this->CreateEntry($in_airport, $in_time, $out_time, $username);
							
							$matches[$in_i] = 1;
							$matches[$i] = 1;
						}
					}
					else
					{
						$in_time = 0;
						$in_airport = "";
					}
				}
				
				$i++;
			}
			
			$i = 0;
			foreach($tweets->Items() as $tweet)
			{
				if(!isset($matches[$i]))
				{
					$tweet->SetValue("matched", 0);
					$tweet->Save();
				}
				
				$i++;
			}
		}
		
		private function ParseMsg($msg)
		{
			$regexp = "#airportsecurity ([a-zA-Z]{3}) (in|out)";
		
			if(preg_match_all("/$regexp/siU", $msg, $matches))
			{
				return array(
					"airport" => strtoupper($matches[1][0]),
					"direction" => strtolower($matches[2][0])
				);
			}
			
			$regexp = "#airportsecurity (in|out) ([a-zA-Z]{3})";
		
			if(preg_match_all("/$regexp/siU", $msg, $matches))
			{
				return array(
					"airport" => strtoupper($matches[2][0]),
					"direction" => strtolower($matches[1][0])
				);
			}
			
			$regexp = "#airportsecurity (out)";
		
			if(preg_match_all("/$regexp/siU", $msg, $matches))
			{
				return array(
					"airport" => "",
					"direction" => strtolower($matches[1][0])
				);
			}
			
			return false;
		}
		
		private function CreateUserHash($username = '')
		{
			$salt = $GLOBALS["salt"];
			
			$cleartext = $username;
			
			if($cleartext == '')
			{
				$cleartext = rand().'-'.time();	
			}
			
			return base64_encode(crypt($cleartext, $salt));
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
		
		private function GetAirportTimeZone($code)
		{
			$enum = new Enum();
			
			if($enum->LoadByCategoryByCode("AIRPORTS_TIMEZONE", $code))
			{
				return $enum->GetValue("description");
			}
			
			return "";
		}
		
		private function CreateEntry($airport, $in_time, $out_time, $username)
		{
			//set time zone
			$timezone = $this->GetAirportTimeZone($airport);
			if($timezone != "")
			{
				$timezone_old = date_default_timezone_get();
				date_default_timezone_set($timezone);
				
				$line = new Line();
				
				$line->SetValue("userhash", $this->CreateUserHash($username));
				$line->SetValue("linetype", "security");
				$line->SetValue("airportcode", $airport);
				$line->SetValue("airlinecode", "");
				$line->SetValue("in", date('Y-m-d H:i:s', $in_time));
				$line->SetValue("out", date('Y-m-d H:i:s', $out_time));
				$line->SetValue("diff", ($out_time - $in_time));
				$line->SetValue("inyear", date("Y", $in_time));
				$line->SetValue("inmonth", date("n", $in_time));
				$line->SetValue("indayofmonth", date("j", $in_time));
				$line->SetValue("indayofweek", date("N", $in_time));
				$line->SetValue("intimeblk15", $this->GetTimeBlk($in_time, 15));
				$line->SetValue("intimeblk30", $this->GetTimeBlk($in_time, 30));
				$line->SetValue("intimeblk60", $this->GetTimeBlk($in_time, 60));
				$line->SetValue("source", "twitter");
				$line->SetValue("useragent", "");
				$line->SetValue("timezone", $timezone);
				
				$line->Save();
				
				//restore time zone
				date_default_timezone_set($timezone_old);
			}
		}
		
		
		
	}
	
?>