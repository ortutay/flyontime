<?php $this->pageTitle = 'FlyOnTime.us: Disambiguate Airports'; ?>

<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr>
	<td align="center">
	
		<table border=0 cellpadding=0 cellspacing=0>
		<tr>
			<td align="left">

				<div class="header">
					Disambiguate Airports
				</div>
				
				<br />
				
				<form method="GET" action="/disambiguate/airports">
				
					<?php
					if(isset($Day))
					{
					?>
					
					<input type="hidden" name="day" value="<?php echo $Day; ?>" />
					
					<?php
					}
					?>
				
					<?php
					if(!isset($AirportsFrom) || count($AirportsFrom) == 0)
					{
					?>
					
						<div class="subheader">We're not sure which airport you want to leave from.  Please enter it again:</div>
						<br />
						
						<div>
						
							From (city or airport): <input name="from" type="text" style="width: 250px;" />
							<br /><br />
						</div>
					
					<?php
					}
					elseif(isset($AirportsFrom) && count($AirportsFrom) == 1)
					{
					?>
					
					<input type="hidden" name="from" value="<?php echo $AirportsFrom[0]['Enum']['code']; ?>" />
					
					<?php
					}
					elseif(isset($AirportsFrom) && count($AirportsFrom) > 1)
					{
					?>
						
					<div>
						<div class="subheader">We're not sure which airport you want to leave from.  Please select one from the following:</div>
						<br />
						<?php
						foreach($AirportsFrom as $airport)
						{
						?>
						
						<input type="radio" name="from" value="<?php echo $airport['Enum']['code']; ?>" /> <?php echo $airport['Enum']['description']; ?> (<?php echo $airport['Enum']['code']; ?>)<br />
						
						<?php
						}
						?>
						
						<br /><br />
					</div>
						
					<?php
					}
					?>
					
					
					
					
					
					<?php
					if(!isset($AirportsTo) || count($AirportsTo) == 0)
					{
					?>
					
						<div class="subheader">We're not sure which airport you want to travel to.  Please enter it again:</div>
						<br />
						
						<div>
						
							To (city or airport): <input name="to" type="text" style="width: 250px;" />
							<br /><br />
						</div>
					
					<?php
					}
					elseif(isset($AirportsTo) && count($AirportsTo) == 1)
					{
					?>
					
					<input type="hidden" name="to" value="<?php echo $AirportsTo[0]['Enum']['code']; ?>" />
					
					<?php
					}
					elseif(isset($AirportsTo) && count($AirportsTo) > 1)
					{
					?>
						
					<div>
						<div class="subheader">We're not sure which airport you want to travel to.  Please select one from the following:</div>
						<br />
						<?php
						foreach($AirportsTo as $airport)
						{
						?>
						
						<input type="radio" name="to" value="<?php echo $airport['Enum']['code']; ?>" /> <?php echo $airport['Enum']['description']; ?> (<?php echo $airport['Enum']['code']; ?>)<br />
						
						<?php
						}
						?>
						
						<br /><br />
					</div>
						
					<?php
					}
					?>
				
					<div style="width: 100%; text-align: center;">
						<input type="submit" value="Continue >>" />
					</div>
				
				</form>


			</td>
		</tr>
		</table>

	</td>
</tr>
</table>

<br />

