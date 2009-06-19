<flight>
	<months>
		<?php
		foreach($Months as $month => $foo)
		{
		?>
		<month><?php echo $month; ?></month>
		<?php
		}
		?>
	</months>
	<unique_carrier><?php echo $Airline ?></unique_carrier>
	<airline_name><?php echo $AirlineInfo['Enum']['description']; ?></airline_name>
	<flight_num><?php echo $FlightNum ?></flight_num>
	<routes>
		<?php
		foreach($AirportPairStats as $airport_pair => $stats)
		{
			$OriginCityName = $AirportPairFlights[$airport_pair][0]['Log']['OriginCityName'];
			$Origin = $AirportPairFlights[$airport_pair][0]['Log']['Origin'];
			
			$DestCityName = $AirportPairFlights[$airport_pair][0]['Log']['DestCityName'];
			$Dest = $AirportPairFlights[$airport_pair][0]['Log']['Dest'];
		?>
		<route>
			<from>
				<code><?php echo $Origin; ?></code>
				<city><?php echo $OriginCityName; ?></city>
			</from>
			<to>
				<code><?php echo $Dest; ?></code>
				<city><?php echo $DestCityName; ?></city>
			</to>
			<day><?php echo $Day; ?></day>
			<scheduled><?php echo $stats['total']; ?></scheduled>
			<on_time><?php echo $stats['arrived_on_time']; ?></on_time>
			<late><?php echo ($stats['arrived'] - $stats['arrived_on_time']); ?></late>
			<cancelled><?php echo $stats['cancelled']; ?></cancelled>
			<diverted><?php echo $stats['diverted']; ?></diverted>
			<average_arrival_delay><?php echo round($stats['avg_arrival_delay'], 4); ?></average_arrival_delay>
		</route>
		<?php
		}
		?>
	</routes>
</flight>