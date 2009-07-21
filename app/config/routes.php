<?php
/* SVN FILE: $Id: routes.php 7945 2008-12-19 02:16:01Z gwoo $ */
/**
 * Short description for file.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.2.9
 * @version       $Revision: 7945 $
 * @modifiedby    $LastChangedBy: gwoo $
 * @lastmodified  $Date: 2008-12-18 18:16:01 -0800 (Thu, 18 Dec 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
	
	//Turn on extension parsing
	Router::parseExtensions('json', 'xml');
	
	
	//MOBILE SECURITY - /m/disambiguate/airports
	Router::connect(
		'/m/disambiguate/airports',
		array('controller' => 'disambiguate', 'action' => 'airports_mobile')
	);
	
	//MOBILE SECURITY - /m/lines/security/*
	Router::connect(
		'/m/lines/security',
		array('controller' => 'lines', 'action' => 'security_mobile')
	);
	
	Router::connect(
		'/m/lines/security/:airport',
		array('controller' => 'lines', 'action' => 'security_mobile_search'),
		array('airport' => '([A-Z]{3})')
	);
	
	Router::connect(
		'/m/lines/security/in/:airport',
		array('controller' => 'lines', 'action' => 'security_mobile_in'),
		array('airport' => '([A-Z]{3})')
	);
	
	Router::connect(
		'/m/lines/security/wait/:airport',
		array('controller' => 'lines', 'action' => 'security_mobile_wait'),
		array('airport' => '([A-Z]{3})')
	);
	
	Router::connect(
		'/m/lines/security/out/:airport',
		array('controller' => 'lines', 'action' => 'security_mobile_out'),
		array('airport' => '([A-Z]{3})')
	);
	
	//AIRPORTS
	Router::connect(
		'/airports/:from',
		array('controller' => 'airports', 'action' => 'index')
	);
	
	//ROUTES
	Router::connect(
		'/routes/:from/:to',
		array('controller' => 'airports', 'action' => 'index')
	);
	
	//FLIGHTS
	Router::connect(
		'/flights/:carrier/:flightnum',
		array('controller' => 'airports', 'action' => 'index')
	);
	Router::connect(
		'/flights/:carrier/:flightnum/:from/:to',
		array('controller' => 'airports', 'action' => 'index')
	);

	//AIRLINES
	Router::connect(
		'/airlines/:UniqueCarrier',
		array('controller' => 'airlines', 'action' => 'view'),
		array('UniqueCarrier' => '(.*)')
	);
	
	//LINES
	Router::connect(
		'/lines/security/:airport',
		array('controller' => 'lines', 'action' => 'security_search'),
		array('airport' => '([A-Z]{3})')
	);
	
	//PAGES
	Router::connect('/terms', array('controller' => 'pages', 'action' => 'display', 'terms'));
	Router::connect('/developers', array('controller' => 'pages', 'action' => 'display', 'developers'));
	Router::connect('/about', array('controller' => 'pages', 'action' => 'display', 'about'));
	
	
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.ctp)...
 */
	//Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
	Router::connect('/', array('controller' => 'home', 'action' => 'index'));
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
?>