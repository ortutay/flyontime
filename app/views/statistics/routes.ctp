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

<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load("visualization", "1", {packages:["barchart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Route');
	data.addColumn('number', '<?php echo $DataTitle; ?>');
	data.addRows(<?php echo count($Routes); ?>);
	
	<?php
	$i = 0;
	foreach($Routes as $route)
	{
	?>
	
	data.setValue(<?php echo $i; ?>, 0, '<?php echo $route['Log']['OriginCityName'].' ('.$route['Log']['Origin'].') - '.$route['Log']['DestCityName'].' ('.$route['Log']['Dest'].')'; ?>');
	data.setValue(<?php echo $i; ?>, 1, <?php echo $route[0][$DataValue]; ?>);
	
	<?php
	$i++;
	}
	?>

	var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
	chart.draw(data, {width: 650, height: 2000, is3D: true, legend: 'bottom', axisFontSize: 10, legendFontSize: 16});
  }
</script>


<div id='chart_div'></div>
