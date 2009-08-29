<airports>
	<?php foreach($Airports as $code => $values) { ?>
	<airport>
		<code><?php echo $code; ?></code>
		<name><?php echo $values['name']; ?></name>
		<geocode><?php echo $values['geocode']; ?></geocode>
		<timezone><?php echo $values['timezone']; ?></timezone>
	</airport>
	<?php } ?>
</airports>
