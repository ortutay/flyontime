<?php

$obj = array(
	'routes' => array(
		array(
			'from' => array(
				'code' => '',
				'city' => ''
			),
			'to' => array(
				'code' => '',
				'city' => ''
			),
			'day' => null,
			'flights' => array(),
			'airlines' => array(),
			'days' => array(),
			'times' => array()
		)
	)
);

//FROM ----- TO

//months
foreach($Months as $month => $foo)
{
	$obj['routes'][0]['months'][] = $month;
}

$obj['routes'][0]['from']['code'] = $From;
$obj['routes'][0]['from']['city'] = $FromCity;
$obj['routes'][0]['to']['code'] = $To;
$obj['routes'][0]['to']['city'] = $ToCity;
if(isset($Day) && intval($Day) > 0)
	$obj['routes'][0]['day'] = intval($Day);

foreach($FlightsFrom as $flight)
{
	$obj['routes'][0]['flights'][] = array(
		'unique_carrier' => $flight['Log']['UniqueCarrier'],
		'airline_short_name' => $AirlineNames[$flight['Log']['UniqueCarrier']],
		'flight_num' => $flight['Log']['FlightNum'],
		'average_arrival_delay' => floatval($flight[0]['AvgArrDelay'])
	);
}

foreach($AirlinesFrom as $airline)
{
	$obj['routes'][0]['airlines'][] = array(
		'unique_carrier' => $airline['Log']['UniqueCarrier'],
		'airline_short_name' => $AirlineNames[$airline['Log']['UniqueCarrier']],
		'percent_on_time' => floatval($airline[0]['PercentOnTime'])
	);
}

foreach($DaysFrom as $day)
{
	$obj['routes'][0]['days'][] = array(
		'index' => $day['Log']['DayOfWeek'],
		'scheduled' => $day[0]['NumScheduled'],
		'on_time' => ($day[0]['NumScheduled'] - $day[0]['NumDelayed'] - $day[0]['NumCancelled'] - $day[0]['NumDiverted']),
		'late' => $day[0]['NumDelayed'],
		'cancelled' => $day[0]['NumCancelled'],
		'diverted' => $day[0]['NumDiverted']
	);
}

foreach($TimesFrom as $time)
{
	$obj['routes'][0]['times'][] = array(
		'block' => $time['Log']['DepTimeBlk'],
		'scheduled' => $time[0]['NumScheduled'],
		'on_time' => ($time[0]['NumScheduled'] - $time[0]['NumDelayed'] - $time[0]['NumCancelled'] - $time[0]['NumDiverted']),
		'late' => $time[0]['NumDelayed'],
		'cancelled' => $time[0]['NumCancelled'],
		'diverted' => $time[0]['NumDiverted']
	);
}


//output
echo json_encode($obj);

?>