<airlines>
	<?php foreach($Airlines as $airline) { ?>
	<airline>
		<code><?php echo $airline['Enum']['code']; ?></code>
		<name><?php echo $airline['Enum']['description']; ?></name>
	</airline>
	<?php } ?>
</airlines>
