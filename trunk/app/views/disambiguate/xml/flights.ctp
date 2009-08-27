<disambiguate>
	<routes>
		<?php
		//print_r($Flights);
		if(isset($Flights))// && count($Flights) > 1)
		{
			foreach($Flights as $flight)
			{
		?>
		<route>
			<origin><?php echo $flight['Ontime']['origin']; ?></origin>
			<dest><?php echo $flight['Ontime']['dest']; ?></dest>
		</route>
		<?php 
			}
		}
		?>
	</routes>
</disambiguate>
