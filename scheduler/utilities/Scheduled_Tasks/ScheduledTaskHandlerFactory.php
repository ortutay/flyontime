<?php
	
	class ScheduledTaskHandlerFactory
	{	
		public static function CreateScheduledTaskHandler($TaskHandlerNumber)
		{
			switch ($TaskHandlerNumber)
			{
				case ScheduledTask::$eFETCHTWITTERRESULTS:
					return new FetchTwitterResultsTaskHandler();
					break;
				
				case ScheduledTask::$ePARSETWITTERRESULTS:
					return new ParseTwitterResultsTaskHandler();
					break;
				
				case ScheduledTask::$eMATCHTWEETS:
					return new MatchTweetsTaskHandler();
					break;
			}
		}
	
	}
	
?>