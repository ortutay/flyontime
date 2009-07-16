<?php $this->pageTitle = 'FlyOnTime.us: Disambiguate Flight'; ?>

<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr>
	<td align="center">
	
		<table border=0 cellpadding=0 cellspacing=0>
		<tr>
			<td align="left">

				<div class="header">
					Choose Flight
				</div>
				
				<br />
				
				<?php
				if(!isset($Flights) || count($Flights) == 0)
				{
				?>
				
				<div class="subheader">I'm sorry, we couldn't find your flight.</div>
				<br />
				
				<?php
				}
				elseif(isset($Flights) && count($Flights) > 1)
				{
				?>
				
				<form method="GET" action="/disambiguate/flights">
				
					<input type="hidden" name="airline" value="<?php echo $Airline; ?>" />
					<input type="hidden" name="flight_num" value="<?php echo $FlightNum; ?>" />
					
					<?php
					if(isset($Day))
					{
					?>
					
					<input type="hidden" name="day" value="<?php echo $Day; ?>" />
					
					<?php
					}
					?>
					
					<div>
						<p class="subheader">This flight travels on multiple routes.  Please select which airports you're traveling between:</p>

						<?php
						foreach($Flights as $flight)
						{
						?>
						
						<div>
						<input type="radio" name="from_to" value="<?php echo $flight['Ontime']['origin']; ?>_<?php echo $flight['Ontime']['dest']; ?>" /> 
							<?php echo $flight["Ontime"]["origin"] ?>
							to
							<?php echo $flight["Ontime"]["dest"] ?>
						</div>
						
						<?php
						}
						?>
						
						<br /><br />
					</div>
					
					<div style="width: 100%; text-align: center;">
						<input type="submit" value="Continue >>" />
					</div>
				
				</form>
				
				<?php
				}
				?>

			</td>
		</tr>
		</table>

	</td>
</tr>
</table>

<br /><br /><br /><br />

