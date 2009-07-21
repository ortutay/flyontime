<?php $this->pageTitle = 'FlyOnTime.us'; ?>


<div class="header">
	Choose Airport
</div>

<br />

<form method="GET" action="/disambiguate/airports">
	
	<?php if(isset($Basepath)) { ?>
	<input type="hidden" name="basepath" value="<?php echo $Basepath; ?>" />
	<?php } ?>
	
	<?php if(isset($Day)) { ?>
	<input type="hidden" name="day" value="<?php echo $Day; ?>" />
	<?php } ?>
	
	<?php if(isset($Time)) { ?>
	<input type="hidden" name="time" value="<?php echo $Time; ?>" />
	<?php } ?>

	<?php
	if(!isset($AirportsFrom) || count($AirportsFrom) == 0)
	{
	?>
	
		<div class="subheader">We're not sure which airport you meant.  Please enter it again:</div>
		<br />
		
		<div>
		
			Airport (code or city):
			<br />
			<input name="from" type="text" class="big" style="width: 250px;" value="<?php echo $From; ?>" />
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
		<div class="subheader">We're not sure which airport you meant.  Please select one from the following:</div>
		
		<hr />
		<table border=0 cellpadding=5 cellspacing=0 width="100%">
		<?php
		foreach($AirportsFrom as $airport)
		{
		?>
		<tr valign="middle">
			<td align="left"><input type="radio" name="from" value="<?php echo $airport['Enum']['code']; ?>" /></td>
			<td align="left"><div><?php echo $airport['Enum']['description']; ?> (<?php echo $airport['Enum']['code']; ?>)</div></td>
		</tr>
		<?php
		}
		?>
		</table>
		<hr />
	</div>
		
	<?php
	}
	?>
	
	
	
	
	
	<?php
	if(!isset($AirportsTo) || count($AirportsTo) == 0)
	{
	?>
	
		<div class="subheader">We're not sure which airport you meant.  Please enter it again:</div>
		<br />
		
		<div>
		
			Airport (code or city):
			<br />
			<input name="to" type="text" class="big" style="width: 250px;" value="<?php echo $To; ?>" />
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
		<div class="subheader">We're not sure which airport you meant.  Please select one from the following:</div>
		
		<hr />
		<table border=0 cellpadding=5 cellspacing=0 width="100%">
		<?php
		foreach($AirportsTo as $airport)
		{
		?>
		<tr valign="middle">
			<td align="left"><input type="radio" name="to" value="<?php echo $airport['Enum']['code']; ?>" /></td>
			<td align="left"><div><?php echo $airport['Enum']['description']; ?> (<?php echo $airport['Enum']['code']; ?>)</div></td>
		</tr>
		<?php
		}
		?>
		</table>
		<hr />
	</div>
		
	<?php
	}
	?>

	<br />
	<div style="width: 100%; text-align: center;">
		<input type="submit" style="font-size: 18pt;" value="Continue >>" />
	</div>

</form>

