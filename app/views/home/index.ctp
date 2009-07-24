<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr>
	<td>
		<div style="background-color: black; text-align: right; color: white; font-family: Verdana; font-weight: bold; padding: 5px 1em 5px 5px" class="menubar">
			<a href="/about" style="color: white; font-weight: bold;">About</a>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="/statistics" style="color: white; font-weight: bold;">Statistics</a>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="/developers" style="color: white; font-weight: bold;">Source/Data/API</a>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="/lines/security" style="color: white; font-weight: bold;">Airport Security</a>
		</div>
		
		<table border=0 cellpadding=0 cellspacing=0 width="800px" style="margin-top: 2em" align="center">
		<tr valign="top">
			<td style="padding-bottom: 2em">
				<div style="background-image: url('/img/main.png'); width: 528px; height: 250px; text-align: left;">
					<div style="position: relative; left: 450px; top: 10px; width: 340px">
						<div style="font-family: Trebuchet MS, Geneva, Helvetica, Tahoma, Arial; font-size: 32pt; font-weight: bold">Fly<span style="color: #DD0000">OnTime</span>.us</div>
						
						<div style="margin-top: 1em; font-size: 90%; color: #444">Find the most on-time flight between two airports or check how late your flight is on average, in good weather and bad, before you leave.</div>
					</div>

				</div>
			</td>
		</tr>
		</table>
		
		<table border=0 cellpadding=0 cellspacing=0 align="center">
		<tr>
			<td style="padding-right: 5em; width: 220px">
				<h3>Find A Route</h3>
				<form method="GET" action="/disambiguate/airports">
					<div style="white-space: nowrap;">From: <div style="color: #666666; display: inline;">(city or airport)</div></div>
					<div style="margin-top: .3em"><input name="from" type="text" class="big" style="width: 220px;" /></div>
					<div style="margin-top: .85em; white-space: nowrap;">To: <div style="color: #666666; display: inline;">(city or airport; optional)</div></div>
					<div style="margin-top: .3em"><input name="to" type="text" class="big" style="width: 220px;" /></div>
					<div style="text-align: right; margin-top: .85em"><input type="submit" value="Search >>" /></div>
				</form>
			</td>
			<td style="padding-right: 5em; width: 220px">
				<h3 style="white-space: nowrap;">Find An Airline/Flight</h3>
				<form method="GET" action="/disambiguate/flights">
					<div>Airline:</div>
					<div style="margin-top: .3em"><select name="airline" class="big" style="width: 220px;">
						<option value="">Select Airline</option>
						<?php
						foreach($Airlines as $airline)
						{
						?>
						<option value="<?php echo $airline['Enum']['code']; ?>"><?php echo $airline['Enum']['description']; ?></option>
						<?php
						}
						?>
					</select></div>
					<div style="margin-top: .85em; white-space: nowrap;">Flight #: <div style="color: #666666; display: inline;">(optional)</div></div>
					<div style="margin-top: .3em"><input name="flight_num" type="text" class="big" style="width: 220px;" /></div>
					<div style="text-align: right; margin-top: .85em"><input type="submit" value="Search >>" /></div>
				</form>
			</td>
			<td>
				<div style="margin-top: 3em; font-size: 85%">
				<p>Check out these popular flights:</p>
				<table border=0 cellpadding=0 cellspacing=0 style="font-size: 95%">
				<?php
				function DelayText($delay)
				{
					$delay_style = '';
					$delay_str = '';
					$delay_unit = 'min.';
					
					if (abs($delay) >= 120) {
						$delay = round($delay / 60, 1);
						$delay_unit = 'hrs.';
					}
					
					if ($delay < 0) {
						$delay_str = abs($delay).' '.$delay_unit.' early';
						//$delay_style = 'color: green;';
					} else if ($delay > 0) {
						$delay_str = abs($delay).' '.$delay_unit.' late';
						//$delay_style = 'color: red;';
					} else {
						$delay_str = 'on time';
						//$delay_style = 'color: black;';
					}
					return '<span style="' . $delay_style . '">' . $delay_str . '</span>';
				}

				$seen_airports = array();
				foreach($TopRoutes as $route)
				{
					if (
						(isset($seen_airports[$route['Ontime']['origin']]) && $seen_airports[$route['Ontime']['origin']]) || 
						(isset($seen_airports[$route['Ontime']['dest']]) && $seen_airports[$route['Ontime']['dest']])
					) 
					{ continue; }
					
					echo "<tr>";
					echo "<td><a href='/routes/" . $route['Ontime']['origin'] . "/" . $route['Ontime']['dest'] . "' style='text-decoration: none'>" . $route['Ontime']['origin'] . " to " . $route['Ontime']['dest'] . "</a></td>";
					//echo "<td>" . $route['Ontime']["count"] . " flights</td>";
					echo "<td style='padding-left: 2em'>" . round($route['Ontime']["pct_ontime"]*100) . "% on time</td>";
					echo "</tr>";
					echo "<tr><td colspan=2 style='text-align: right; padding-bottom: .75em'>" . DelayText($route['Ontime']["delay_median"]) . ' on average';
					echo "</td></tr>";
					$seen_airports[$route['Ontime']['origin']] = 1;
					$seen_airports[$route['Ontime']['dest']] = 1;
				}
				?>
				</table>
				</div>
			</td>
		</tr>
		<tr valign="top">
			<td style="padding: 1em 5em 0px 0px; width: 220px;">
				<h3>Security Lines</h3>
				<div style="font-size: 95%; width: 100%;">
				<p style="width: 100%;">Search wait time statistics for <a href="/lines/security">security lines</a>.</p>
				<div style="float: right; text-align: center; margin-top: -10px">
					<div><img src="/img/twitter.gif"/></div>
					<div><img src="/img/iphone.png"/></div>
				</div>
				<p>You can also contribute by notifying us when you get on line and then past security
				via Twitter or <a href="/m/lines/security">from your mobile phone</a>.</p>
				</div>
			</td>
			<td style="padding: 1em 5em 0px 0px; width: 220px" colspan="2">
				<h3>Site News</h3>
				<div style="font-size: 95%">
				<p style="width: 100%;">July 24, 2009. Mentioned by <a href="http://www.whitehouse.gov/omb/blog/09/07/24/DatagovSurpasses100000Datasets/">OMB Director Peter Orszag</a>.</p>
				<p style="width: 100%;">July 21, 2009. Mentioned in <a href="http://voices.washingtonpost.com/federal-eye/2009/07/chopra.html?wprss=federal-eye">The Washington Post</a>.</p>
				<p style="width: 100%;">July 1, 2009. Mentioned by <a href="http://www.youtube.com/watch?v=9HZ-BESVVck">Federal CIO Vivek Kundra</a>.</p>
				<p style="width: 100%;">June 24, 2009. Mentioned in <a href="http://www.politico.com/news/stories/0609/24118.html">The Politico</a>.</p>
				</div>
			</td>
		</tr>
		</table>
		
		<div style="margin-top: 4em; background-color: black; text-align: center; color: white; font-family: Verdana; font-weight: bold; padding: 3px; font-size: 80%" class="menubar">
			<a href="/terms" style="color: white;">Terms of Use</a>
		</div>
		
	</td>
</tr>
</table>

