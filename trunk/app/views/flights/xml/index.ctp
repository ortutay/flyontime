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
			<days>
				<?php
				foreach($stats['days'] as $day_index => $day)
				{
				?>
				<day>
					<index><?php echo $day_index; ?></index>
					<scheduled><?php echo $day['total']; ?></scheduled>
					<on_time><?php echo ($day['total'] - $day['delayed'] - $day['cancelled'] - $day['diverted']); ?></on_time>
					<late><?php echo $day['delayed']; ?></late>
					<cancelled><?php echo $day['cancelled']; ?></cancelled>
					<diverted><?php echo $day['diverted']; ?></diverted>
				</day>
				<?php
				}
				?>
			</days>
			<times>
				<?php
				foreach($stats['times'] as $time_block => $time)
				{
				?>
				<time>
					<block><?php echo $time_block; ?></block>
					<scheduled><?php echo $time['total']; ?></scheduled>
					<on_time><?php echo ($time['total'] - $time['delayed'] - $time['cancelled'] - $time['diverted']); ?></on_time>
					<late><?php echo $time['delayed']; ?></late>
					<cancelled><?php echo $time['cancelled']; ?></cancelled>
					<diverted><?php echo $time['diverted']; ?></diverted>
				</time>
				<?php
				}
				?>
			</times>
		</route>
		<?php
		}
		?>
	</routes>
</flight>