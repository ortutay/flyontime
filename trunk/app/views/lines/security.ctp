<?php $this->pageTitle = 'FlyOnTime.us: Airport Security Lines' ?>

<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr>
	<td align="center">
	
		<table border=0 cellpadding=0 cellspacing=0 width="800px">
		<tr>
			<td align="left">
				<div>
				
					<h1>Airport Security Lines</h1>
					
					<p style="margin: 2em 0em 2em 0em; font-style: italic">This part of FlyOnTime is under development as travelers send in their
					security line wait times.</p>
					
					<div class="header">Search</div>
					
					<p>You can search <b>real-time</b> and historical wait times in the security lines at major U.S. airports.</p>
					
					<table border=0 cellpadding=0 cellspacing=0 width="100%">
					<tr>
						<td align="center">
					
					
					
							<form method="GET" action="/disambiguate/airports">
							
								<input name="basepath" type="hidden" value="/lines/security/" />
									
								<div style="width: 700px; border: 1px solid #C3D9FF; padding: 3px;">
			
									<div style="width: 100%; height: 100%; background-color: #E8EEFA;">
										
										<br />
										
										<table border=0 cellpadding=5 cellspacing=0>
										<tr>
											<td align="left">
												<div>Airport: <div style="color: #666666; display: inline;">(city or code)</div></div>
											</td>
											<td align="left">
												<div>Day: <div style="color: #666666; display: inline;">(optional)</div></div>
											</td>
											<td align="left">
												<div>Time: <div style="color: #666666; display: inline;">(optional)</div></div>
											</td>
										</tr>
										<tr>
											<td>
												<input name="from" type="text" class="big" style="width: 250px;" />
											</td>
											<td>
												<select name="day" class="big" style="width: 150px;">
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
											<td>
												<select name="time" class="big" style="width: 150px;">
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
										</tr>
										<tr>
											<td colspan=3 align="left">
												<br />
												<input type="submit" value="Search >>" />
											</td>
										</tr>
										</table>
										
										<br />
								
									</div>
			
								</div>
							
							</form>
					
					
						</td>
					</tr>
					</table>
					
					<br />

					
					<div class="header">Participate</div>
					
					<p>We gather all of our airport security line statistics from <b>YOU</b>, the users of this site.  We depend on our users to tell us when and where they are entering and leaving an airport security line.  You can contribute one of two ways:</p>
					
					<table border=0 cellpadding=10 cellspacing=0>
					<tr valign="top">
						<td width="50px"></td>
						<td align="left">
							<div style="font-size: 18pt;">1)</td>
						</td>
						<td align="left">
							<div style="font-size: 18pt;">On your smart phone's web browser</div>
							<br />
							<div>
								Visit <a href="/">flyontime.us</a> from your iPhone, BlackBerry, or other smart phone and tell us what airport you're at and when you enter and leave a security line.  If you're on a smart phone right now, <a href="/m/lines/security">enter our mobile site</a>.
							</div>
						</td>
					</tr>
					<tr valign="top">
						<td width="50px"></td>
						<td align="left">
							<div style="font-size: 18pt;">2)</td>
						</td>
						<td align="left">
							<div style="font-size: 18pt;">On Twitter</div>
							<br />
							<div>
								When you enter a security line, tweet this message:
								<br /><br />
								
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>#airportsecurity xxx in</b>
								<br /><br />
								
								and when you leave the line, tweet:
								<br /><br />
								
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>#airportsecurity xxx out</b>
								<br /><br />
								
								where <b>xxx</b> is an airport code like "ord" or "lax".  The airport code is optional for the second message.  Also, "in" and "out" can come before or after the airport code.
								<br /><br />
								
								The following example will work too:
								<br /><br />
								
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>I'm going to #airportsecurity in ord and I hope I make my flight.</b>
								<br /><br />
								
								<br />
								<a href="http://twitter.com/flyontime"><img border=0 src="http://static.twitter.com/images/twitter_logo.png" style="width: 308px;" /></a>
								<br /><br />
							</div>
						</td>
					</tr>
					</table>

				
				</div>
				
			</td>
		</tr>
		</table>
	
	</td>
</tr>
</table>

