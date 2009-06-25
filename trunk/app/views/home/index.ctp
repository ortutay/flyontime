<table border=0 cellpadding=0 cellspacing=0 width="100%">
<tr valign="top">
	<td align="center">
		<div style="border-bottom: solid yellow; width: 100%; text-align: left;">
			<div style="padding: 5px;">
				<a href="/about">About</a>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="/statistics">Statistics</a>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="/developers">Developers</a>
			</div>
		</div>
		
		<br /><br />
		
		<table border=0 cellpadding=0 cellspacing=0 width="800px">
		<tr valign="top">
			<td>
				<div style="background-image: url('/img/airplane.jpg'); width: 800px; height: 215px; text-align: left;">
					<div style="position: relative; left: 380px; top: -20px; display: block; width: 350px;">
						<div style="font-size: 10pt; color: #666666; line-height: 14pt; text-align: justify;">
							Using data from the <a href="/about" style="font-size: 10pt;">Federal Government</a>, we can tell you how late your flight is on average or help you find the most on-time flight from one city to another.
						</div>
					</div>
					
					<div style="position: relative; left: 575px; top: 110px; display: block; width: 225px;">
						<div style="font-size: 24pt;">Fly<div style="color: #FF0000; display: inline; font-size: 24pt;">OnTime</div>.us</div>
					</div>
				</div>
			</td>
		</tr>
		<tr valign="top">
			<td>
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
										<div>Flight #: <div style="color: #666666; display: inline;">(optional)</div></div>
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
										<div>From: <div style="color: #666666; display: inline;">(city or airport)</div></div>
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
										<div>To: <div style="color: #666666; display: inline;">(city or airport)</div></div>
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
		
	</td>
</tr>
</table>