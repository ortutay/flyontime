<?php $this->pageTitle = 'FlyOnTime.us: Statistics - '.$Name; ?>

<div class="header">
	Statistics - <?php echo $Name; ?>
</div>

<br />

<script src='http://maps.google.com/maps?file=api&v=2&key=ABQIAAAAhD9h1r6o4CX5R6aR5Sm7chSk0bEoTe4xv8Xwjuk4IV3JR0xuqxSCWSPF07wu9P2WbpcpovPoKtafwQ' type='text/javascript'></script>
<script type='text/javascript' src='http://www.google.com/jsapi'></script>
<script type='text/javascript'>
google.load('visualization', '1', {'packages': ['geomap']});
google.setOnLoadCallback(drawMap);

function drawMap() {
  var data = new google.visualization.DataTable();
  data.addRows(<?php echo count($Airports); ?>);
  data.addColumn('string', 'Airport');
  data.addColumn('number', '<?php echo $DataTitle; ?>');
  
  <?php
  $i = 0;
  foreach($Airports as $airport)
  {
  ?>
  
  data.setValue(<?php echo $i; ?>, 0, '<?php echo $airport['Log']['Origin']; ?>');
  data.setValue(<?php echo $i; ?>, 1, <?php echo $airport[0][$DataValue]; ?>);
  
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
