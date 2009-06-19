<?php

$obj = array(
	'routes' => array(
		array(
			'months' => array(),
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
			'airlines' => array()
		),
		array(
			'months' => array(),
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
			'airlines' => array()
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

//TO ----- FROM

//months
foreach($Months as $month => $foo)
{
	$obj['routes'][1]['months'][] = $month;
}

$obj['routes'][1]['from']['code'] = $From;
$obj['routes'][1]['from']['city'] = $FromCity;
$obj['routes'][1]['to']['code'] = $To;
$obj['routes'][1]['to']['city'] = $ToCity;
if(isset($Day) && intval($Day) > 0)
	$obj['routes'][1]['day'] = intval($Day);

foreach($FlightsFrom as $flight)
{
	$obj['routes'][1]['flights'][] = array(
		'unique_carrier' => $flight['Log']['UniqueCarrier'],
		'airline_short_name' => $AirlineNames[$flight['Log']['UniqueCarrier']],
		'flight_num' => $flight['Log']['FlightNum'],
		'average_arrival_delay' => floatval($flight[0]['AvgArrDelay'])
	);
}

foreach($AirlinesFrom as $airline)
{
	$obj['routes'][1]['airlines'][] = array(
		'unique_carrier' => $airline['Log']['UniqueCarrier'],
		'airline_short_name' => $AirlineNames[$airline['Log']['UniqueCarrier']],
		'percent_on_time' => floatval($airline[0]['PercentOnTime'])
	);
}




//output
echo json_encode($obj);

?>