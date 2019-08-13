<?php return array(
	// Core actions
	'index' => 'Index::a_index',
	'forbidden' => 'ResponseErrors::a403',
	'not_found' => 'ResponseErrors::a404',
	
	// Widget's actions
	'/core/wa/widget.css' => 'WidgetAssistent::style',
	'/core/wa/widget.js' => 'WidgetAssistent::script',
	'/widgets' => 'Index::a_widgets',


	'api/0.1/login' => 'Auth::a_login',
	'api/0.1/logout' => 'Auth::a_logout',
	'api/0.1/whoami' => 'Auth::a_whoami',

	'/api/0.1/threats' => 'Auth::a_threats',
	'/api/0.1/threat_info/{id}' => 'Auth::a_threat_info'
);
