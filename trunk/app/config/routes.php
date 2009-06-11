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
	
	/*
	//LEGISLATION
	Router::connect(
		'/legislation/:CanonicalName',
		array('controller' => 'legislation', 'action' => 'view'),
		array('CanonicalName' => '(GA[0-9]+[A-Z]+[0-9]+)')
	);
	
	Router::connect(
		'/legislation/:SearchName',
		array('controller' => 'legislation', 'action' => 'search'),
		array('SearchName' => '(.+)')
	);
	
	//MEMBERS	
	Router::connect(
		'/members/:SearchName',
		array('controller' => 'members', 'action' => 'search'),
		array('SearchName' => '(house|senate|MostProlificRepresentatives|MostProlificSenators|MostSuccessfulRepresentatives|MostSuccessfulSenators|MostEfficientRepresentatives|MostEfficientSenators)')
	);
	
	Router::connect(
		'/members/:CanonicalName',
		array('controller' => 'members', 'action' => 'view'),
		array('CanonicalName' => '(.+)')
	);
	
	//COMMITTEES
	Router::connect(
		'/committees/:SearchName',
		array('controller' => 'committees', 'action' => 'search'),
		array('SearchName' => '(house|senate|MostAssignedHouse|MostAssignedSenate)')
	);
	
	Router::connect(
		'/committees/:CanonicalName',
		array('controller' => 'committees', 'action' => 'view'),
		array('CanonicalName' => '(.+)')
	);
	
	//RSS - LEGISLATION
	Router::connect(
		'/rss/legislation/:CanonicalName',
		array('controller' => 'legislation', 'action' => 'view_rss'),
		array('CanonicalName' => '(GA[0-9]+[A-Z]+[0-9]+)')
	);
	
	Router::connect(
		'/rss/legislation/:SearchName',
		array('controller' => 'legislation', 'action' => 'search_rss'),
		array('SearchName' => '(.+)')
	);
	
	//RSS - MEMBERS
	Router::connect(
		'/rss/members/:CanonicalName',
		array('controller' => 'members', 'action' => 'view_rss'),
		array('CanonicalName' => '(.+)')
	);
	
	//RSS - COMMITTEES
	Router::connect(
		'/rss/committees/:CanonicalName',
		array('controller' => 'committees', 'action' => 'view_rss'),
		array('CanonicalName' => '(.+)')
	);
	
	*/
	
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