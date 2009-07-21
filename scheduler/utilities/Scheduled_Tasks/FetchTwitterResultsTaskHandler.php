<?php
	
	class FetchTwitterResultsTaskHandler extends ScheduledTaskHandler
	{	
		function __construct()
		{
			
		}
		
		public function Execute($aoScheduledTask)
		{
			$params = parent::UnserializeParams($aoScheduledTask->GetValue("Params"));
			
			$response = array();
			
			if(isset($params["since_id"]))
				$response = util_twitter::search(array("q" => "#airportsecurity", "rpp" => "100", "since_id" => $params["since_id"]));
			else
				$response = util_twitter::search(array("q" => "#airportsecurity", "rpp" => "100"));
			
			$num_pages = 0;
			
			if(util_twitter::isValidResponse($response))
			{
				$num_pages++;
				
				$filename = $GLOBALS["filetmp_root_abspath"].($this->CreateRandomFilename());
				file_put_contents($filename, base64_encode(serialize($response)));
				$this->CreateScheduledTask($filename);
				
				$next_page = "";
				if(isset($response["body"]["next_page"]))
					$next_page = $response["body"]["next_page"];
					
				if(isset($response["body"]["max_id"]))
					$params["since_id"] = $response["body"]["max_id"];
				
				while($next_page != "")
				{
					$num_pages++;
					
					sleep(1);
					
					$response = util_twitter::nonAuthJSONQuery($GLOBALS["twitter_search_api_root"].".json".$next_page);
					
					$next_page = "";
					
					if(util_twitter::isValidResponse($response))
					{
						$filename = $GLOBALS["filetmp_root_abspath"].($this->CreateRandomFilename());
						file_put_contents($filename, base64_encode(serialize($response)));
						$this->CreateScheduledTask($filename);
						
						if(isset($response["body"]["next_page"]))
							$next_page = $response["body"]["next_page"];
					}
				}
				
			}
			else
			{
				$params = array();
			}
			
			if($num_pages == 0)
				self::EndTask($aoScheduledTask, $params, 1);
			elseif($num_pages == 1)
				self::EndTask($aoScheduledTask, $params, 2);
			else
				self::EndTask($aoScheduledTask, $params, 10);
		}
		
		private static function EndTask($aoScheduledTask, $params, $minutes = 10)
		{
			$aoScheduledTask->SetValue("Params", parent::SerializeParams($params));
			$aoScheduledTask->AdvanceExpirationBy(60*$minutes);
		}
		
		private function CreateRandomFilename($ext = "txt")
		{
			$cleartext = rand().'-'.time();	
			
			return base64_encode(crypt($cleartext)).".".$ext;
		}
		
		private function CreateScheduledTask($filename)
		{
			$task = new ScheduledTask();
			
			$params = array(
				"filename" => $filename
			);
			
			$task->SetValue("HandlerNumber", ScheduledTask::$ePARSETWITTERRESULTS);
			$task->SetValue("Params", base64_encode(serialize($params)));
			$task->Save();
		}
		
		
	}
	
?>