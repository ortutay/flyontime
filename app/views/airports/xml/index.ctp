<arrival_delays>
	<route>
		<from>
			<code><?php echo $From; ?></code>
			<city><?php echo $FromCity; ?></city>
		</from>
		<?php if ($To != '') { ?>
		<to>
			<code><?php echo $To; ?></code>
			<city><?php echo $ToCity; ?></city>
		</to>
		<?php } ?>
		<?php if ($Carrier != '') { ?>
		<carrier>
			<unique_carrier><?php echo $Carrier; ?></unique_carrier>
			<name><?php echo $CarrierName; ?></name>
		</carrier>
		<flight_number><?php echo $FlightNum; ?></flight_number>
		<?php } ?>
		<summary>
			<count><?php echo $Summary['Ontime']['count']; ?></count>
			<on_time><?php echo $Summary['Ontime']['pct_ontime']*100; ?></on_time>
			<short_delay><?php echo (1 - $Summary['Ontime']['pct_ontime'] - $Summary['Ontime']['pct_20mindelay'] - $Summary['Ontime']['pct_cancel'])*100; ?></short_delay>
			<long_delay><?php echo $Summary['Ontime']['pct_20mindelay']*100; ?></long_delay>
			<cancelled><?php echo $Summary['Ontime']['pct_cancel']*100; ?></cancelled>
			<percentile_15><?php echo $Summary['Ontime']['delay_15thpctile']; ?></percentile_15>
			<percentile_50><?php echo $Summary['Ontime']['delay_median']; ?></percentile_50>
			<percentile_85><?php echo $Summary['Ontime']['delay_85thpctile']; ?></percentile_85>
		</summary>
		<?php if ($To != '') { ?>
		<flights>
			<?php
			foreach($BestFlights as $flight)
			{
			?>
			<flight>
				<unique_carrier><?php echo $flight['Ontime']['carrier']; ?></unique_carrier>
				<airline_short_name><?php echo $AirlineNames[$flight['Ontime']['carrier']]; ?></airline_short_name>
				<flight_num><?php echo $flight['Ontime']['flightnum']; ?></flight_num>
				<count><?php echo $flight['Ontime']['count']; ?></count>
				<on_time><?php echo $flight['Ontime']['pct_ontime']*100; ?></on_time>
				<short_delay><?php echo (1 - $flight['Ontime']['pct_ontime'] - $flight['Ontime']['pct_20mindelay'] - $flight['Ontime']['pct_cancel'])*100; ?></short_delay>
				<long_delay><?php echo $flight['Ontime']['pct_20mindelay']*100; ?></long_delay>
				<cancelled><?php echo $flight['Ontime']['pct_cancel']*100; ?></cancelled>
				<percentile_15><?php echo $flight['Ontime']['delay_15thpctile']; ?></percentile_15>
				<percentile_50><?php echo $flight['Ontime']['delay_median']; ?></percentile_50>
				<percentile_85><?php echo $flight['Ontime']['delay_85thpctile']; ?></percentile_85>
			</flight>
			<?php
			}
			?>
		</flights>
		<airlines>
			<?php
			foreach($BestAirlines as $flight)
			{
			?>
			<airline>
				<unique_carrier><?php echo $flight['Ontime']['carrier']; ?></unique_carrier>
				<airline_short_name><?php echo $AirlineNames[$flight['Ontime']['carrier']]; ?></airline_short_name>
				<count><?php echo $flight[0]['carrier_count']; ?></count>
				<on_time><?php echo $flight[0]['carrier_ontime']/$flight[0]['carrier_count']*100; ?></on_time>
			</airline>
			<?php
			}
			?>
		</airlines>
		<?php } ?>
		<?php if ($To == '') { ?>
		<days>
			<?php
			foreach($DaysFrom as $day)
			{
			?>
			<day>
				<index><?php echo $day['Ontime']['dayofweek']; ?></index>
				<count><?php echo $day['Ontime']['count']; ?></count>
				<on_time><?php echo $day['Ontime']['pct_ontime']*100; ?></on_time>
				<short_delay><?php echo (1 - $day['Ontime']['pct_ontime'] - $day['Ontime']['pct_20mindelay'] - $day['Ontime']['pct_cancel'])*100; ?></short_delay>
				<long_delay><?php echo $day['Ontime']['pct_20mindelay']*100; ?></long_delay>
				<cancelled><?php echo $day['Ontime']['pct_cancel']*100; ?></cancelled>
				<percentile_15><?php echo $day['Ontime']['delay_15thpctile']; ?></percentile_15>
				<percentile_50><?php echo $day['Ontime']['delay_median']; ?></percentile_50>
				<percentile_85><?php echo $day['Ontime']['delay_85thpctile']; ?></percentile_85>
			</day>
			<?php
			}
			?>
		</days>
		<times>
			<?php
			foreach($TimesFrom as $day)
			{
			?>
			<time>
				<hour><?php echo $day['Ontime']['hour']; ?></hour>
				<count><?php echo $day['Ontime']['count']; ?></count>
				<on_time><?php echo $day['Ontime']['pct_ontime']*100; ?></on_time>
				<short_delay><?php echo (1 - $day['Ontime']['pct_ontime'] - $day['Ontime']['pct_20mindelay'] - $day['Ontime']['pct_cancel'])*100; ?></short_delay>
				<long_delay><?php echo $day['Ontime']['pct_20mindelay']*100; ?></long_delay>
				<cancelled><?php echo $day['Ontime']['pct_cancel']*100; ?></cancelled>
				<percentile_15><?php echo $day['Ontime']['delay_15thpctile']; ?></percentile_15>
				<percentile_50><?php echo $day['Ontime']['delay_median']; ?></percentile_50>
				<percentile_85><?php echo $day['Ontime']['delay_85thpctile']; ?></percentile_85>
			</time>
			<?php
			}
			?>
		</times>
		<?php } ?>
	</route>
</arrival_delays>