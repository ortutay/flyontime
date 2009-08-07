<?php $this->pageTitle = 'FlyOnTime.us: Statistics - '.$Name; ?>

<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr>
	<td align="center">
	
		<table border=0 cellpadding=0 cellspacing=0 width="800px">
		<tr>
			<td align="left">
				
				<br />
				<h1>
					Airline Statistics
				</h1>
				<div class="header">
					<?php echo $Name; ?>
				</div>
				<br />

			</td>
		</tr>
		</table>
		
		<script type="text/javascript" src="http://www.google.com/jsapi"></script>
		<script type="text/javascript">
		  google.load("visualization", "1", {packages:["barchart"]});
		  google.setOnLoadCallback(drawChart);
		  function drawChart() {
			var data = new google.visualization.DataTable();
			data.addColumn('string', 'Airline');
			data.addColumn('number', '<?php echo $DataTitle; ?>');
			data.addRows(<?php echo count($Airlines); ?>);
			
			<?php
			$i = 0;
			foreach($Airlines as $airline)
			{
			?>
			
			data.setValue(<?php echo $i; ?>, 0, '<?php echo $AirlineNames[$airline['Ontime']['carrier']]; ?>');
			data.setValue(<?php echo $i; ?>, 1, <?php echo $airline['Ontime'][$DataValue]*$MultiplyBy; ?>);
			
			<?php
			$i++;
			}
			?>
		
			var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
			chart.draw(data, {width: 650, height: 700, is3D: true, legend: 'bottom', axisFontSize: 14});
		  }
		</script>
		
		
		<div id='chart_div'></div>
		
	</td>
</tr>
</table>
