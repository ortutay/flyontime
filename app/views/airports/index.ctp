<?php $this->pageTitle = 'FlyOnTime.us: Airports'; ?>

<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr>
	<td align="center">
	
		<table border=0 cellpadding=0 cellspacing=0 width="800px">
		<tr>
			<td align="left">
				
				<br />
				
				<h1>
					Airports
				</h1>
				
				<br />

			</td>
		</tr>
		</table>
		
		<table border=0 cellpadding=5 cellspacing=0>
		<tr>
			<td><b>Code</b></td>
			<td><b>Name</b></td>
			<td><b>Geocode</b></td>
			<td><b>Timezone</b></td>
		</tr>
		<?php foreach($Airports as $code => $values) { ?>
		<tr>
			<td><a href="/airports/<?php echo $code; ?>"><?php echo $code; ?></a></td>
			<td><?php echo $values['name']; ?></td>
			<td><?php echo $values['geocode']; ?></td>
			<td><?php echo $values['timezone']; ?></td>
		</tr>
		<?php } ?>
		</table>
		
	</td>
</tr>
</table>

<br /><br />