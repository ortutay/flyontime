<?php $this->pageTitle = 'FlyOnTime.us: Statistics - '.$Name; ?>

<div class="header">
	Statistics - <?php echo $Name; ?>
</div>

<br />

<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load("visualization", "1", {packages:["barchart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Airline');
	data.addColumn('number', 'Minutes Delayed');
	data.addRows(<?php echo count($Airlines); ?>);
	
	<?php
	$i = 0;
	foreach($Airlines as $airline)
	{
	?>
	
	data.setValue(<?php echo $i; ?>, 0, '<?php echo $AirlineNames[$airline['Log']['UniqueCarrier']]; ?>');
	data.setValue(<?php echo $i; ?>, 1, <?php echo $airline[0][$DataValue]; ?>);
	
	<?php
	$i++;
	}
	?>

	var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
	chart.draw(data, {width: 650, height: 700, is3D: true, legend: 'bottom'});
  }
</script>


<div id='chart_div'></div>
