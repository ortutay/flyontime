<?php $this->pageTitle = 'FlyOnTime.us: Statistics - '.$Name; ?>

<div class="header">
	Statistics - <?php echo $Name; ?>
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

<script type='text/javascript' src='http://www.google.com/jsapi'></script>
<script type='text/javascript'>
google.load('visualization', '1', {'packages': ['geomap']});
google.setOnLoadCallback(drawMap);

function drawMap() {
  var data = new google.visualization.DataTable();
  data.addRows(<?php echo count($Airports); ?>);
  
  data.addColumn('number', 'Latitude');
  data.addColumn('number', 'Longitude');
  data.addColumn('number', '<?php echo $DataTitle; ?>');
  data.addColumn('string', 'Airport Name');
  
  <?php
  $i = 0;
  foreach($Airports as $airport)
  {
    $airport_code = $airport['Log'][$AirportValue];
    $airport_name = $AirportNames[$airport_code];
    
    $airport_name = preg_replace("/[^a-zA-Z0-9\s\.,:\-\_\/]/", "", $airport_name);
    
    if(strlen($airport_name) > 28)
    	$airport_name = substr($airport_name, 0, 25).'...';
  ?>

  data.setValue(<?php echo $i; ?>, 0, <?php echo $Geocodes[$airport_code]['Lat']; ?>);
  data.setValue(<?php echo $i; ?>, 1, <?php echo $Geocodes[$airport_code]['Lng']; ?>);
  data.setValue(<?php echo $i; ?>, 2, <?php echo $airport[0][$DataValue]; ?>);
  data.setValue(<?php echo $i; ?>, 3, '<?php echo $airport_name; ?>');
  
  <?php
  $i++;
  }
  ?>

  var options = {};
  options['region'] = 'US';
  options['colors'] = [0xFF8747, 0xFFB581, 0xc06000]; //orange colors
  options['dataMode'] = 'markers';

  var container = document.getElementById('map_canvas');
  var geomap = new google.visualization.GeoMap(container);
  geomap.draw(data, options);
};

</script>

<div id='map_canvas'><blink>Loading...</blink></div>
