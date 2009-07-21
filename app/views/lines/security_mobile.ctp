<?php $this->pageTitle = 'FlyOnTime.us'; ?>

<div class="header">Airport Security</div>

<hr />
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
			<input name="from" type="text" class="big" style="width: 100px;" value="" />
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
			<input name="from" type="text" class="big" style="width: 100px;" value="" />
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
				<option value="1">Monday</option>
				<option value="2">Tuesday</option>
				<option value="3">Wednesday</option>
				<option value="4">Thursday</option>
				<option value="5">Friday</option>
				<option value="6">Saturday</option>
				<option value="7">Sunday</option>
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
				<option value="0">00:00 - 00:59</option>
				<option value="1">01:00 - 01:59</option>
				<option value="2">02:00 - 02:59</option>
				<option value="3">03:00 - 03:59</option>
				<option value="4">04:00 - 04:59</option>
				<option value="5">05:00 - 05:59</option>
				<option value="6">06:00 - 06:59</option>
				<option value="7">07:00 - 07:59</option>
				<option value="8">08:00 - 08:59</option>
				<option value="9">09:00 - 09:59</option>
				<option value="10">10:00 - 10:59</option>
				<option value="11">11:00 - 11:59</option>
				<option value="12">12:00 - 12:59</option>
				<option value="13">13:00 - 13:59</option>
				<option value="14">14:00 - 14:59</option>
				<option value="15">15:00 - 15:59</option>
				<option value="16">16:00 - 16:59</option>
				<option value="17">17:00 - 17:59</option>
				<option value="18">18:00 - 18:59</option>
				<option value="19">19:00 - 19:59</option>
				<option value="20">20:00 - 20:59</option>
				<option value="21">21:00 - 21:59</option>
				<option value="22">22:00 - 22:59</option>
				<option value="23">23:00 - 23:59</option>
			</select>
		</td>
		<td width="5px"></td>
	</tr>
	</table>
	<hr />
	
	<input type="submit" style="font-size: 18pt;" value="Search >>" />

</form>
