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
		$delay_str = abs($delay).' '.$delay_unit.' late';
		//$delay_style = 'color: black;';
	}
	return '<span style="' . $delay_style . '">' . $delay_str . '</span>';
}

$seen_airports = array();
$example_routes = array();
foreach($TopRoutes as $route)
{
	if (
		(isset($seen_airports[$route['Ontime']['origin']]) && $seen_airports[$route['Ontime']['origin']]) || 
		(isset($seen_airports[$route['Ontime']['dest']]) && $seen_airports[$route['Ontime']['dest']])
	) 
	{ continue; }
	
	$example_routes[] = array(
		'origin' => $route['Ontime']['origin'],
		'dest' => $route['Ontime']['dest'],
		'pct_ontime' => round($route['Ontime']["pct_ontime"]*100),
		'delay_median' => DelayText($route['Ontime']["delay_median"])
	);
	
	$seen_airports[$route['Ontime']['origin']] = 1;
	$seen_airports[$route['Ontime']['dest']] = 1;
}

$i_rand = rand(0, count($example_routes) - 1);

$example_route = $example_routes[$i_rand];
?>


<table border=0 cellpadding=0 cellspacing=0 style="height: 100%; width: 100%;">

<tr>
	<td colspan=3>
		<div style="background-color: black; text-align: right; color: white; font-family: Verdana; font-weight: bold; padding: 5px 1em 5px 5px" class="menubar">
			<a href="/about" style="color: white; font-weight: bold;">About</a>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="/statistics" style="color: white; font-weight: bold;">Statistics</a>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="/developers" style="color: white; font-weight: bold;">Source/Data/API</a>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="/lines/security" style="color: white; font-weight: bold;">Airport Security</a>
		</div>
	</td>
</tr>

