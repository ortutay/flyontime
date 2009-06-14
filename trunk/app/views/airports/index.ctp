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
    //FROM
	var data_from = new google.visualization.DataTable();
	data_from.addColumn('string', 'Airline');
	data_from.addColumn('number', 'Percent On-Time Arrival');
	data_from.addRows(<?php echo count($AirlinesFrom); ?>);
	
	<?php
	$i = 0;
	foreach($AirlinesFrom as $airline)
	{
	?>
	
	data_from.setValue(<?php echo $i; ?>, 0, '<?php echo $AirlineNames[$airline['Log']['Carrier']]; ?>');
	data_from.setValue(<?php echo $i; ?>, 1, <?php echo $airline[0]['PercentOnTime']; ?>);

	<?php
	$i++;
	}
	?>

	var chart_from = new google.visualization.BarChart(document.getElementById('chart_div_from'));
	chart_from.draw(data_from, {width: 325, height: 400, is3D: true, legend: 'bottom', axisFontSize: 14, legendFontSize: 16, min: 0, max: 100});
	
	//TO
	var data_to = new google.visualization.DataTable();
	data_to.addColumn('string', 'Airline');
	data_to.addColumn('number', 'Percent On-Time Arrival');
	data_to.addRows(<?php echo count($AirlinesTo); ?>);
	
	<?php
	$i = 0;
	foreach($AirlinesTo as $airline)
	{
	?>
	
	data_to.setValue(<?php echo $i; ?>, 0, '<?php echo $AirlineNames[$airline['Log']['UniqueCarrier']]; ?>');
	data_to.setValue(<?php echo $i; ?>, 1, <?php echo $airline[0]['PercentOnTime']; ?>);

	<?php
	$i++;
	}
	?>

	var chart_to = new google.visualization.BarChart(document.getElementById('chart_div_to'));
	chart_to.draw(data_to, {width: 325, height: 400, is3D: true, legend: 'bottom', axisFontSize: 14, legendFontSize: 16, min: 0, max: 100});
  }
</script>


<div class="subheader">Modify your search:</div>
<br />

<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr>
	<td align="center">

		<form method="GET" action="/disambiguate/airports">
			
			<table border=0 cellpadding=0 cellspacing=0>
			<tr>
				<td>
					<div>From:</div>
				</td>
				<td width="5px"></td>
				<td>
					<input name="from" type="text" style="width: 125px;" value="<?php echo $From; ?>" />
				</td>
				<td width="15px"></td>
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

<br />
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
		</tr>
		
		<?php
			$i++;
		}
		?>
		
		</table>

	</td>
	
	<td align="right">
	
		<div id='chart_div_from'></div>

	</td>
</tr>
</table>



<br /><br />

<div>
	<u>From <b><?php echo $ToCity; ?> (<?php echo $To; ?>)</b> to <b><?php echo $FromCity; ?> (<?php echo $From; ?>)</b><?php echo $day_str; ?>:</u>
</div>

<br />

<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr valign="top">
	<td align="left">

		<table border=0 cellpadding=5 cellspacing=1>
		<tr>
			<td><div><b>Flight</b></div></td>
			<td><div><b>Averge Arrival</b></div></td>
		</tr>
		
		<?php
		$i = 0;
		foreach($FlightsTo as $flight)
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
			<td><a href="/flights?airline=<?php echo $flight['Log']['UniqueCarrier']; ?>&flight_num=<?php echo $flight['Log']['FlightNum']; ?>&from=<?php echo $To; ?>&to=<?php echo $From; ?>&day=<?php echo $Day; ?>"><?php echo $AirlineNames[$flight['Log']['UniqueCarrier']].' '.$flight['Log']['FlightNum']; ?></a></td>
			<td><div style="<?php echo $delay_style; ?>"><?php echo $delay_str; ?></div></td>
		</tr>
		
		<?php
			$i++;
		}
		?>
		
		</table>
		
	</td>
	
	<td align="right">
	
		<div id='chart_div_to'></div>

	</td>
</tr>
</table>

<br />

