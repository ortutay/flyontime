<?php $this->pageTitle = 'FlyOnTime.us: Airports'; ?>

<div class="subheader">Modify your search:</div>
<br />

<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr>
	<td align="center">

		<form method="GET" action="/disambiguate/airports">
			
			<table border=0 cellpadding=0 cellspacing=0>
			<tr>
				<td>
					<div>From:</div>
				</td>
				<td width="5px"></td>
				<td>
					<input name="from" type="text" style="width: 125px;" value="<?php echo $From; ?>" />
				</td>
				<td width="15px"></td>
				<td>
					<div>To:</div>
				</td>
				<td width="5px"></td>
				<td>
					<input name="to" type="text" style="width: 125px;" value="<?php echo $To; ?>" />
				</td>
				<td width="15px"></td>
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
					<input type="submit" value="Search >>" />
				</td>
				
			</tr>
			</table>
			
		</form>
		
	</td>
</tr>
</table>

<br />
<div class="header">
	Most On-Time Flights
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
<tr valign="top">
	<td align="center">
		
		<div class="subheader">
			<u>From <?php echo $From; ?> to <?php echo $To; ?>:</u>
		</div>
		
		<br />
		
		<table border=0 cellpadding=5 cellspacing=0>
		<tr>
			<td><div><b>Flight</b></div></td>
			<td><div><b>Averge Arrival</b></div></td>
		</tr>
		
		<?php
		$i = 0;
		foreach($FlightsFrom as $flight)
		{
			$style = '';
			
			$delay = round($flight[0]['AvgArrDelay'], 1);
			
			$delay_style = '';
			$delay_str = '';
			if($delay < 0)
			{
				$delay_str = abs($delay).' min. early';
				$delay_style = 'color: green;';
			}
			elseif($delay > 0)
			{
				$delay_str = $delay.' min. late';
				$delay_style = 'color: red;';
			}
			else
			{
				$delay_str = 'on time';
				$delay_style = 'color: black;';
			}
			
			if(($i % 2) == 0)
				$style = 'background-color: #BBBBBB;';
		?>
		
		<tr style="<?php echo $style; ?>">
			<td><a href="/flights?airline=<?php echo $flight['Log']['UniqueCarrier']; ?>&flight_num=<?php echo $flight['Log']['FlightNum']; ?>&from=<?php echo $From; ?>&to=<?php echo $To; ?>&day=<?php echo $Day; ?>"><?php echo $flight['Log']['Carrier'].' '.$flight['Log']['FlightNum']; ?></a></td>
			<td><div style="<?php echo $delay_style; ?>"><?php echo $delay_str; ?></div></td>
		</tr>
		
		<?php
			$i++;
		}
		?>
		
		</table>
		
	</td>
	
	<td align="center">
		
		<div class="subheader">
			<u>From <?php echo $To; ?> to <?php echo $From; ?>:</u>
		</div>
		
		<br />
		
		<table border=0 cellpadding=5 cellspacing=0>
		<tr>
			<td><div><b>Flight</b></div></td>
			<td><div><b>Averge Arrival</b></div></td>
		</tr>
		
		<?php
		$i = 0;
		foreach($FlightsTo as $flight)
		{
			$style = '';
			
			$delay = round($flight[0]['AvgArrDelay'], 1);
			
			$delay_style = '';
			$delay_str = '';
			if($delay < 0)
			{
				$delay_str = abs($delay).' min. early';
				$delay_style = 'color: green;';
			}
			elseif($delay > 0)
			{
				$delay_str = $delay.' min. late';
				$delay_style = 'color: red;';
			}
			else
			{
				$delay_str = 'on time';
				$delay_style = 'color: black;';
			}
			
			if(($i % 2) == 0)
				$style = 'background-color: #BBBBBB;';
		?>
		
		<tr style="<?php echo $style; ?>">
			<td><a href="/flights?airline=<?php echo $flight['Log']['UniqueCarrier']; ?>&flight_num=<?php echo $flight['Log']['FlightNum']; ?>&from=<?php echo $To; ?>&to=<?php echo $From; ?>&day=<?php echo $Day; ?>"><?php echo $flight['Log']['Carrier'].' '.$flight['Log']['FlightNum']; ?></a></td>
			<td><div style="<?php echo $delay_style; ?>"><?php echo $delay_str; ?></div></td>
		</tr>
		
		<?php
			$i++;
		}
		?>
		
		</table>
		
	</td>
</tr>
</table>

<br />

