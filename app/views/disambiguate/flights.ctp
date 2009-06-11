<?php $this->pageTitle = 'FlyOnTime.us: Disambiguate Flight'; ?>

<div class="header">
	Disambiguate Flight
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

<form method="GET" action="/flights">

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
		<div class="subheader">This flight travels on multiple routes.  Please select which airports you're traveling between:</div>
		<br /><br />
		<?php
		foreach($Flights as $flight)
		{
		?>
		
		<input type="radio" name="from_to" value="<?php echo $flight['Log']['Origin']; ?>_<?php echo $flight['Log']['Dest']; ?>" /> From <b><?php echo $flight['Log']['OriginCityName']; ?> (<?php echo $flight['Log']['Origin']; ?>)</b> to <b><?php echo $flight['Log']['DestCityName']; ?> (<?php echo $flight['Log']['Dest']; ?>)</b><br /><br />
		
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

<br /><br /><br /><br />

