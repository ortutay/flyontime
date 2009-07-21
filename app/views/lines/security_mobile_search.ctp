<?php $this->pageTitle = 'FlyOnTime.us'; ?>

<div class="header">Airport Security</div>

<hr />
<div class="subheader">Real-Time Results</div>
<hr />

<table border=0 cellpadding=5 cellspacing=0 style="background-color: yellow;">
<tr>
	<td>
		<div>
			<b><?php echo round($Realtime[0][0]['AvgDiff'] / 60,1); ?></b> (+/- <?php echo round($Realtime[0][0]['StdDiff'] / 60, 1); ?>) minutes
		</div>
	</td>
</tr>
</table>
<div style="color: #666666;">
	Based on <?php echo $Realtime[0][0]['NumEntries']; ?> entries in the past 30 minutes.
</div>

<hr />
<div class="subheader">Historical Results</div>
<hr />

<?php foreach($Times as $time) { ?>

<table border=0 cellpadding=3 cellspacing=0 width="100%">
<tr>
	<td width="5px"></td>
	<td align="left">
		<div><?php echo $time['Line']['intimeblk60']; ?></div>
	</td>
	<td align="right">
		<div><?php echo round($time[0]['AvgDiff']/60, 1); ?> min.</div>
	</td>
	<td width="5px"></td>
</tr>
</table>
<hr />

<?php } ?>

<div class="subheader">Enter Security Line</div>
<hr />

<form method="GET" action="/m/disambiguate/airports">

	<input name="basepath" type="hidden" value="/m/lines/security/in/" />
	<input name="noflash" type="hidden" value="1" />

	<table border=0 cellpadding=3 cellspacing=0 width="100%">
	<tr>
		<td width="5px"></td>
		<td align="left">
			<div>Airport Code:</div></div>
		</td>
		<td align="right">
			<input name="from" type="text" class="big" style="width: 100px;" value="<?php echo $Airport; ?>" />
		</td>
		<td width="5px"></td>
	</tr>
	</table>
	<hr />
	
	<input type="submit" style="font-size: 18pt;" value="Entering Line Now >>" />

</form>
<br /><br />

<hr />
<div class="subheader">Search</div>
<hr />

<form method="GET" action="/m/disambiguate/airports">

	<input name="basepath" type="hidden" value="/m/lines/security/" />
	<input name="noflash" type="hidden" value="1" />
	
	<table border=0 cellpadding=3 cellspacing=0 width="100%">
	<tr>
		<td width="5px"></td>
		<td align="left">
			<div>Airport Code:</div></div>
		</td>
		<td align="right">
			<input name="from" type="text" class="big" style="width: 100px;" value="<?php echo $Airport; ?>" />
		</td>
		<td width="5px"></td>
	</tr>
	</table>
	<hr />
	
	<table border=0 cellpadding=3 cellspacing=0 width="100%">
	<tr>
		<td width="5px"></td>
		<td align="left">
			<div>Day:</div></div>
		</td>
		<td align="right">
			<select name="day" class="big">
				<option value=""></option>
				<option value="1" <?php if($Day==1) echo 'selected'; ?>>Monday</option>
				<option value="2" <?php if($Day==2) echo 'selected'; ?>>Tuesday</option>
				<option value="3" <?php if($Day==3) echo 'selected'; ?>>Wednesday</option>
				<option value="4" <?php if($Day==4) echo 'selected'; ?>>Thursday</option>
				<option value="5" <?php if($Day==5) echo 'selected'; ?>>Friday</option>
				<option value="6" <?php if($Day==6) echo 'selected'; ?>>Saturday</option>
				<option value="7" <?php if($Day==7) echo 'selected'; ?>>Sunday</option>
			</select>
		</td>
		<td width="5px"></td>
	</tr>
	</table>
	<hr />
	
	<table border=0 cellpadding=3 cellspacing=0 width="100%">
	<tr>
		<td width="5px"></td>
		<td align="left">
			<div>Time:</div></div>
		</td>
		<td align="right">
			<select name="time" class="big">
				<option value=""></option>
				<option value="0" <?php if($Time=="0") echo 'selected'; ?>>00:00 - 00:59</option>
				<option value="1" <?php if($Time==1) echo 'selected'; ?>>01:00 - 01:59</option>
				<option value="2" <?php if($Time==2) echo 'selected'; ?>>02:00 - 02:59</option>
				<option value="3" <?php if($Time==3) echo 'selected'; ?>>03:00 - 03:59</option>
				<option value="4" <?php if($Time==4) echo 'selected'; ?>>04:00 - 04:59</option>
				<option value="5" <?php if($Time==5) echo 'selected'; ?>>05:00 - 05:59</option>
				<option value="6" <?php if($Time==6) echo 'selected'; ?>>06:00 - 06:59</option>
				<option value="7" <?php if($Time==7) echo 'selected'; ?>>07:00 - 07:59</option>
				<option value="8" <?php if($Time==8) echo 'selected'; ?>>08:00 - 08:59</option>
				<option value="9" <?php if($Time==9) echo 'selected'; ?>>09:00 - 09:59</option>
				<option value="10" <?php if($Time==10) echo 'selected'; ?>>10:00 - 10:59</option>
				<option value="11" <?php if($Time==11) echo 'selected'; ?>>11:00 - 11:59</option>
				<option value="12" <?php if($Time==12) echo 'selected'; ?>>12:00 - 12:59</option>
				<option value="13" <?php if($Time==13) echo 'selected'; ?>>13:00 - 13:59</option>
				<option value="14" <?php if($Time==14) echo 'selected'; ?>>14:00 - 14:59</option>
				<option value="15" <?php if($Time==15) echo 'selected'; ?>>15:00 - 15:59</option>
				<option value="16" <?php if($Time==16) echo 'selected'; ?>>16:00 - 16:59</option>
				<option value="17" <?php if($Time==17) echo 'selected'; ?>>17:00 - 17:59</option>
				<option value="18" <?php if($Time==18) echo 'selected'; ?>>18:00 - 18:59</option>
				<option value="19" <?php if($Time==19) echo 'selected'; ?>>19:00 - 19:59</option>
				<option value="20" <?php if($Time==20) echo 'selected'; ?>>20:00 - 20:59</option>
				<option value="21" <?php if($Time==21) echo 'selected'; ?>>21:00 - 21:59</option>
				<option value="22" <?php if($Time==22) echo 'selected'; ?>>22:00 - 22:59</option>
				<option value="23" <?php if($Time==23) echo 'selected'; ?>>23:00 - 23:59</option>
			</select>
		</td>
		<td width="5px"></td>
	</tr>
	</table>
	<hr />
	
	<input type="submit" style="font-size: 18pt;" value="Search >>" />

</form>

