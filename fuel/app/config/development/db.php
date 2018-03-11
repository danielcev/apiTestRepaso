<?php
/**
 * The development database settings. These get merged with the global settings.
 */

/* LOCAL MAC MAMP*/


return array(
	'default' => array(
		'connection'  => array(
			'dsn'        => 'mysql:host=localhost:8889;dbname=apiTestRepaso',
			'username'   => 'root',
			'password'   => 'root',
		),
	),
);



/* PRODUCCIÓN */
/*
return array(
	'default' => array(
		'connection'  => array(
			'dsn'        => 'mysql:host=localhost;dbname=daniplat_apiTestRepaso',
			'username'   => 'daniplat_me',
			'password'   => '7y1RXtIMBc4ynUvz',
		),
	),
);
*/