<?php
	
	class FetchTwitterMyTSAResultsTaskHandler extends ScheduledTaskHandler
	{	
		function __construct()
		{
			
		}
		
		public function Execute($aoScheduledTask)
		{
			$params = parent::UnserializeParams($aoScheduledTask->GetValue("Params"));
			
			$response = array();
			
			if(isset($params["since_id"]))
				$response = util_twitter::userTimeline(array("screen_name" => "MyTSA", "count" => 200, "since_id" => $params["since_id"]));
			else
				$response = util_twitter::userTimeline(array("screen_name" => "MyTSA", "count" => 200));

			
			if(count($response["body"]) > 0)
				$params["since_id"] = $response["body"][0]["id"];
			
			
			
			if(util_twitter::isValidResponse($response))
			{
				foreach($response["body"] as $item)
				{
					$text = $item["text"];
					$created = strtotime($item["created_at"]);
					$parse = $this->ParseText($item["text"]);
					
					
					if($parse["airport"] != "" && $parse["wait_time"] >= 0)
					{
						$airport = $parse["airport"];
						$in_time = $created - $parse["wait_time"];
						$out_time = $created;

						$this->CreateEntry($airport, $in_time, $out_time);
					}

				}
			}
			
			
			self::EndTask($aoScheduledTask, $params, 5);
		}
		
		private static function EndTask($aoScheduledTask, $params, $minutes = 10)
		{
			$aoScheduledTask->SetValue("Params", parent::SerializeParams($params));
			$aoScheduledTask->AdvanceExpirationBy(60*$minutes);
		}
		
		private function ParseText($text)
		{
			$ret = array();
			
			// get wait time
			$ret["wait_time"] = 0;
			
			if(strpos($text,"No Wait") !== false)
				$ret["wait_time"] = 0;
			elseif(strpos($text,"1-10 min") !== false)
				$ret["wait_time"] = 5.5;
			elseif(strpos($text,"11-20 min") !== false)
				$ret["wait_time"] = 15.5;
			elseif(strpos($text,"21-30 min") !== false)
				$ret["wait_time"] = 25.5;
			elseif(strpos($text,"31+ min") !== false)
				$ret["wait_time"] = 31;
			
			$ret["wait_time"] = $ret["wait_time"]*60;
			
			
			// get airport code
			$ret["airport"] = "";
			
			$regexp = "#([a-zA-Z]{3})";
		
			if(preg_match_all("/$regexp/siU", $text, $matches))
			{
				$ret["airport"] = strtoupper($matches[1][0]);
			}
			
			return $ret;
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
		
		private function CreateEntry($airport, $in_time, $out_time)
		{
			$userhash = $this->CreateUserHash("MyTSA");
			

			//set time zone
			$timezone = $this->GetAirportTimeZone($airport);
			if($timezone != "")
			{
				$timezone_old = date_default_timezone_get();
				date_default_timezone_set($timezone);
				
				$line = new Line();
				
				$line->SetValue("userhash", $userhash);
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
				$line->SetValue("source", "twitter_MyTSA");
				$line->SetValue("useragent", "");
				$line->SetValue("timezone", $timezone);
				
				
				$line->Save();
				
				//restore time zone
				date_default_timezone_set($timezone_old);
			}
				
		}
		
		
	}
	
?>