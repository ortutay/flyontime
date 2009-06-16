<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr valign="top">
	<td align="center">
		<table border=0 cellpadding=0 cellspacing=0>
		<tr>
			<td style="background-color: #FFFF00" align="left">
				<div style="background-image: url('/img/top_left.png'); background-repeat: no-repeat; width: 258px; height: 72px;">
					
					<div style="position: relative; left: 365px; top: 40px; width: 500px;">
						<a href="/about" style="font-size: 16pt;">About</a>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="/statistics" style="font-size: 16pt;">Statistics</a>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="/developers" style="font-size: 16pt;">Developers</a>
					</div>
					
				</div>
			</td>
		</tr>
		<tr>
			<td align="left">
				<div style="background-image: url('/img/middle.png'); width: 720px; height: 186px;">
					
					<div style="text-align: left; position: relative; left: 335px; top: 30px; width: 350px; font-size: 10pt; color: 666666; line-height: 14pt;">
						Using data from the <a href="/about" style="font-size: 10pt;">Federal Government</a>, we can tell you how late your flight is on average or help you find the most on-time flight from one city to another.
					</div>
					
				</div>
			</td>
		</tr>
		<tr valign="top">
			<td style="background-color: white;">
				<div style="margin: 10px;">
					
					<br />
					
					<table border=0 cellpadding=0 cellspacing=0 width="100%">
					<tr valign="top">
						<td align="center">
						
							<form method="GET" action="/disambiguate/flights">
							
								<table border=0 cellpadding=5 cellspacing=0>
								<tr>
									<td>
										<div>Airline:</div>
									</td>
								</tr>
								<tr>
									<td>
										<select name="airline" class="big" style="width: 250px;">
											<option value=""></option>
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
								</tr>
								<tr>
									<td>
										<br />
									</td>
								</tr>
								<tr>
									<td>
										<div>Flight #:</div>
									</td>
								</tr>
								<tr>
									<td>
										<input name="flight_num" type="text" class="big" style="width: 250px;" />
									</td>
								</tr>
								<tr>
									<td>
										<br />
									</td>
								</tr>
								<tr>
									<td align="right">
										<input type="submit" value="Search >>" />
									</td>
								</tr>
								</table>
							
							</form>
							
						</td>

						<td width="5px"></td>
						<td>
							<img border=0 src="/img/or.png" />
						</td>

						<td align="center">
						
							<form method="GET" action="/disambiguate/airports">
							
								<table border=0 cellpadding=5 cellspacing=0>
								<tr>
									<td>
										<div>From: (city or airport)</div>
									</td>
								</tr>
								<tr>
									<td>
										<input name="from" type="text" class="big" style="width: 250px;" />
									</td>
								</tr>
								<tr>
									<td>
										<br />
									</td>
								</tr>
								<tr>
									<td>
										<div>To: (city or airport)</div>
									</td>
								</tr>
								<tr>
									<td>
										<input name="to" type="text" class="big" style="width: 250px;" />
									</td>
								</tr>
								<tr>
									<td>
										<br />
									</td>
								</tr>
								<tr>
									<td align="right">
										<input type="submit" value="Search >>" />
									</td>
								</tr>
								</table>
								
							</form>
							
						</td>
					</tr>
					</table>
					
					<br /><br />
					
				</div>
			</td>
		</tr>
		</table>
		
		<a href="/terms" style="font-size: 10pt;">Terms of Use</a>
		
		<br /><br /><br />
		
		<table border=0 cellpadding=0 cellspacing=0 width="720px">
		<tr>
			<td>
				<div>
					Please add your comments or suggestions about FlyOnTime.us:<br /><br />
				</div>
				<div id="disqus_thread"></div><script type="text/javascript" src="http://disqus.com/forums/flyontime/embed.js"></script><noscript><a href="http://flyontime.disqus.com/?url=ref">View the discussion thread.</a></noscript><a href="http://disqus.com" class="dsq-brlink">blog comments powered by <span class="logo-disqus">Disqus</span></a>
			</td>
		</tr>
		</table>
		
	</td>
</tr>
</table>