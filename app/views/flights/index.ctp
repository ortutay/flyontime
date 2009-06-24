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
						$ArrDelay_str = abs(round($stats['avg_arrival_delay'], 1)).' min. early';
					elseif($stats['avg_arrival_delay'] > 0)
						$ArrDelay_str = round($stats['avg_arrival_delay'], 1).' min. late';
					
					$day_str = '';
					if($Day != '')
						$day_str = ' on <b>'.GetDayName($Day).'</b>';
				?>
				
				<div>
					<u>From <b><?php echo $OriginCityName.' ('.$Origin.')'; ?></b> to <b><?php echo $DestCityName.' ('.$Dest.')'; ?></b><?php echo $day_str; ?>:</u>
					<br /><br />
					
					Out of <?php echo $stats['total']; ?> flights scheduled:
					<br /><br />
					
					<script type="text/javascript" src="http://www.google.com/jsapi"></script>
					<script type="text/javascript">
					
					  // Load the Visualization API and the piechart package.
					  google.load('visualization', '1', {'packages':['piechart']});
					  
					  // Set a callback to run when the API is loaded.
					  google.setOnLoadCallback(drawChart);
					  
					  // Callback that creates and populates a data table, 
					  // instantiates the pie chart, passes in the data and
					  // draws it.
					  function drawChart() {
						var data = new google.visualization.DataTable();
						data.addColumn('string', 'Flight Outcome');
						data.addColumn('number', 'Number of Flights');
						data.addRows(4);
						data.setValue(0, 0, 'Arrived On-Time');
						data.setValue(0, 1, <?php echo $stats['arrived_on_time']; ?>);
						data.setValue(1, 0, 'Arrived Late');
						data.setValue(1, 1, <?php echo ($stats['arrived'] - $stats['arrived_on_time']); ?>);
						data.setValue(2, 0, 'Cancelled');
						data.setValue(2, 1, <?php echo $stats['cancelled']; ?>);
						data.setValue(3, 0, 'Diverted');
						data.setValue(3, 1, <?php echo $stats['diverted']; ?>);
					
						var chart = new google.visualization.PieChart(document.getElementById('chart_div_<?php echo $i; ?>'));
						chart.draw(data, {width: 400, height: 240, is3D: true});
					  }
					</script>
					
					
					<div id="chart_div_<?php echo $i; ?>"></div>
					
					Average arrival: <?php echo $ArrDelay_str; ?>
					<br /><br />
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
