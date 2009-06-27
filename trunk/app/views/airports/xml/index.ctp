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
		<days>
			<?php
			foreach($DaysFrom as $day)
			{
			?>
			<day>
				<index><?php echo $day['Log']['DayOfWeek']; ?></index>
				<scheduled><?php echo $day[0]['NumScheduled']; ?></scheduled>
				<on_time><?php echo ($day[0]['NumScheduled'] - $day[0]['NumDelayed'] - $day[0]['NumCancelled'] - $day[0]['NumDiverted']); ?></on_time>
				<late><?php echo $day[0]['NumDelayed']; ?></late>
				<cancelled><?php echo $day[0]['NumCancelled']; ?></cancelled>
				<diverted><?php echo $day[0]['NumDiverted']; ?></diverted>
			</day>
			<?php
			}
			?>
		</days>
		<times>
			<?php
			foreach($TimesFrom as $time)
			{
			?>
			<time>
				<block><?php echo $time['Log']['DepTimeBlk']; ?></block>
				<scheduled><?php echo $time[0]['NumScheduled']; ?></scheduled>
				<on_time><?php echo ($time[0]['NumScheduled'] - $time[0]['NumDelayed'] - $time[0]['NumCancelled'] - $time[0]['NumDiverted']); ?></on_time>
				<late><?php echo $time[0]['NumDelayed']; ?></late>
				<cancelled><?php echo $time[0]['NumCancelled']; ?></cancelled>
				<diverted><?php echo $time[0]['NumDiverted']; ?></diverted>
			</time>
			<?php
			}
			?>
		</times>
	</route>
</routes>