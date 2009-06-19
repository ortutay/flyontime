<routes>
	<route>
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
		<from>
			<code><?php echo $From; ?></code>
			<city><?php echo $FromCity; ?></city>
		</from>
		<to>
			<code><?php echo $To; ?></code>
			<city><?php echo $ToCity; ?></city>
		</to>
		<day><?php echo $Day; ?></day>
		<flights>
			<?php
			foreach($FlightsFrom as $flight)
			{
			?>
			<flight>
				<unique_carrier><?php echo $flight['Log']['UniqueCarrier']; ?></unique_carrier>
				<airline_short_name><?php echo $AirlineNames[$flight['Log']['UniqueCarrier']]; ?></airline_short_name>
				<flight_num><?php echo $flight['Log']['FlightNum']; ?></flight_num>
				<average_arrival_delay><?php echo $flight[0]['AvgArrDelay']; ?></average_arrival_delay>
			</flight>
			<?php
			}
			?>
		</flights>
		<airlines>
			<?php
			foreach($AirlinesFrom as $airline)
			{
			?>
			<airline>
				<unique_carrier><?php echo $airline['Log']['UniqueCarrier']; ?></unique_carrier>
				<airline_short_name><?php echo $AirlineNames[$airline['Log']['UniqueCarrier']]; ?></airline_short_name>
				<percent_on_time><?php echo $airline[0]['PercentOnTime']; ?></percent_on_time>
			</airline>
			<?php
			}
			?>
		</airlines>
	</route>
	<route>
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
		<from>
			<code><?php echo $To; ?></code>
			<city><?php echo $ToCity; ?></city>
		</from>
		<to>
			<code><?php echo $From; ?></code>
			<city><?php echo $FromCity; ?></city>
		</to>
		<day><?php echo $Day; ?></day>
		<flights>
			<?php
			foreach($FlightsTo as $flight)
			{
			?>
			<flight>
				<unique_carrier><?php echo $flight['Log']['UniqueCarrier']; ?></unique_carrier>
				<airline_short_name><?php echo $AirlineNames[$flight['Log']['UniqueCarrier']]; ?></airline_short_name>
				<flight_num><?php echo $flight['Log']['FlightNum']; ?></flight_num>
				<average_arrival_delay><?php echo $flight[0]['AvgArrDelay']; ?></average_arrival_delay>
			</flight>
			<?php
			}
			?>
		</flights>
		<airlines>
			<?php
			foreach($AirlinesTo as $airline)
			{
			?>
			<airline>
				<unique_carrier><?php echo $airline['Log']['UniqueCarrier']; ?></unique_carrier>
				<airline_short_name><?php echo $AirlineNames[$airline['Log']['UniqueCarrier']]; ?></airline_short_name>
				<percent_on_time><?php echo $airline[0]['PercentOnTime']; ?></percent_on_time>
			</airline>
			<?php
			}
			?>
		</airlines>
	</route>
</routes>