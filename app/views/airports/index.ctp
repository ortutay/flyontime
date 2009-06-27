<?php

$this->pageTitle = 'FlyOnTime.us: Airports';

function GetDayName($day)
{
	switch($day)
	{
		case 1:
			return 'Monday';
			break;
		
		case 2:
			return 'Tuesday';
			break;
		
		case 3:
			return 'Wednesday';
			break;
		
		case 4:
			return 'Thursday';
			break;
		
		case 5:
			return 'Friday';
			break;
		
		case 6:
			return 'Saturday';
			break;
		
		case 7:
			return 'Sunday';
			break;
	}
	
	return '';
}

$day_str = '';
if($Day != '')
	$day_str = ' on <b>'.GetDayName($Day).'</b>';

?>

<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load("visualization", "1", {packages:["barchart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
    //AIRLINE FROM
	var data_airline_from = new google.visualization.DataTable();
	data_airline_from.addColumn('string', 'Airline');
	data_airline_from.addColumn('number', 'Percent On-Time Arrival');
	data_airline_from.addRows(<?php echo count($AirlinesFrom); ?>);
	
	<?php
	$i = 0;
	foreach($AirlinesFrom as $airline)
	{
	?>
	
	data_airline_from.setValue(<?php echo $i; ?>, 0, '<?php echo $AirlineNames[$airline['Log']['Carrier']]; ?>');
	data_airline_from.setValue(<?php echo $i; ?>, 1, <?php echo $airline[0]['PercentOnTime']; ?>);

	<?php
	$i++;
	}
	?>

	var chart_airline_from = new google.visualization.BarChart(document.getElementById('chart_div_airline_from'));
	chart_airline_from.draw(data_airline_from, {width: 325, height: 400, is3D: true, legend: 'bottom', axisFontSize: 14, legendFontSize: 16, min: 0, max: 100});
	
	//DAY FROM
	<?php
	if($Day == '')
	{
	?>
	
	var data_day_from = new google.visualization.DataTable();
	data_day_from.addColumn('string', 'Day');
	data_day_from.addColumn('number', 'Arrived On-Time');
	data_day_from.addColumn('number', 'Arrived Late');
	data_day_from.addColumn('number', 'Cancelled');
	data_day_from.addColumn('number', 'Diverted');
	data_day_from.addRows(<?php echo count($DaysFrom); ?>);
	
	<?php
	$i = 0;
	foreach($DaysFrom as $day)
	{
	?>
	
	data_day_from.setValue(<?php echo $i; ?>, 0, '<?php echo GetDayName($day['Log']['DayOfWeek']); ?>');
	data_day_from.setValue(<?php echo $i; ?>, 1, <?php echo ($day[0]['NumScheduled'] - $day[0]['NumDelayed'] - $day[0]['NumCancelled'] - $day[0]['NumDiverted']); ?>);
	data_day_from.setValue(<?php echo $i; ?>, 2, <?php echo $day[0]['NumDelayed']; ?>);
	data_day_from.setValue(<?php echo $i; ?>, 3, <?php echo $day[0]['NumCancelled']; ?>);
	data_day_from.setValue(<?php echo $i; ?>, 4, <?php echo $day[0]['NumDiverted']; ?>);

	<?php
	$i++;
	}
	?>

	var chart_day_from = new google.visualization.BarChart(document.getElementById('chart_div_day_from'));
	chart_day_from.draw(data_day_from, {width: 380, height: 500, is3D: true, legend: 'bottom', axisFontSize: 14, legendFontSize: 16, titleFontSize: 16, isStacked: true, titleX: 'Number of Flights', title: 'Day of Week', min: 0});
	
	<?php
	}
	?>
	
	//TIME FROM
	var data_time_from = new google.visualization.DataTable();
	data_time_from.addColumn('string', 'Time');
	data_time_from.addColumn('number', 'Arrived On-Time');
	data_time_from.addColumn('number', 'Arrived Late');
	data_time_from.addColumn('number', 'Cancelled');
	data_time_from.addColumn('number', 'Diverted');
	data_time_from.addRows(<?php echo count($TimesFrom); ?>);
	
	<?php
	$i = 0;
	foreach($TimesFrom as $time)
	{
	?>
	
	data_time_from.setValue(<?php echo $i; ?>, 0, '<?php echo $time['Log']['DepTimeBlk']; ?>');
	data_time_from.setValue(<?php echo $i; ?>, 1, <?php echo ($time[0]['NumScheduled'] - $time[0]['NumDelayed'] - $time[0]['NumCancelled'] - $time[0]['NumDiverted']); ?>);
	data_time_from.setValue(<?php echo $i; ?>, 2, <?php echo $time[0]['NumDelayed']; ?>);
	data_time_from.setValue(<?php echo $i; ?>, 3, <?php echo $time[0]['NumCancelled']; ?>);
	data_time_from.setValue(<?php echo $i; ?>, 4, <?php echo $time[0]['NumDiverted']; ?>);

	<?php
	$i++;
	}
	?>

	var chart_time_from = new google.visualization.BarChart(document.getElementById('chart_div_time_from'));
	
	<?php
	if($Day == '')
	{
	?>
	chart_time_from.draw(data_time_from, {width: 380, height: 500, is3D: true, legend: 'bottom', axisFontSize: 14, legendFontSize: 16, titleFontSize: 16, isStacked: true, titleX: 'Number of Flights', title: 'Time of Day (24-hour format)', min: 0});
	<?php
	}
	else
	{
	?>
	chart_time_from.draw(data_time_from, {width: 800, height: 500, is3D: true, legend: 'bottom', axisFontSize: 14, legendFontSize: 16, titleFontSize: 16, isStacked: true, titleX: 'Number of Flights', title: 'Time of Day (24-hour format)', min: 0});
	<?php
	}
	?>
  }
  
  function swap_search()
  {
  	var temp = document.search.from.value;
  	document.search.from.value = document.search.to.value;
  	document.search.to.value = temp;
  }
</script>

<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr>
	<td align="left">

		<form method="GET" action="/disambiguate/airports" name="search">
			
			<table border=0 cellpadding=0 cellspacing=0>
			<tr>
				<td>
					<div>From:</div>
				</td>
				<td width="5px"></td>
				<td>
					<input name="from" type="text" style="width: 125px;" value="<?php echo $From; ?>" />
				</td>
				<td width="10px"></td>
				<td><a href="javascript: swap_search();"><img border=0 src="/img/swap.png" /></a></td>
				<td width="10px"></td>
				<td>
					<div>To:</div>
				</td>
				<td width="5px"></td>
				<td>
					<input name="to" type="text" style="width: 125px;" value="<?php echo $To; ?>" />
				</td>
				<td width="15px"></td>
				<td>
					<div>Day:</div>
				</td>
				<td width="5px"></td>
				<td>
					<select name="day">
						<option value=""></option>
						<option value="1" <?php if($Day==1) echo 'selected'; ?>>Monday</option>
						<option value="2" <?php if($Day==2) echo 'selected'; ?>>Tuesday</option>
						<option value="3" <?php if($Day==3) echo 'selected'; ?>>Wednesday</option>
						<option value="4" <?php if($Day==4) echo 'selected'; ?>>Thursday</option>
						<option value="5" <?php if($Day==5) echo 'selected'; ?>>Friday</option>
						<option value="6" <?php if($Day==6) echo 'selected'; ?>>Saturday</option>
						<option value="7" <?php if($Day==7) echo 'selected'; ?>>Sunday</option>
					</select>
				</td>
				<td width="25px"></td>
				<td>
					<input type="submit" value="Search >>" />
				</td>
				
			</tr>
			</table>
			
		</form>
		
	</td>
</tr>
</table>

<br /><br />

<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr>
	<td align="center">
	
		<table border=0 cellpadding=0 cellspacing=0 width="800px">
		<tr>
			<td align="left">

				<div class="header">
					Most On-Time Flights and Airlines
				</div>
				<div style="color: #777777;">
					Data from 
					<?php
					$i = 0;
					$num = count($Months);
					foreach($Months as $month => $foo)
					{
						echo $month;
						
						if($i < ($num - 1))
							echo ', ';
					}
					?>
				</div>
				<br />
				
				<div>
					<u>From <b><?php echo $FromCity; ?> (<?php echo $From; ?>)</b> to <b><?php echo $ToCity; ?> (<?php echo $To; ?>)</b><?php echo $day_str; ?>:</u>
				</div>
				
				<br />

				<table border=0 cellpadding=0 cellspacing=0 width="100%">
				<tr valign="top">
					<td align="left">
					
						<table border=0 cellpadding=5 cellspacing=1>
						<tr>
							<td><div><b>Flight</b></div></td>
							<td><div><b>Averge Arrival</b></div></td>
							<td><div><b>Num Flights</b></div></td>
						</tr>
						
						<?php
						$i = 0;
						foreach($FlightsFrom as $flight)
						{
							$style = '';
							
							$delay = round($flight[0]['AvgArrDelay'], 1);
							
							$delay_style = '';
							$delay_str = '';
							if($delay < 0)
							{
								$delay_str = abs($delay).' min. early';
								$delay_style = 'color: green;';
							}
							elseif($delay > 0)
							{
								$delay_str = $delay.' min. late';
								$delay_style = 'color: red;';
							}
							else
							{
								$delay_str = 'on time';
								$delay_style = 'color: black;';
							}
							
							if(($i % 2) == 0)
								$style = 'background-color: #DDDDDD;';
						?>
						
						<tr style="<?php echo $style; ?>">
							<td><a href="/flights?airline=<?php echo $flight['Log']['UniqueCarrier']; ?>&flight_num=<?php echo $flight['Log']['FlightNum']; ?>&from=<?php echo $From; ?>&to=<?php echo $To; ?>&day=<?php echo $Day; ?>"><?php echo $AirlineNames[$flight['Log']['UniqueCarrier']].' '.$flight['Log']['FlightNum']; ?></a></td>
							<td><div style="<?php echo $delay_style; ?>"><?php echo $delay_str; ?></div></td>
							<td><div><?php echo $flight[0]['NumScheduled']; ?></div></td>
						</tr>
						
						<?php
							$i++;
						}
						?>
						
						</table>
				
					</td>
					
					<td align="right">
					
						<div id='chart_div_airline_from'></div>
				
					</td>
				</tr>
				</table>
				
				<br /><br />
				
				<div class="header">
					<?php
					if($Day == '')
					{
					?>
					Best Days and Times to Fly
					<?php
					}
					else
					{
					?>
					Best Times to Fly
					<?php
					}
					?>
				</div>
				<div style="color: #777777;">
					Data from 
					<?php
					$i = 0;
					$num = count($Months);
					foreach($Months as $month => $foo)
					{
						echo $month;
						
						if($i < ($num - 1))
							echo ', ';
					}
					?>
				</div>
				<br />
				
				<div>
					<u>From <b><?php echo $FromCity; ?> (<?php echo $From; ?>)</b> to <b><?php echo $ToCity; ?> (<?php echo $To; ?>)</b><?php echo $day_str; ?>:</u>
				</div>
				
				<br />
				
				<?php
				if($Day == '')
				{
				?>
				<table border=0 cellpadding=0 cellspacing=0 width="100%">
				<tr>
					
					<td align="left">
						<div id='chart_div_day_from'></div>
					</td>
					<td align="right">
						<div id='chart_div_time_from'></div>
					</td>
				</tr>
				</table>
				<?php
				}
				else
				{
				?>
				<div id='chart_div_time_from'></div>
				<?php
				}
				?>

			</td>
		</tr>
		</table>

	</td>
</tr>
</table>

<br /><br />