<tr valign="top">
	<td width="334px" style="background-color: #B3CEE4;" align="center">
		<div style="background-image: url('/img/airplane_left.gif'); width: 334px; height: 233px;"></div>
		<br />
		
		<div style="font-family: Arial; font-size: 12pt; font-weight: bold; font-style: italic; width: 90%;">
			Find the most on-time flight between two airports or check how late your flight is on average, in good weather and bad, before you leave.
		</div>
		
		<br /><br /><br /><br /><br />
		
		<div style="font-family: Arial; font-size: 8pt; width: 90%; text-align: left;">
			See how FlyOnTime.us can <a href="/about">save your tax dollars!</a>
			
			<br /><br />
			<u>Data on this site is derived from:</u>
			
			<br /><br />
			The <a href="http://www.bts.gov/">Bureau of Transportation Statistics</a> via <a href="http://www.data.gov/">data.gov</a>
			
			<br /><br />
			The <a href="http://www.faa.gov/">Federal Aviation Administration</a>
			
			<br /><br />
			The <a href="http://www.noaa.gov/">National Oceanic and Atmospheric Administration</a>
			
			<br /><br />
			<a href="/lines/security">People Like You</a>
		</div>

		<br />
	</td>
	<td width="35px">
		<div style="background-image: url('/img/airplane_right.gif'); width: 35px; height: 233px;"></div>
	</td>
	<td width="100%">
	
		<table border=0 cellpadding=0 cellspacing=0 style="width: 100%; padding: 10px 10px 5px 5px;">
		<tr>
			<td align="right">
				<div style="font-family: Arial; font-size: 32pt; font-weight: bold; font-style: italic;">Fly<span style="color: #B3CEE4">OnTime</span>.us</div>
			</td>
		</tr>
		<tr>
			<td align="left">
				<br />
				<div style="font-family: Arial; font-size: 16pt; font-weight: bold; font-style: italic; color: #0A1D64;">Find a Route</div>
				
				<form method="GET" action="/disambiguate/airports">
				
				<table border=0 cellpadding=5 cellspacing=0 style="margin-top: 5px;">
				<tr>
					<td>
						<div style="font-family: Arial; font-size: 10pt; font-weight: bold">From: <span style="color: #666666;">(city or airport)</span></div>
					</td>
					<td>
						<div style="font-family: Arial; font-size: 10pt; font-weight: bold">To: <span style="color: #666666;">(city or airport; optional)</span></div>
					</td>
					<td></td>
				</tr>
				<tr>
					<td>
						<input name="from" type="text" class="big" style="width: 220px;" />
					</td>
					<td>
						<input name="to" type="text" class="big" style="width: 220px;" />
					</td>
					<td>
						<input type="submit" value="Search >>" />
					</td>
				</tr>
				</table>
				
				</form>
				
				<div style="font-family: Arial; font-size: 8pt; margin-top: 5px;">
					Example route: <a href="/routes/<?php echo $example_route['origin'].'/'.$example_route['dest']; ?>"><?php echo $example_route['origin'].' to '.$example_route['dest']; ?></a> is <?php echo '<b>'.$example_route['pct_ontime'].'% on-time</b> and <b>'.$example_route['delay_median'].'</b>'; ?> on average
				</div>
				
				<div style="margin-top: 10px; border-bottom: 1px solid #aaa; width: 100%;"></div>
				
				
				<br />
				<div style="font-family: Arial; font-size: 16pt; font-weight: bold; font-style: italic; color: #0A1D64;">Find An Airline/Flight</div>
				
				<form method="GET" action="/disambiguate/flights">
				
				<table border=0 cellpadding=5 cellspacing=0 style="margin-top: 5px;">
				<tr>
					<td>
						<div style="font-family: Arial; font-size: 10pt; font-weight: bold">Airline:</div>
					</td>
					<td>
						<div style="font-family: Arial; font-size: 10pt; font-weight: bold">Flight #: <span style="color: #666666;">(optional)</span></div>
					</td>
					<td></td>
				</tr>
				<tr>
					<td>
						<select name="airline" class="big" style="width: 220px;">
							<option value="">Select Airline</option>
							<?php
							foreach($Airlines as $airline)
							{
							?>
							<option value="<?php echo $airline['Enum']['code']; ?>"><?php echo $airline['Enum']['description']; ?></option>
							<?php
							}
							?>
						</select>
					</td>
					<td>
						<input name="flight_num" type="text" class="big" style="width: 220px;" />
					</td>
					<td>
						<input type="submit" value="Search >>" />
					</td>
				</tr>
				</table>
				
				</form>
				
				
				<div style="margin-top: 10px; border-bottom: 1px solid #aaa; width: 100%;"></div>
				
				<br /><br />
				
				<table border=0 cellpadding=0 cellspacing=0 width="100%">
				<tr valign="top">
					<td width="50%">
						<div style="font-family: Arial; font-size: 16pt; font-weight: bold; font-style: italic; color: #0A1D64;">Security Lines</div>
					</td>
					<td width="50%">
						<div style="font-family: Arial; font-size: 16pt; font-weight: bold; font-style: italic; color: #0A1D64;">Site News</div>
					</td>
				</tr>
				<tr valign="top">
					<td width="50%">
						<div style="font-size: 95%; width: 250px;">
							<p style="width: 100%;">Search wait time statistics for <a href="/lines/security">security lines</a>.</p>
							<table border=0 cellpadding=0 cellspacing=0>
							<tr>
								<td>
									<p>You can also contribute by notifying us when you get on line and then past security
									via Twitter or <a href="/m/lines/security">from your mobile phone</a>.</p>
								</td>
								<td align="center">
									<div><img src="/img/twitter.gif" /></div>
									<div><img src="/img/iphone.png" /></div>
								</td>
							</tr>
							</table>
						</div>
					</td>
					<td width="50%">
						<div style="font-size: 95%;">
							<p style="width: 100%;">July 24, 2009. Mentioned by <a href="http://www.whitehouse.gov/omb/blog/09/07/24/DatagovSurpasses100000Datasets/">OMB Director Peter Orszag</a>.</p>
							<p style="width: 100%;">July 21, 2009. Mentioned in <a href="http://voices.washingtonpost.com/federal-eye/2009/07/chopra.html?wprss=federal-eye">The Washington Post</a>.</p>
							<p style="width: 100%;">July 1, 2009. Mentioned by <a href="http://www.youtube.com/watch?v=9HZ-BESVVck">Federal CIO Vivek Kundra</a>.</p>
							<p style="width: 100%;">June 24, 2009. Mentioned in <a href="http://www.politico.com/news/stories/0609/24118.html">The Politico</a>.</p>
						</div>
						
						<table border=0 cellpadding=0 cellspacing=0 style="margin-top: 4px; margin-left: 4px;">
						<tr>
							<td>
								<a href="http://digg.com/submit?url=http%3A%2F%2Fwww.flyontime.us&title=FlyOnTime.us&topic=tech_news"><img border=0 src="/img/Digg_16x16.png" /></a>
							</td>
							<td width="4px"></td>
							<td>
								<a href="http://del.icio.us/post?url=http%3A%2F%2Fwww.flyontime.us&title=FlyOnTime.us"><img border=0 src="/img/delicious_16x16.png" /></a>
							</td>
							<td width="4px"></td>
							<td>
								<a href="http://www.facebook.com/sharer.php?u=http%3A%2F%2Fwww.flyontime.us&t=FlyOnTime.us"><img border=0 src="/img/FaceBook_16x16.png" /></a>
							</td>
							<td width="4px"></td>
							<td>
								<a href="http://reddit.com/submit?url=http%3A%2F%2Fwww.flyontime.us&title=FlyOnTime.us"><img border=0 src="/img/Reddit_16x16.png" /></a>
							</td>
							<td width="4px"></td>
							<td>
								<a href="http://www.stumbleupon.com/submit?url=http%3A%2F%2Fwww.flyontime.us&title=FlyOnTime.us"><img border=0 src="/img/Stumbleupon_16x16.png" /></a>
							</td>
							<td width="4px"></td>
							<td>
								<a href="http://technorati.com/faves?add=http%3A%2F%2Fwww.flyontime.us"><img border=0 src="/img/Technorati_16x16.png" /></a>
							</td>
							<td width="4px"></td>
							<td>
								<a href="http://twitthis.com/twit?url=http%3A%2F%2Fwww.flyontime.us"><img border=0 src="/img/Twitter_16x16.png" /></a>
							</td>
						</tr>
						</table>
		
					</td>
				</tr>
				</table>
				
				<br />
				
			</td>
		</tr>
		</table>
	
	</td>
</tr>

<tr>
	<td colspan=3>
		<div style="background-color: black; text-align: center; color: white; font-family: Verdana; font-weight: bold; padding: 3px; font-size: 80%" class="menubar">
			<a href="/terms" style="color: white;">Terms of Use</a>
		</div>
	</td>
</tr>

</table>


