<?php

$this->pageTitle = 'FlyOnTime.us: Flights';

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

?>

<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load('visualization', '1', {'packages':['piechart', 'barchart']});
  google.setOnLoadCallback(drawVisualizations);
  
  function drawVisualizations() {
  
    <?php
	$i = 0;
	foreach($AirportPairStats as $airport_pair => $stats)
	{
	?>
	
	//===========================================
	//Flight Outcome <?php echo $i; ?>
	
	//===========================================
	
	var data_outcome_<?php echo $i; ?> = new google.visualization.DataTable();
	data_outcome_<?php echo $i; ?>.addColumn('string', 'Flight Outcome');
	data_outcome_<?php echo $i; ?>.addColumn('number', 'Number of Flights');
	data_outcome_<?php echo $i; ?>.addRows(4);
	data_outcome_<?php echo $i; ?>.setValue(0, 0, 'Arrived On-Time');
	data_outcome_<?php echo $i; ?>.setValue(0, 1, <?php echo $stats['arrived_on_time']; ?>);
	data_outcome_<?php echo $i; ?>.setValue(1, 0, 'Arrived Late');
	data_outcome_<?php echo $i; ?>.setValue(1, 1, <?php echo ($stats['arrived'] - $stats['arrived_on_time']); ?>);
	data_outcome_<?php echo $i; ?>.setValue(2, 0, 'Cancelled');
	data_outcome_<?php echo $i; ?>.setValue(2, 1, <?php echo $stats['cancelled']; ?>);
	data_outcome_<?php echo $i; ?>.setValue(3, 0, 'Diverted');
	data_outcome_<?php echo $i; ?>.setValue(3, 1, <?php echo $stats['diverted']; ?>);

	var chart_outcome_<?php echo $i; ?> = new google.visualization.PieChart(document.getElementById('chart_div_outcome_<?php echo $i; ?>'));
	chart_outcome_<?php echo $i; ?>.draw(data_outcome_<?php echo $i; ?>, {width: 400, height: 325, is3D: true, legend: 'bottom', legendFontSize: 12});
	
	
	<?php
	if($Day == '')
	{
	?>
	
	//===========================================
	//Flight Days <?php echo $i; ?>
	
	//===========================================
	
	var data_day_<?php echo $i; ?> = new google.visualization.DataTable();
	data_day_<?php echo $i; ?>.addColumn('string', 'Day');
	data_day_<?php echo $i; ?>.addColumn('number', 'Arrived On-Time');
	data_day_<?php echo $i; ?>.addColumn('number', 'Arrived Late');
	data_day_<?php echo $i; ?>.addColumn('number', 'Cancelled');
	data_day_<?php echo $i; ?>.addColumn('number', 'Diverted');
	data_day_<?php echo $i; ?>.addRows(<?php echo count($stats['days']); ?>);
	
	<?php
	$j = 0;
	foreach($stats['days'] as $day_index => $day)
	{
	?>
	
	data_day_<?php echo $i; ?>.setValue(<?php echo $j; ?>, 0, '<?php echo GetDayName($day_index); ?>');
	data_day_<?php echo $i; ?>.setValue(<?php echo $j; ?>, 1, <?php echo ($day['total'] - $day['delayed'] - $day['cancelled'] - $day['diverted']); ?>);
	data_day_<?php echo $i; ?>.setValue(<?php echo $j; ?>, 2, <?php echo $day['delayed']; ?>);
	data_day_<?php echo $i; ?>.setValue(<?php echo $j; ?>, 3, <?php echo $day['cancelled']; ?>);
	data_day_<?php echo $i; ?>.setValue(<?php echo $j; ?>, 4, <?php echo $day['diverted']; ?>);

	<?php
	$j++;
	}
	?>
	
	var chart_day_<?php echo $i; ?> = new google.visualization.BarChart(document.getElementById('chart_div_day_<?php echo $i; ?>'));
	chart_day_<?php echo $i; ?>.draw(data_day_<?php echo $i; ?>, {width: 380, height: 400, is3D: true, legend: 'bottom', axisFontSize: 14, legendFontSize: 12, titleFontSize: 16, isStacked: true, titleX: 'Number of Flights', title: 'Day of Week', min: 0});
	
	<?php
	}
	?>
	
	<?php
	$i++;
	}
	?>
	
  }
