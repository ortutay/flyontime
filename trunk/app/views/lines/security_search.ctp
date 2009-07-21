<?php

$this->pageTitle = 'FlyOnTime.us: Airport Security Lines - '.$Airport;

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



<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load("visualization", "1", {packages:["barchart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
	//DAYS	
	var data_day = new google.visualization.DataTable();
	data_day.addColumn('string', 'Day');
	data_day.addColumn('number', 'Average Wait Time');
	data_day.addRows(<?php echo count($Days); ?>);
	
	<?php
	$i = 0;
	foreach($Days as $day)
	{
	?>
	
	data_day.setValue(<?php echo $i; ?>, 0, '<?php echo GetDayName($day['Line']['indayofweek']); ?>');
	data_day.setValue(<?php echo $i; ?>, 1, <?php echo round($day[0]['AvgDiff']/60, 1); ?>);

	<?php
	$i++;
	}
	?>

	var chart_day = new google.visualization.BarChart(document.getElementById('chart_div_day'));
	chart_day.draw(data_day, {width: 380, height: 500, is3D: true, legend: 'bottom', axisFontSize: 14, legendFontSize: 16, titleFontSize: 16, isStacked: true, titleX: 'Wait Time (min.)', title: 'Days', min: 0});
	
	
	
	
	//TIMES
	var data_time = new google.visualization.DataTable();
	data_time.addColumn('string', 'Time');
	data_time.addColumn('number', 'Average Wait Time');
	data_time.addRows(<?php echo count($Times); ?>);
	
	<?php
	$i = 0;
	foreach($Times as $time)
	{
	?>
	
	data_time.setValue(<?php echo $i; ?>, 0, '<?php echo $time['Line']['intimeblk60']; ?>');
	data_time.setValue(<?php echo $i; ?>, 1, <?php echo round($time[0]['AvgDiff']/60, 1); ?>);

	<?php
	$i++;
	}
	?>

	var chart_time = new google.visualization.BarChart(document.getElementById('chart_div_time'));
	chart_time.draw(data_time, {width: 380, height: 500, is3D: true, legend: 'bottom', axisFontSize: 14, legendFontSize: 16, titleFontSize: 16, isStacked: true, titleX: 'Wait Time (min.)', title: 'Time of Day (24-hour format)', min: 0});

  }
</script>

<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr>
	<td align="left">

		<form method="GET" action="/disambiguate/airports" name="search">
		
			<input name="basepath" type="hidden" value="/lines/security/" />
			
			<table border=0 cellpadding=0 cellspacing=0>
			<tr>
				<td>
					<div>Airport:</div>
				</td>
				<td width="5px"></td>
				<td>
					<input name="from" type="text" style="width: 125px;" value="<?php echo $Airport; ?>" />
				</td>
				<td width="25px"></td>
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
					<div>Time:</div>
				</td>
				<td width="5px"></td>
				<td>
					<select name="time">
						<option value=""></option>
						<option value="0" <?php if($Time=="0") echo 'selected'; ?>>00:00 - 00:59</option>
						<option value="1" <?php if($Time==1) echo 'selected'; ?>>01:00 - 01:59</option>
						<option value="2" <?php if($Time==2) echo 'selected'; ?>>02:00 - 02:59</option>
						<option value="3" <?php if($Time==3) echo 'selected'; ?>>03:00 - 03:59</option>
						<option value="4" <?php if($Time==4) echo 'selected'; ?>>04:00 - 04:59</option>
						<option value="5" <?php if($Time==5) echo 'selected'; ?>>05:00 - 05:59</option>
						<option value="6" <?php if($Time==6) echo 'selected'; ?>>06:00 - 06:59</option>
						<option value="7" <?php if($Time==7) echo 'selected'; ?>>07:00 - 07:59</option>
						<option value="8" <?php if($Time==8) echo 'selected'; ?>>08:00 - 08:59</option>
						<option value="9" <?php if($Time==9) echo 'selected'; ?>>09:00 - 09:59</option>
						<option value="10" <?php if($Time==10) echo 'selected'; ?>>10:00 - 10:59</option>
						<option value="11" <?php if($Time==11) echo 'selected'; ?>>11:00 - 11:59</option>
						<option value="12" <?php if($Time==12) echo 'selected'; ?>>12:00 - 12:59</option>
						<option value="13" <?php if($Time==13) echo 'selected'; ?>>13:00 - 13:59</option>
						<option value="14" <?php if($Time==14) echo 'selected'; ?>>14:00 - 14:59</option>
						<option value="15" <?php if($Time==15) echo 'selected'; ?>>15:00 - 15:59</option>
						<option value="16" <?php if($Time==16) echo 'selected'; ?>>16:00 - 16:59</option>
						<option value="17" <?php if($Time==17) echo 'selected'; ?>>17:00 - 17:59</option>
						<option value="18" <?php if($Time==18) echo 'selected'; ?>>18:00 - 18:59</option>
						<option value="19" <?php if($Time==19) echo 'selected'; ?>>19:00 - 19:59</option>
						<option value="20" <?php if($Time==20) echo 'selected'; ?>>20:00 - 20:59</option>
						<option value="21" <?php if($Time==21) echo 'selected'; ?>>21:00 - 21:59</option>
						<option value="22" <?php if($Time==22) echo 'selected'; ?>>22:00 - 22:59</option>
						<option value="23" <?php if($Time==23) echo 'selected'; ?>>23:00 - 23:59</option>
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

<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr>
	<td align="center">
	
		<table border=0 cellpadding=0 cellspacing=0 width="800px">
		<tr>
			<td align="left">
				<h1 style="margin: 0px">Airport Security Lines - <?php echo $Airport; ?></h1>
				<div class="info" style="margin-bottom: 2em;"><?php echo $City; ?></div>
				
				<div class="header">
					Real-Time Data
				</div>
				<br />
				
				<table border=0 cellpadding=5 cellspacing=0 style="background-color: yellow;">
				<tr>
					<td>
						<div>
							<b><?php echo round($Realtime[0][0]['AvgDiff'] / 60,1); ?></b> (+/- <?php echo round($Realtime[0][0]['StdDiff'] / 60, 1); ?>) minutes
						</div>
					</td>
				</tr>
				</table>
				<div class="info">
					Based on <?php echo $Realtime[0][0]['NumEntries']; ?> entries in the past 30 minutes.
				</div>
				<br /><br />
				
				<div class="header">
					Historical (Average) Data
				</div>
				<br /><br />
				
				<table border=0 cellpadding=0 cellspacing=0 width="100%">
				<tr>
					
					<td align="left">
						<div id='chart_div_day'></div>
					</td>
					<td align="right">
						<div id='chart_div_time'></div>
					</td>
				</tr>
				</table>
				
				
			</td>
		</tr>
		</table>
		
	</td>
</tr>
</table>

