<disambiguate>
	<from>
		<airports>
			<?php
			if(!isset($AirportsFrom) || count($AirportsFrom) == 0)
			{}
			elseif(isset($AirportsFrom) && count($AirportsFrom) == 1)
			{
			?>
			<airport>
				<code><?php echo $AirportsFrom[0]['Enum']['code']; ?></code>
				<city><?php echo $AirportsFrom[0]['Enum']['description']; ?></city>
			</airport>
			<?php
			}
			elseif(isset($AirportsFrom) && count($AirportsFrom) > 1)
			{
				foreach($AirportsFrom as $airport)
				{
			?>
			<airport>
				<code><?php echo $airport['Enum']['code']; ?></code>
				<city><?php echo $airport['Enum']['description']; ?></city>
			</airport>
			<?php 
				}
			}
			?>
		</airports>
	</from>
	<to>
		<airports>
			<?php
			if(!isset($AirportsTo) || count($AirportsTo) == 0)
			{}
			elseif(isset($AirportsTo) && count($AirportsTo) == 1)
			{
			?>
			<airport>
				<code><?php echo $AirportsTo[0]['Enum']['code']; ?></code>
				<city><?php echo $AirportsTo[0]['Enum']['description']; ?></city>
			</airport>
			<?php
			}
			elseif(isset($AirportsTo) && count($AirportsTo) > 1)
			{
				foreach($AirportsTo as $airport)
				{
			?>
			<airport>
				<code><?php echo $airport['Enum']['code']; ?></code>
				<city><?php echo $airport['Enum']['description']; ?></city>
			</airport>
			<?php 
				}
			}
			?>
		</airports>
	</to>
</disambiguate>