</script>


<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr>
	<td align="left">

		<form method="GET" action="/disambiguate/flights">
			
			<input type="hidden" name="from" value="<?php echo $From; ?>" />
			<input type="hidden" name="to" value="<?php echo $To; ?>" />
			
			<table border=0 cellpadding=0 cellspacing=0>
			<tr>
				<td>
					<div>Airline:</div>
				</td>
				<td width="5px"></td>
				<td>
					<input name="airline" type="text" style="width: 125px;" value="<?php echo $Airline; ?>" />
				</td>
				<td width="15px"></td>
				<td>
					<div>Flight #:</div>
				</td>
				<td width="5px"></td>
				<td>
					<input name="flight_num" type="text" style="width: 125px;" value="<?php echo $FlightNum; ?>" />
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
					<?php echo $Airline.' '.$FlightNum ?>
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

			</td>
		</tr>
		</table>
		

		<table border=0 cellpadding=0 cellspacing=0>
		<tr>
			<td align="left">
			
				<div class="subheader">
					Carrier: <?php echo $AirlineInfo['Enum']['description']; ?>
					<br /><br />
				</div>
				
				<?php
				$i = 0;
				foreach($AirportPairStats as $airport_pair => $stats)
				{
					$OriginCityName = $AirportPairFlights[$airport_pair][0]['Log']['OriginCityName'];
					$Origin = $AirportPairFlights[$airport_pair][0]['Log']['Origin'];
					
					$DestCityName = $AirportPairFlights[$airport_pair][0]['Log']['DestCityName'];
					$Dest = $AirportPairFlights[$airport_pair][0]['Log']['Dest'];
					
					$ArrDelay_str = 'on time';
					if($stats['avg_arrival_delay'] < 0)
					{
						$ArrDelay_str = abs(round($stats['avg_arrival_delay'], 1)).' min. early';
						$ArrDelay_style = 'color: green; display: inline;';
					}
					elseif($stats['avg_arrival_delay'] > 0)
					{
						$ArrDelay_str = round($stats['avg_arrival_delay'], 1).' min. late';
						$ArrDelay_style = 'color: red; display: inline;';
					}
					
					$day_str = '';
					if($Day != '')
						$day_str = ' on <b>'.GetDayName($Day).'</b>';
				?>
				
				<div>
					<u>From <b><?php echo $OriginCityName.' ('.$Origin.')'; ?></b> to <b><?php echo $DestCityName.' ('.$Dest.')'; ?></b><?php echo $day_str; ?>:</u>
					<br /><br />
					
					<table border=0 cellpadding=0 cellspacing=0 width="100%">
					<tr valign="top">
						<td align="left">
							<div>
								Out of <?php echo $stats['total']; ?> flights scheduled:
								<br /><br />
								
								<div id="chart_div_outcome_<?php echo $i; ?>"></div>
								
								<br />
								
								Average arrival: <div style="<?php echo $ArrDelay_style; ?>"><?php echo $ArrDelay_str; ?></div>
								
								<br /><br />
								
								Departure Time(s) in 24-hour format:
								
								<?php
								foreach($stats['times'] as $time_block => $time)
								{
								?>
								
								<br /><br />
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo $time['total']; ?> flights</b> in the range <b><?php echo $time_block; ?></b>
								
								<?php
								}
								?>
							</div>
						</td>
						<td align="right">
							<div id="chart_div_day_<?php echo $i; ?>"></div>
						</td>
					</tr>
					</table>
					
					<br /><br /><br />
				</div>
				
				<?php
					$i++;
				}
				?>
		
			</td>
		</tr>
		</table>		
		
	</td>
</tr>
</table>

<br /><br />
