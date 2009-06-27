<?php

$obj = array(
	'flight' => array(
		'months' => array(),
		'unique_carrier' => $Airline,
		'airline_name' => $AirlineInfo['Enum']['description'],
		'flight_num' => $FlightNum,
		'routes' => array()
	)
);

//months
foreach($Months as $month => $foo)
{
	$obj['flight']['months'][] = $month;
}

//routes
foreach($AirportPairStats as $airport_pair => $stats)
{
	$OriginCityName = $AirportPairFlights[$airport_pair][0]['Log']['OriginCityName'];
	$Origin = $AirportPairFlights[$airport_pair][0]['Log']['Origin'];
	
	$DestCityName = $AirportPairFlights[$airport_pair][0]['Log']['DestCityName'];
	$Dest = $AirportPairFlights[$airport_pair][0]['Log']['Dest'];
	
	$day_val = null;
	if(isset($Day) && intval($Day) > 0)
		$day_val = intval($Day);
	
	$days = array();
	foreach($stats['days'] as $day_index => $day)
	{
		$days[] = array(
			'index' => $day_index,
			'scheduled' => $day['total'],
			'on_time' => ($day['total'] - $day['delayed'] - $day['cancelled'] - $day['diverted']),
			'late' => $day['delayed'],
			'cancelled' => $day['cancelled'],
			'diverted' => $day['diverted']
		);
	}
	
	$times = array();
	foreach($stats['times'] as $time_block => $time)
	{
		$times[] = array(
			'block' => $time_block,
			'scheduled' => $time['total'],
			'on_time' => ($time['total'] - $time['delayed'] - $time['cancelled'] - $time['diverted']),
			'late' => $time['delayed'],
			'cancelled' => $time['cancelled'],
			'diverted' => $time['diverted']
		);
	}

	$obj['flight']['routes'][] = array(
		'from' => array(
			'code' => $Origin,
			'city' => $OriginCityName
		),
		'to' => array(
			'code' => $Dest,
			'city' => $DestCityName
		),
		'day' => $day_val,
		'scheduled' => intval($stats['total']),
		'on_time' => intval($stats['arrived_on_time']),
		'late' => intval($stats['arrived'] - $stats['arrived_on_time']),
		'cancelled' => intval($stats['cancelled']),
		'diverted' => intval($stats['diverted']),
		'average_arrival_delay' => floatval(round($stats['avg_arrival_delay'], 4)),
		'days' => $days,
		'times' => $times
	);
}

//output
echo json_encode($obj);

?>