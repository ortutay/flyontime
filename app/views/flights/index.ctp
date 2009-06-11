<?php $this->pageTitle = 'FlyOnTime.us: Flights'; ?>

<div class="header">
	<?php echo $Airline.' '.$FlightNum ?>
</div>
<br />

<div class="subheader">
	Carrier: <?php echo $AirlineInfo['Enum']['description']; ?>
	<br /><br />
</div>

<?php
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
?>

<div>
	<u>From <b><?php echo $OriginCityName.' ('.$Origin.')'; ?></b> to <b><?php echo $DestCityName.' ('.$Dest.')'; ?></b>:</u>
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
	
		var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
		chart.draw(data, {width: 400, height: 240, is3D: true});
	  }
	</script>
	
	
	<div id="chart_div"></div>
	
	Average arrival: <?php echo $ArrDelay_str; ?>
	<br /><br />
</div>

<?php
}
?>

<br /><br />
