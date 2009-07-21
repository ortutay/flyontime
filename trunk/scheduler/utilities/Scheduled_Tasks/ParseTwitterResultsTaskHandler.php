<?php
	
	class ParseTwitterResultsTaskHandler extends ScheduledTaskHandler
	{	
		function __construct()
		{
			
		}
		
		public function Execute($aoScheduledTask)
		{
			$params = parent::UnserializeParams($aoScheduledTask->GetValue("Params"));
			
			$filename = $params["filename"];

			if($filename != "" && file_exists($filename))
			{
				$encoded = file_get_contents($filename);
				
				if($encoded != "")
				{
					$response = array();
					
					try
					{
						$response = unserialize(base64_decode($encoded));
					} catch (Exception $e) {}
					
					if(isset($response["body"]) && isset($response["body"]["results"]))
					{
						foreach($response["body"]["results"] as $item)
						{
							$tweet = new Tweet();
							
							$tweet->SetValue("msgid", $item["id"]);
							$tweet->SetValue("username", $item["from_user"]);
							$tweet->SetValue("created", strtotime($item["created_at"]));
							$tweet->SetValue("msg", $item["text"]);
							$tweet->SetValue("matched", 0);
							
							$tweet->Save();
						}
					}
				}
				
				unlink($filename);
			}
			
			self::EndTask($aoScheduledTask, $params);
		}
		
		private static function EndTask($aoScheduledTask, $params)
		{
			$aoScheduledTask->MarkAsCompleted();
		}
		
	}
	
?>