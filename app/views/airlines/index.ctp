<?php $this->pageTitle = 'FlyOnTime.us: Airlines'; ?>

<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr>
	<td align="center">
	
		<table border=0 cellpadding=0 cellspacing=0 width="800px">
		<tr>
			<td align="left">

				<div class="header">
					Airlines
				</div>
				
				<br />

			</td>
		</tr>
		</table>
		
		<table border=0 cellpadding=0 cellspacing=0>
		<tr valign="top">
			<td align="left">
				<div>
					<?php
					for($i = 0; $i < floor(count($Airlines)/2); $i++)
					{
					$airline = $Airlines[$i];
					?>

					<a href="/airlines/<?php echo $airline['Enum']['code']; ?>"><?php echo $airline['Enum']['description']; ?></a>
					<br /><br />
					
					<?php
					}
					?>
				</div>
			</td>
			<td width="100px"></td>
			<td align="left">
				<div>
					<?php
					for($i = floor(count($Airlines)/2); $i < count($Airlines); $i++)
					{
					$airline = $Airlines[$i];
					?>

					<a href="/airlines/<?php echo $airline['Enum']['code']; ?>"><?php echo $airline['Enum']['description']; ?></a>
					<br /><br />
					
					<?php
					}
					?>
				</div>
			</td>
		</tr>
		</table>
		
	</td>
</tr>
</table>

<br /><br />