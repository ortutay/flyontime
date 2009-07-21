<?php $this->pageTitle = 'FlyOnTime.us'; ?>

<script type="text/javascript">
	function exitLine()
	{
		var url = '/m/lines/security/out/' + '<?php echo $Airport; ?>'
		window.location = url;
	}
	
	function cancelLine()
	{
		var yes = confirm('Are you sure you want to cancel this timer?');
		if(yes)
		{
			var url = '/m/lines/security/cancel/' + '<?php echo $Airport; ?>'
			window.location = url;
		}
	}
</script>

<div class="header">Airport Security</div>
<br />

<div><b>Be sure to click below when you leave the line:</b></div>

<br />

<hr />

<div id="timer" style="font-size: 24pt;">Loading...</div>

<script type="text/javascript">
	<?php if($In_js != '') { ?>
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
	<?php } ?>
</script>


<hr />

<br />

<input type="button" style="font-size: 18pt;" value="Leaving Line Now >>" onclick="exitLine();" />

<br /><br />

<input type="button" style="font-size: 18pt;" value="Cancel Timer" onclick="cancelLine();" />

