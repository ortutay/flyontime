<?php
	
	include "lib.php";
	
	//echo "Loading Scheduled Task<br>";
	
	$loTask = new ScheduledTask();
	
	if($loTask->LoadExpiredTask())
	{
		//echo "Executing Scheduled Task<br>";
		
		$loTaskHandler = ScheduledTaskHandlerFactory::CreateScheduledTaskHandler($loTask->GetValue("HandlerNumber"));
		$loTaskHandler->Execute($loTask);
		
		//echo "Finished Executing Scheduled Task<br>";
	}
	else
	{
		//echo "No Scheduled Task Found<br>";
	}
	
	$loTasks = new ScheduledTaskCollection();
	$loTasks->RemoveCompletedTasks();
	$loTasks->ResetInProgressTasksStartedBefore(date('Y-m-d H:i:s', time() - 3600));
	
	//echo "Deleted Completed Tasks<br>";
	
	//echo "Done.<br>";
	
?>