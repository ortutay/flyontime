<?php $this->pageTitle = 'FlyOnTime.us: Airlines - View'; ?>

<script type="text/javascript" src="http://www.google.com/jsapi?key=ABQIAAAAhD9h1r6o4CX5R6aR5Sm7chSk0bEoTe4xv8Xwjuk4IV3JR0xuqxSCWSPF07wu9P2WbpcpovPoKtafwQ"></script>
<script type="text/javascript">

  // Load the Visualization API
  google.load('visualization', '1', {'packages':['piechart', 'barchart']});
  google.load("maps", "2.x");
  
  // Set a callback to run when the API is loaded.
  google.setOnLoadCallback(drawVisualizations);
  
  function drawVisualizations()
  {
  	drawBarChart();
  	drawPieChart();
  	drawMap();
  }
  
  function drawPieChart() {
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Flight Outcome');
	data.addColumn('number', 'Number of Flights');
	data.addRows(4);
	data.setValue(0, 0, 'Arrived On-Time');
	data.setValue(0, 1, <?php echo ($Stats['NumScheduled'] - $Stats['NumCancelled'] - $Stats['NumDiverted'] - $Stats['NumDelayed']); ?>);
	data.setValue(1, 0, 'Arrived Late');
	data.setValue(1, 1, <?php echo $Stats['NumDelayed']; ?>);
	data.setValue(2, 0, 'Cancelled');
	data.setValue(2, 1, <?php echo $Stats['NumCancelled']; ?>);
	data.setValue(3, 0, 'Diverted');
	data.setValue(3, 1, <?php echo $Stats['NumDiverted']; ?>);

	var chart = new google.visualization.PieChart(document.getElementById('pie_chart_div'));
	chart.draw(data, {width: 400, height: 240, is3D: true});
  }
  
  function drawBarChart() {
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Route');
	data.addColumn('number', 'Scheduled Flights');
	data.addRows(<?php echo count($Routes); ?>);
	
	<?php
	$max_abs_data_value = 0;
	
	$i = 0;
	foreach($Routes as $route)
	{
	if(abs($route[0]['NumScheduled']) > $max_abs_data_value)
		$max_abs_data_value = abs($route[0]['NumScheduled']);
	?>
	
	data.setValue(<?php echo $i; ?>, 0, '<?php echo $route['Log']['OriginCityName'].' ('.$route['Log']['Origin'].') - '.$route['Log']['DestCityName'].' ('.$route['Log']['Dest'].')'; ?>');
	data.setValue(<?php echo $i; ?>, 1, <?php echo $route[0]['NumScheduled']; ?>);
	
	<?php
	$i++;
	}
	?>

	var chart = new google.visualization.BarChart(document.getElementById('bar_chart_div'));
	chart.draw(data, {width: 650, height: 2500, is3D: true, legend: 'bottom', axisFontSize: 10, legendFontSize: 16});
  }
  
  function drawMap() {
    var map = new google.maps.Map2(document.getElementById("map_canvas"));
    map.setCenter(new google.maps.LatLng(39.0904320, -94.5836530), 4);
    
    <?php
    
    $max_line_thickness = 8;
    
	foreach($Routes as $route)
	{
	$line_thickness = round((abs($route[0]['NumScheduled'])/$max_abs_data_value)*$max_line_thickness);
	if($line_thickness < 1)
		$line_thickness = 1;
	?>
    
	map.addOverlay( new google.maps.Polyline([new google.maps.LatLng(<?php echo $Geocodes[$route['Log']['Origin']]['Lat']; ?>, <?php echo $Geocodes[$route['Log']['Origin']]['Lng']; ?>), new google.maps.LatLng(<?php echo $Geocodes[$route['Log']['Dest']]['Lat']; ?>, <?php echo $Geocodes[$route['Log']['Dest']]['Lng']; ?>)], "#ff0000", <?php echo $line_thickness; ?>) );
	
	<?php
	}
	?>
  }
</script>

<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr>
	<td align="center">
	
		<table border=0 cellpadding=0 cellspacing=0 width="800px">
		<tr>
			<td align="left">
			
				<div class="header">
					<?php echo $FullName; ?>
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
				
				<table border=0 cellpadding=0 cellspacing=0 width="100%">
				<tr valign="middle">
					<td align="left">
						
						<div>
							Total Flights Scheduled: <?php echo $Stats['NumScheduled']; ?>
							<br /><br />
							Average Minutes Arriving Late: <?php echo round($Stats['TotalArrivalDelay']/($Stats['NumScheduled'] - $Stats['NumCancelled'] - $Stats['NumDiverted']), 1); ?>
						</div>
						
					</td>
					
					<td align="right">
						
						<div id="pie_chart_div"></div>
						
					</td>
				</tr>
				</table>
				
				<div id="map_canvas" style="width: 100%; height: 400px"></div>
				
				<br />
				
				<div id='bar_chart_div'></div>

			</td>
		</tr>
		</table>

	</td>
</tr>
</table>
