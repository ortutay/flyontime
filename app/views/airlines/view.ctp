<?php $this->pageTitle = 'FlyOnTime.us: Airlines - View'; ?>

<?php
function NiceDate($date)
{
	return substr($date, 5, 2) . '/' . substr($date, 8, 2) . '/' . substr($date, 0, 4);
}
?>

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
	data.setValue(0, 0, 'On-Time');
	data.setValue(0, 1, <?php echo round($Stats['pct_ontime']*$Stats["count"]); ?>);
	data.setValue(1, 0, '5-20 Min.');
	data.setValue(1, 1, <?php echo round((1.0 - $Stats['pct_ontime'] - $Stats['pct_cancel'] - $Stats['pct_20mindelay'])*$Stats["count"]); ?>);
	data.setValue(2, 0, '>20 Min.');
	data.setValue(2, 1, <?php echo round($Stats['pct_20mindelay']*$Stats["count"]); ?>);
	data.setValue(3, 0, 'Can./Div.');
	data.setValue(3, 1, <?php echo round($Stats['pct_cancel']*$Stats["count"]); ?>);

	var chart = new google.visualization.PieChart(document.getElementById('pie_chart_div'));
	chart.draw(data, {width: 220, height: 200, is3D: true, legend: "none"});
  }
  
  function drawBarChart() {
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Route');
	data.addColumn('number', 'Percent On Time');
	data.addRows(<?php echo count($Routes); ?>);
	
	<?php
	$max_abs_data_value = 0;
	
	$i = 0;
	foreach($Routes as $route)
	{
	if(abs($route[0]['count']) > $max_abs_data_value)
		$max_abs_data_value = abs($route[0]['count']);
	?>
	
	data.setValue(<?php echo $i; ?>, 0, '<?php echo $route['Ontime']['origin'].' - '.$route['Ontime']['dest']; ?>');
	data.setValue(<?php echo $i; ?>, 1, <?php echo round($route[0]['pct_ontime']*100); ?>);
	
	<?php
	$i++;
	}
	?>

	var chart = new google.visualization.BarChart(document.getElementById('bar_chart_div'));
	chart.draw(data, {width: 650, height: 500, is3D: true, legend: 'bottom', axisFontSize: 10, legendFontSize: 16});
  }
  
  function drawMap() {
    var map = new google.maps.Map2(document.getElementById("map_canvas"));
    map.setCenter(new google.maps.LatLng(39.0904320, -94.5836530), 4);
    
    <?php
    
    $max_line_thickness = 8;
    
	foreach($Routes as $route)
	{
	$line_thickness = round((abs($route[0]['count'])/$max_abs_data_value)*$max_line_thickness);
	if($line_thickness < 1)
		$line_thickness = 1;
	if (!isset( $Geocodes[$route['Ontime']['origin']]['Lng'])) { continue; }
	if (!isset( $Geocodes[$route['Ontime']['dest']]['Lng'])) { continue; }
	?>
    
    var polyOptions = {geodesic:true, clickable:false};
	map.addOverlay( new google.maps.Polyline([new google.maps.LatLng(<?php echo $Geocodes[$route['Ontime']['origin']]['Lat']; ?>, <?php echo $Geocodes[$route['Ontime']['origin']]['Lng']; ?>), new google.maps.LatLng(<?php echo $Geocodes[$route['Ontime']['dest']]['Lat']; ?>, <?php echo $Geocodes[$route['Ontime']['dest']]['Lng']; ?>)], "#ff0000", <?php echo $line_thickness; ?>, .75, polyOptions) );
	
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
			
				<h1 style="margin: 1em 0em 1em 0em">
					<?php echo $FullName; ?>
				</h1>
				
				<table>
				<tr valign="top">
				
				<td style="padding-right: 2em;" width="230">
					<div class="info" style="text-align: center">
						Based on <?php echo $Stats['count'] ?> flights from <?php echo NiceDate($Stats['firstdate']) ?> to <?php echo NiceDate($Stats['lastdate']) ?>
					</div>
					<div id="pie_chart_div"></div>
					
					<table style="font-size: 90%; margin-top: .5em; margin-left: 1em">
					<tr>
						<td><?php echo round($Stats["pct_ontime"]*100) ?>%</td>
						<th style="color: #66F; padding-left: .25em">On Time</th>
					</tr>
					<tr>
						<td><?php echo round((1-$Stats["pct_ontime"]-$Stats["pct_20mindelay"]-$Stats["pct_cancel"])*100) ?>%</td>
						<th style="color: #F66; padding-left: .25em">5-20 min. Delay</th>
					</tr>
					<tr>
						<td><?php echo round($Stats["pct_20mindelay"]*100) ?>%</td>
						<th style="color: #B80; padding-left: .25em">&gt;20 min. Delay</th>
					</tr>
					<tr>
						<td><?php echo round($Stats["pct_cancel"]*100) ?>%</td>
						<th style="color: #070; padding-left: .25em">Cancelled/Diverted</th>
					</tr>
					</table>
					
				</td>
				
				<td style="padding-left: 1em">
				
					<div id="map_canvas" style="width: 500px; height: 300px; border: 1px solid black"></div>
					
				</td>
				</tr>
				</table>
				
				
				<div class="header" style="margin-top: 2em">On-Time Performance</div>
				<div class="info">Top 25 most frequent flights</div>
				
				<div id='bar_chart_div'></div>

			</td>
		</tr>
		</table>

	</td>
</tr>
</table>
