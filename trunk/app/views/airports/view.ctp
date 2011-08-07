<?php

$HolidayNames = array(
		'memorial-1' => 'Day Before Memorial Day',
		'memorial' => 'Memorial Day',
		'memorial+1' => 'Day After Memorial Day',
		'labor' => 'Labor Day',
		'thanksgiving-1' => 'Wednesday Before Thanksgiving Day',
		'thanksgiving' => 'Thanksgiving Day',
		'thanksgiving+1' => 'Friday After Thanksgiving Day',
		'thanksgiving+2' => 'Saturday After Thanksgiving Day',
		'thanksgiving+3' => 'Sunday After Thanksgiving Day',
		'christmas-1' => 'Day Before Christmas Day',
		'christmas' => 'Christmas Day',
		'christmas+1' => 'Day After Christmas Day',
		);

if ($Carrier != '') {
	$this->pageTitle = 'FlyOnTime.us: ' . $CarrierName . ' Flight ' . $FlightNum . ' (' . $From . ' to ' . $To . ')';
	$this->set('MetaDescription', 'On-time statistics for ' . $CarrierName . ' Flight ' . $FlightNum . ' from ' . $From . ' to ' . $To . ', including percent on time, average delays, and best days to fly.');
} else if ($To != '') {
	$this->pageTitle = 'FlyOnTime.us: ' . $From . ' to ' . $To;
	$this->set('MetaDescription', 'Flight on-time statistics for ' . $From . ' to ' . $To . ', including percent on time, average delays, best flights, and best days to fly.');
} else {
	$this->pageTitle = 'FlyOnTime.us: Flights From ' . $From;
	$this->set('MetaDescription', 'Flight on-time statistics for flights from ' . $From . ', including percent on time, average delays, best days to fly, and holiday delays.');
}

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

function GetTimeName($hour)
{
	if ($hour < 12) {
		if ($hour == 0) { $hour = 12; }
		return round($hour) . " am";
	} else {
		$hour = $hour - 12;
		if ($hour == 0) { $hour = 12; }
		return $hour . " pm";
	}
}


function DelayText($delay, $relative_to='', $colorize=1)
{
	$delay_style = '';
	$delay_str = '';
	$delay_unit = 'min.';
	
	if ($relative_to != '') {
		$delay -= $relative_to;
	}
	
	if (abs($delay) >= 120) {
		$delay = round($delay / 60, 1);
		$delay_unit = 'hrs.';
	}
	
	if($delay < 0) {
		$delay_str = abs($delay).' '.$delay_unit.' early';
		$delay_style = 'color: green;';
	} else if ($delay > 0) {
		$delay_str = abs($delay).' '.$delay_unit.' late';
		$delay_style = 'color: red;';
	} else {
		$delay_str = 'on time';
		$delay_style = 'color: black;';
	}
	if (!$colorize) { $delay_style = ''; }
	?> <span style="<?php echo $delay_style; ?>"><?php echo $delay_str; ?></span> <?php
}

function FlightCondition($label, $data, $mincount, $subhead, $alldata) {
	if ($data['Ontime']['count'] >= $mincount) {
	?>
	<tr style="<?php if ($subhead) { echo "font-size: 80%; color: #944"; } ?>">
		<td style="<?php if ($subhead) { echo "padding-left: 1em"; } ?>"><?php echo $label?> <?php if ($subhead) { ?><span style='font-size: 75%; color: #C88'>(<?php echo round($data['Ontime']['count']/$alldata['Ontime']['count']*100)?>%)</span><?php }?></td>
		<td><?php DelayText($data['Ontime']['delay_median'], '', 0) ?></td>
		<td><?php DelayText($data['Ontime']['delay_85thpctile'], '', 0) ?></td>
		<td><?php echo round($data['Ontime']['pct_cancel']*100) ?>% </td>
	</tr>
	<?php }
}

function NiceDate($date)
{
	return substr($date, 5, 2) . '/' . substr($date, 8, 2) . '/' . substr($date, 0, 4);
}

?>

