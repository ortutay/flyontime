<?php $this->pageTitle = 'FlyOnTime.us'; 

$title_msg = '';
$button_msg = '';
$form_action = '';
$form_onsubmit = '';

if($Mode == '')
{
	$title_msg = 'Find the current security line wait times:';
	$button_msg = 'Search >>';
	$form_action = '/m/lines/security';
	$form_onsubmit = '';
}
elseif($Mode == 'in')
{
	$title_msg = 'Entering an airport security line?  Tell us where:';
	$button_msg = 'Entering Line >>';
	$form_action = '/m/lines/security/in';
	$form_onsubmit = 'setTime()';
}
elseif($Mode == 'out')
{
	if($Diff == 0)
	{
		$title_msg = 'Be sure to click below when you leave the line:';
		$button_msg = 'Leaving Line >>';
		$form_action = '/m/lines/security/out';
		$form_onsubmit = 'clearInterval()';
	}
	elseif($Diff > 0)
	{
		$title_msg = 'You were in line for:';
		$button_msg = '';
		$form_action = '';
		$form_onsubmit = '';
	}
}

?>

<script type="text/javascript">
	function setTime()
	{
		var _date = new Date();
		var form_in = document.getElementById('form_in');
		form_in.value = _date.getTime();
		
		return true;
	}
	
</script>

<div style="font-size: 14pt;">Fly<div style="color: #FF0000; display: inline; font-size: 14pt;">OnTime</div>.us</div>

<hr />

<br />

<?php
if($Mode == '')
{
?>
<div>
	<a href="/m/lines/security/in">I'm entering a security line now</a>
</div>

<br />
<?php
}
?>

	
<div>
	<b><?php echo $title_msg; ?></b>
</div>

<br />

<form method="GET" action="<?php echo $form_action; ?>" onsubmit="<?php echo $form_onsubmit; ?>">

	<?php
	if($Mode == '' || $Mode == 'in')
	{
	?>
	
	<input name="in" type="hidden" value="" id="form_in" />
	
	<?php
		if(count($Airports) == 0)
		{
	?>
	
	<hr />
	
	<?php
		}
		elseif(count($Airports) == 1)
		{
			if(isset($Delays))
			{
				foreach($Delays as $delay)
				{
	?>
	
	<hr />
	<table border=0 cellpadding=5 cellspacing=0 width="100%">
	<tr>
		<td width="5px"></td>
		<td align="left"><div><?php echo $delay['Line']['intimeblk30']; ?></div></td>
		<td align="right"><div><b><?php echo round($delay[0]['AvgDiff']/60, 1); ?> min.</b></div></td>
		<td width="5px"></td>
	</tr>
	</table>
	
	<?php
				}
	?>
	
	<hr />
	
	<?php
			}
		}
		else
		{
			foreach($Airports as $airport)
			{
	?>
	<hr />
	<table border=0 cellpadding=5 cellspacing=0 width="100%">
	<tr valign="middle">
		<td align="left"><input type="radio" name="airport" value="<?php echo $airport['Enum']['code']; ?>" /></td>
		<td align="left"><div><?php echo $airport['Enum']['description']; ?> (<?php echo $airport['Enum']['code']; ?>)</div></td>
	</tr>
	</table>
	<?php
			}
	?>
	
	<hr />

	<?php
		}
	?>
	
	<table border=0 cellpadding=10 cellspacing=0>
	<tr>
		<td align="left">
			<div>Airport:</div></div>
		</td>
		<td>
			<input name="airport" type="text" class="big" style="width: 100px;" value="<?php echo $Airport; ?>" />
		</td>
	</tr>
	<tr>
		<td colspan=2>
			<div style="color: #666666; display: inline;">(e.g. "ORD", "LAX", etc.)</div>
		</td>
	</tr>
	</table>
	
	<hr />
	
	<?php
	}
	elseif($Mode == 'out')
	{
	?>
	<input name="airport" type="hidden" value="<?php echo $Airport; ?>" />
	
	<?php
		if(isset($Diff) && $Diff > 0)
		{
	?>
	
	<hr />
	
	<div style="font-size: 24pt;">
		<?php echo round($Diff/60,1); ?> minutes
	</div>
	
	<hr />
	
	<div>Thank you!</div>
	<?php
		}
		else
		{
	?>
	
	<hr />
	<div id="timer" style="font-size: 24pt;">Loading...</div>
	
	<script type="text/javascript">
		var in_js = <?php echo $In_js; ?>;
		
		var timer_div = document.getElementById('timer');
		setInterval("updateTime()", 1000);
		
		function updateTime()
		{
			var _date = new Date();
			var diff = _date.getTime() - in_js;
			var diff_sec = Math.floor(diff / 1000);
			
			var sec = diff_sec % 60;
			sec = sec.toString();
			if(sec.length == 1)
				sec = '0'+sec;
			
			var min = (Math.floor(diff_sec / 60)) % 60;
			min = min.toString();
			if(min.length == 1)
				min = '0'+min;
				
			var hour = Math.floor(diff_sec / 3600);
			hour = hour.toString();
			if(hour.length == 1)
				hour = '0'+hour;
			
			if(Math.floor(diff_sec / 3600) > 0)
				timer_div.innerHTML = hour + ':' + min + ':' + sec;
			else
				timer_div.innerHTML = min + ':' + sec;
		}
		
	</script>
	<hr />
	
	<?php
		}
	?>
	
	<br />
	
	<?php
	}
	?>
	
	<?php
	if($button_msg != '')
	{
	?>
	<input type="submit" style="font-size: 18pt;" value="<?php echo $button_msg; ?>" />
	<?php
	}
	?>
	
</form>