<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load("visualization", "1", {packages:["barchart","piechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
	  
	<?php if ($Summary['Ontime']['count'] > 0) { ?>

	var data_outcome = new google.visualization.DataTable();	  
	data_outcome.addColumn('string', 'Flight Outcome');
	data_outcome.addColumn('number', '% of Flights');
	data_outcome.addRows(4);
	data_outcome.setValue(0, 0, 'On Time');
	data_outcome.setValue(1, 0, '5-20 min.');
	data_outcome.setValue(2, 0, '>20 min.');
	data_outcome.setValue(3, 0, 'Can./Div.');
	data_outcome.setValue(0, 1, <?php echo round($Summary['Ontime']['pct_ontime']*$Summary['Ontime']['count']); ?>);
	data_outcome.setValue(1, 1, <?php echo round((1-$Summary['Ontime']['pct_ontime']-$Summary['Ontime']['pct_20mindelay']-$Summary['Ontime']['pct_cancel'])*$Summary['Ontime']['count']); ?>);
	data_outcome.setValue(2, 1, <?php echo round($Summary['Ontime']['pct_20mindelay']*$Summary['Ontime']['count']); ?>);
	data_outcome.setValue(3, 1, <?php echo round($Summary['Ontime']['pct_cancel']*$Summary['Ontime']['count']); ?>);
	var chart_outcome = new google.visualization.PieChart(document.getElementById('chart_div_outcome'));
	chart_outcome.draw(data_outcome, {width: 220, height: 200, is3D: true, legend: 'none', legendFontSize: 11});
	
	<?php
	if ($To != '' && $Carrier == '') {
	?>
	
    //AIRLINE FROM
	var data_airline_from = new google.visualization.DataTable();
	data_airline_from.addColumn('string', 'Airline');
	data_airline_from.addColumn('number', '% On-Time Arrival');
	data_airline_from.addRows(<?php echo count($BestAirlines); ?>);
	
	<?php
	$i = 0;
	foreach($BestAirlines as $airline)
	{
	?>
	data_airline_from.setValue(<?php echo $i; ?>, 0, '<?php echo $AirlineNames[$airline['Ontime']['carrier']]; ?>');
	data_airline_from.setValue(<?php echo $i; ?>, 1, <?php echo round($airline[0]['carrier_ontime']/$airline[0]['carrier_count']*100, 1); ?>);

	<?php
	$i++;
	}
	?>
	
	var chart_airline_from = new google.visualization.BarChart(document.getElementById('chart_div_airline_from'));
	chart_airline_from.draw(data_airline_from, {width: 325, height: 400, is3D: true, legend: 'bottom', axisFontSize: 14, legendFontSize: 16, min: 0, max: 100});
	

	<?php	
	}
	}
	?>

	//DAY FROM
	var data_day_from = new google.visualization.DataTable();
	data_day_from.addColumn('string', 'Day');
	data_day_from.addColumn('number', 'On Time');
	data_day_from.addColumn('number', '5-20 min. Delay');
	data_day_from.addColumn('number', '>20 min. Delay');
	data_day_from.addColumn('number', 'Cancelled/Diverted');
	data_day_from.addRows(<?php echo count($DaysFrom); ?>);
	
	<?php
	$i = 0;
	foreach($DaysFrom as $day)
	{
	?>

	data_day_from.setValue(<?php echo $i; ?>, 0, '<?php echo GetDayName($day['Ontime']['dayofweek']); ?>');
	data_day_from.setValue(<?php echo $i; ?>, 1, <?php echo round($day['Ontime']['pct_ontime']*100); ?>);
	data_day_from.setValue(<?php echo $i; ?>, 2, <?php echo 100-round($day['Ontime']['pct_ontime']*100)-round($day['Ontime']['pct_20mindelay']*100)-round($day['Ontime']['pct_cancel']*100); ?>);
	data_day_from.setValue(<?php echo $i; ?>, 3, <?php echo round($day['Ontime']['pct_20mindelay']*100); ?>);
	data_day_from.setValue(<?php echo $i; ?>, 4, <?php echo round($day['Ontime']['pct_cancel']*100); ?>);

	<?php
	$i++;
	}
	?>

	var chart_day_from = new google.visualization.BarChart(document.getElementById('chart_div_day_from'));
	chart_day_from.draw(data_day_from, {width: 380, height: 500, is3D: true, legend: 'bottom', axisFontSize: 14, legendFontSize: 16, titleFontSize: 16, isStacked: true, titleX: '% of Flights', title: 'Day of Week', min: 0, max: 100});

	//TIME FROM
	var data_time_from = new google.visualization.DataTable();
	data_time_from.addColumn('string', 'Time');
	data_time_from.addColumn('number', 'On Time');
	data_time_from.addColumn('number', '5-20 min. Delay');
	data_time_from.addColumn('number', '>20 min. Delay');
	data_time_from.addColumn('number', 'Cancelled/Diverted');
	data_time_from.addRows(<?php echo count($TimesFrom); ?>);
	
	<?php
	$i = 0;
	foreach($TimesFrom as $time)
	{
	?>
	
	data_time_from.setValue(<?php echo $i; ?>, 0, '<?php echo GetTimeName($time['Ontime']['hour']); ?>');
	data_time_from.setValue(<?php echo $i; ?>, 1, <?php echo round($time['Ontime']['pct_ontime']*100); ?>);
	data_time_from.setValue(<?php echo $i; ?>, 2, <?php echo 100-round($time['Ontime']['pct_ontime']*100)-round($time['Ontime']['pct_20mindelay']*100)-round($time['Ontime']['pct_cancel']*100); ?>);
	data_time_from.setValue(<?php echo $i; ?>, 3, <?php echo round($time['Ontime']['pct_20mindelay']*100); ?>);
	data_time_from.setValue(<?php echo $i; ?>, 4, <?php echo round($time['Ontime']['pct_cancel']*100); ?>);

	<?php
	$i++;
	}
	?>

	var chart_time_from = new google.visualization.BarChart(document.getElementById('chart_div_time_from'));
	
	chart_time_from.draw(data_time_from, {width: 380, height: 500, is3D: true, legend: 'bottom', axisFontSize: 14, legendFontSize: 16, titleFontSize: 16, isStacked: true, titleX: '% of Flights', title: 'Time of Day', min: 0, max: 100});
  }
  
  function swap_search()
  {
  	var temp = document.search.from.value;
  	document.search.from.value = document.search.to.value;
  	document.search.to.value = temp;
  }
</script>

<table border=0 cellpadding=0 cellspacing=0 width="100%" style="border-bottom: 1px solid black; padding: 5px">
<tr>
	<td align="left">

		<form method="GET" action="/disambiguate/airports" name="search">
			
		<table border=0 cellpadding=0 cellspacing=0 style="font-size: 9pt">
			<tr>
				<td>
					<div>Flights From:</div>
				</td>
				<td width="5px"></td>
				<td>
					<input name="from" type="text" style="width: 125px; font-size: 9pt" value="<?php echo $From; ?>" />
				</td>
				<td width="10px"></td>
				<td><a href="javascript: swap_search();"><img border=0 src="/img/swap.png" /></a></td>
				<td width="10px"></td>
				<td>
					<div>To:</div>
				</td>
				<td width="5px"></td>
				<td>
					<input name="to" type="text" style="width: 125px; font-size: 9pt" value="<?php echo $To; ?>" />
					<span style="font-style: italic; color: #777">(optional)</span>
				</td>
				<td width="25px"></td>
				<td>
					<input type="submit" value="Search >>" style="font-size: 9pt"/>
				</td>
				
			</tr>
			</table>
			
		</form>
		
	</td>
</tr>
</table>

<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr>
	<td align="center">
	
		<table border=0 cellpadding=0 cellspacing=0 width="800px" style="margin-top: 1.5em">
		<tr>
			<td align="left">
				<h1 style="margin: 0px"><?php
					if ($Carrier != '') {
						echo "<div>" . $CarrierName . ' Flight ' . $FlightNum . '</div>';
						echo "<div>" . $From . ' to ' . $To . '</div>';
					} else {
						echo "<div>" . $FromCity . ' (' . $From . ')</div>';
						if ($To != '') {
							echo "<div>to " . $ToCity . ' (' . $To . ')</div>';
						}
					}
				?>
				</h1>
				
				<?php
				// FAA airport delay data. Has general airport delays but not flight-specific delays.
				$faa_warnings = "";
				foreach ($curaptdelays->Delay_type as $delaytype) {
					if (isset($delaytype->Airport_Closure_List)) {
						foreach ($delaytype->Airport_Closure_List->Airport as $airport) {
							if ($airport->ARPT == $From) {
								$faa_warnings .= $airport->ARPT . " is currently closed due to " . $airport->Reason . "! It is scheduled to reopen at " . $airport->Reopen . " (since " . $airport->Start . ") ";
							}
						}
					}
					if (isset($delaytype->Ground_Stop_List)) {
						foreach ($delaytype->Ground_Stop_List->Program as $program) {
							if ($program->ARPT == $From || $program->ARPT == $To) {
								$faa_warnings .= $program->ARPT . " has stopped ground traffic due to " . $program->Reason . " but is expected to resume flights at " . $program->End_Time . " ";
							}
						}
					}
					if (isset($delaytype->Ground_Delay_List)) {
						foreach ($delaytype->Ground_Delay_List->Ground_Delay as $grnddelay) {
							if ($grnddelay->ARPT == $From || $grnddelay->ARPT == $To) {
								$faa_warnings .= $grnddelay->ARPT . " is currently having ground delays around " . $grnddelay->Avg . " due to " . $grnddelay->Reason . ". ";
							}
						}
					}
					if (isset($delaytype->Arrival_Departure_Delay_List)) {
						foreach ($delaytype->Arrival_Departure_Delay_List->Delay as $delay) {
							$arpt = $delay->ARPT;
							foreach ($delay->Arrival_Departure as $ad) {
								if (($arpt == $From && $ad["Type"] == "Departure") || ($arpt == $To && $ad["Type"] == "Arrival")) {
									if ($delay->Arrival_Departure->Trend == "Decreasing") {
										$trend = "but the delay is improving";
									} else if ($delay->Arrival_Departure->Trend == "Increasing") {
										$trend = "and is getting worse";
									}
									$faa_warnings .= $delay->ARPT . " is currently having departure delays of " . $delay->Arrival_Departure->Min . " to " . $delay->Arrival_Departure->Max . " due to " . $delay->Reason . " " . $trend . ". ";
								}
							}
						}
					}
				}
				if ($faa_warnings != "") {
					echo "<p class=\"currentdelay\">" . $faa_warnings . " (Source: <a href='http://fly.faa.gov/flyfaa/usmap.jsp'>FAA</a>)</p>";
				}
				?>
	
				<?php if ($Summary['Ontime']['count'] > 0) { ?>
				<div class="header" style="margin-top: 2em; margin-bottom: 1em">
					Flight Delay Summary
				</div>
				
				<table>
				<tr valign="top">
				
				<td style="padding-right: 1em; border-right: 1px solid #aaa" width="230">
					<div class="info" style="text-align: center">
						Based on <?php echo $Summary['Ontime']['count'] ?> flights from <?php echo NiceDate($Summary['Ontime']['firstdate']) ?> to <?php echo NiceDate($Summary['Ontime']['lastdate']) ?>
					</div>
					<div id="chart_div_outcome"></div>
					
					<table style="font-size: 90%; margin-top: .5em; margin-left: 1em">
					<tr>
						<td><?php echo round($Summary["Ontime"]["pct_ontime"]*100) ?>%</td>
						<th style="color: #66F; padding-left: .25em">On Time</th>
					</tr>
					<tr>
						<td><?php echo round((1-$Summary["Ontime"]["pct_ontime"]-$Summary["Ontime"]["pct_20mindelay"]-$Summary["Ontime"]["pct_cancel"])*100) ?>%</td>
						<th style="color: #F66; padding-left: .25em">5-20 min. Delay</th>
					</tr>
					<tr>
						<td><?php echo round($Summary["Ontime"]["pct_20mindelay"]*100) ?>%</td>
						<th style="color: #B80; padding-left: .25em">&gt;20 min. Delay</th>
					</tr>
					<tr>
						<td><?php echo round($Summary["Ontime"]["pct_cancel"]*100) ?>%</td>
						<th style="color: #070; padding-left: .25em">Cancelled/Diverted</th>
					</tr>
					</table>
					
				</td>
				
				<td style="padding-left: 1em">
				<?php if ($SummaryGoodWeather["Ontime"]["count"] >= 10 && $SummaryBadWeather["Ontime"]["count"] >= 10) { ?>
				<table border=0 cellpadding=4 cellspacing=1>
				<tr>	
					<td/>
					<th style="padding-right: 1em">Average<div style='font-size: 70%; color: #666; font-weight: normal'>(median)</div></th>
					<th style="padding-right: 1em">Be Prepared For<div style='font-size: 70%; color: #666; font-weight: normal'>(85<sup>th</sup> percentile)</div></th>
					<th>Cancelled<div style='font-size: 70%; color: #666; font-weight: normal'>or diverted</div></th>
				</tr>

				<?php
				FlightCondition('In Good Weather...', $SummaryGoodWeather, 10, 0, $Summary);
				FlightCondition('In Bad Weather...', $SummaryBadWeather, 10, 0, $Summary);
				FlightCondition('In Fog...', $SummaryFog, 20, 1, $Summary);
				FlightCondition('In Rain...', $SummaryRain, 20, 1, $Summary);
				FlightCondition('In Snow...', $SummarySnow, 20, 1, $Summary);
				FlightCondition('In Hail...', $SummaryHail, 20, 1, $Summary);
				FlightCondition('In Thunder...', $SummaryThunder, 20, 1, $Summary);
				FlightCondition('Tornado Spotted...', $SummaryTornado, 10, 1, $Summary);
				?>
				</table>

				<p class="info" style="margin-bottom: 2em;">&ldquo;Be Prepared For&rdquo;
				gives the longest delay you can reasonably expect to occur.
				Only the unluckiest 15 percent of flights experience longer delays.</p>
				
				<?php
				if ($WeatherInfo['Weather']['station'] != '') {
					echo "<p>";
					echo "<img src=\"" . $curobs->icon_url_base . $curobs->icon_url_name . "\" style=\"float: left; margin: 4px 1em 1em 0px\" border='1'/>";
					echo "Current weather is ";
					echo $curobs->temperature_string;
					echo " and ";
					echo $curobs->weather;
					echo ", ";
					echo $curobs->relative_humidity;
					echo "% humidity, and ";
					echo $curobs->visibility_mi;
					echo " miles visibility <span style='font-size: 70%; color: #555'>at <a href=\"";
					echo 'http://www.weather.gov/xml/current_obs/' . $WeatherInfo['Weather']['station']  . '.xml';
					echo "\">";
					echo $WeatherInfo['Weather']['station_descr'];
					echo "</a> as of ";
					echo $curobs->observation_time_rfc822;
					echo "</span>.</p>";
					
				}
				} else {
					echo "<p>Detailed flight statistics based on historical weather data is not available for this airport or flight path.</p>";
				}
				?>
				</td>
				</tr>
				</table>
				
				<br />

				<?php if ($To == '') { ?>
				<div class="header">
					Top Destinations from <?php echo $From ?>
				</div>
				<table border=0 cellpadding=0 cellspacing=0 width="100%" style="margin-top: 1em">
				<tr valign="top">
					<td align="left">
						<table border=0 cellpadding=5 cellspacing=1>
						<tr>
							<td><div><b><?php echo $From ?> to ...</b></div></td>
							<td><div><b>Averge Arrival</b></div></td>
						</tr>
						<?php
						$i = 0;
						foreach($BestDestinations as $flight)
						{
							$style = '';
							if(($i % 2) == 0)
								$style = 'background-color: #DDDDDD;';
						?>
						<tr style="<?php echo $style; ?>">
							<td><a href="/routes/<?php echo $From ?>/<?php echo $flight['Ontime']['dest']; ?>"><?php echo $flight['Ontime']['dest'] ?></a>
							<span style='font-size: 75%; color: #666'>(<?php echo $flight['Ontime']['count']; ?> flights)</span></td>
							<td><?php DelayText($flight['Ontime']['delay_median']) ?></td>
						</tr>
						<?php
							$i++;
						}
						?>
						</table>
					</td>
					<td align="right">
					</td>
				</tr>
				</table>	
				<?php } ?>
				
				<?php if ($To != '' && $Carrier == '') { ?>
				<div class="header">
					Most On-Time Flights &amp; Airlines
				</div>

				<br/>
				
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
						foreach($BestFlights as $flight)
						{
							$style = '';
							if(($i % 2) == 0)
								$style = 'background-color: #DDDDDD;';
						?>
						
						<tr style="<?php echo $style; ?>">
							<td><a href="/flights/<?php echo $flight['Ontime']['carrier']; ?>/<?php echo $flight['Ontime']['flightnum']; ?>/<?php echo $From; ?>/<?php echo $To; ?>"><?php echo $AirlineNames[$flight['Ontime']['carrier']].' '.$flight['Ontime']['flightnum']; ?></a>
							<span style='font-size: 75%; color: #666'>(<?php echo $flight['Ontime']['count']; ?> flights)</span></td>
							<td><?php DelayText($flight['Ontime']['delay_median']) ?></td>
						</tr>
						
						<?php
							$i++;
						}
						?>
						
						</table>
				
					</td>
					
					<td align="right">
					
						<div id='chart_div_airline_from'></div>
				
					</td>
				</tr>
				</table>	
				<?php } ?>
				
				<?php if ($Carrier != '' && $FlightComparison != '') { ?>
					<div class="header">Comparison to Other Flights Between <?php echo $From ?> and <?php echo $To ?></div>
					<p>This flight's average arrival delay is <?php echo $FlightComparison ?> of <a href="/routes/<?php echo $From ?>/<?php echo $To ?>">other flights between these airports</a>.</p>
				<?php } ?>
				
				<?php } else { ?>
					
				<p>There are no records for flights between these airports. Instead see <a href="/airports/<?php echo $From ?>">all flights departing from <?php echo $From ?></a>.</p>
					
				<?php } ?>
				
				<br/>
			
				<div class="header">Best Days and Times to Fly from <?php echo $From ?></div>
				<?php if ($To != '') { ?> <div class="info" style="margin-bottom: 1em;">Based on all flights originating at <?php echo $FromCity ?>.</div> <?php } ?>
				
				<table border=0 cellpadding=0 cellspacing=0 width="100%">
				<tr>
					
					<td align="left">
						<div id='chart_div_day_from'></div>
					</td>
					<td align="right">
						<div id='chart_div_time_from'></div>
					</td>
				</tr>
				</table>

				<div class="header" style="margin-top: 1em">
					Holiday Delays at <?php echo $From ?>
				</div>
				<?php if ($To != '') { ?> <div class="info" style="margin-bottom: 1em;">Based on all flights originating at <?php echo $FromCity ?>.</div> <?php } ?>
				<br/>
				<table border=0 cellpadding=0 cellspacing=0 width="100%">
				<tr valign="top">
					<td align="left">
					
						<table border=0 cellpadding=5 cellspacing=1>
						<tr>
							<td><div><b>Holiday</b></div></td>
							<td><div><b>Averge Arrival</b></div></td>
							<td><div><b>Be Prepared For</b></div></td>
							<td><div><b>Cancelled</b></div></td>
						</tr>
						
						<tr style="font-style: italic">
							<td>Most Days</td>
							<td><?php DelayText($Summary['Ontime']['delay_median'])?></td>
							<td><?php DelayText($Summary['Ontime']['delay_85thpctile'])?></td>
							<td><?php echo round($Summary['Ontime']['pct_cancel']*100)?>%</td>
						</tr>
						
						<?php
						$i=1;
						foreach($Holidays as $flight)
						{
							if ($flight['Ontime']['count'] == 0) { continue; }
							$i++;
							$style = '';
							if(($i % 2) == 0)
								$style = 'background-color: #DDDDDD;';
						?>
						
						<tr style="<?php echo $style; ?>">
							<td><?php echo $HolidayNames[$flight['Ontime']['holiday']] ?>
							<span style='font-size: 75%; color: #666'>(<?php echo $flight['Ontime']['count']; ?> flights)</span></td>
							<td><?php DelayText($flight['Ontime']['delay_median'])?></td>
							<td><?php DelayText($flight['Ontime']['delay_85thpctile'])?></td>
							<td><?php echo round($flight['Ontime']['pct_cancel']*100)?>%</td>
						</tr>
						<?php
						}
						?>
						
						
						</table>
				
					</td>
				</tr>
				</table>				
			</td>
		</tr>
		</table>

	</td>
</tr>
</table>

<br /><br />


