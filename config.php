<?php

/**
 * I don't believe in license
 * You can do want you want with this program
 * - gwen -
 */

define( 'APP_PATH', __DIR__ );
define( 'CLASS_PATH', __DIR__.'/class' );
define( 'INCLUDE_PATH', __DIR__.'/include' );
define( 'GRAPH_PATH', __DIR__.'/graph' );
define( 'DATABASE_PATH', __DIR__.'/data' );
define( 'DATABASE_FILE', DATABASE_PATH.'/db.json' );


define( 'GRAPH_BOUNTIES', true );
define( 'GRAPH_BOUNTIES_REPORTS_REPUTATION', true );
define( 'GRAPH_REPORTS_RATINGS', true );
define( 'GRAPH_RATINGS_PIE', true );
define( 'GRAPH_PROGRAMS_PIE', true );
define( 'GRAPH_PLATFORMS_PIE', true );
define( 'GRAPH_TAGS_PIE', true );
define( 'GRAPH_TOP_PROGRAMS', true );
define( 'GRAPH_TOP_TAGS', true );


define( 'AUTO_RATE_TAG', [
	1 => [
		'rce' => [
			'tag_terms' => [
				'\s+rce\s+',
				'^rce\s+',
				'remote command execution',
				'remote command injection',
			],
		],
	],
	2 => [
		'sqli' => [
			'tag_terms' => [
				'sql',
				'sqli',
				'sql injection',
			],
			'rate_terms' => [
				'read' => 0,
				'write' => +1,
				'possible' => -1,
			],
		],
		'ssrf' => [
			'tag_terms' => [
				'ssrf',
				'server side request forgery',
			],
			'rate_terms' => [
			],
		],
		'lfi' => [
			'tag_terms' => [
				'lfi',
				'local file inclusion',
			],
		],
		'rfi' => [
			'tag_terms' => [
				'rfi',
				'remote file inclusion',
			],
		],
	],
	3 => [
		'xss' => [
			'tag_terms' => [
				'xss',
				'cross site scripting',
			],
		],
		'csrf' => [
			'tag_terms' => [
				'csrf',
				'cross site request forgery',
			],
		],
		'idor' => [
			'tag_terms' => [
				'idor',
				'insecure direct object reference',
			],
		],
		'bucket' => [
			'tag_terms' => [
				'bucket',
			],
		],
		'cors' => [
			'tag_terms' => [
				'cors',
				'cross origin',
			],
		],
	],
	4 => [
		'ratel' => [
			'tag_terms' => [
				'rate limit',
			],
		],
		'subto' => [
			'tag_terms' => [
				'subdomain takeover',
				'subdomain take over',
			],
		],
		'openr' => [
			'tag_terms' => [
				'open redirect',
			],
		],
	],
	5 => [
		'infod' => [
			'tag_terms' => [
				'phpinfo',
				'server-?status',
				'server-?info',
				'directory listing',
				'information disclosure',
				'htaccess',
			],
		],
		'fpd' => [
			'tag_terms' => [
				'full path disclosure',
			],
		],
	],
] );


function __autoload( $c ) {
	$f = CLASS_PATH.'/class.'.strtolower($c).'.php';
	if( is_file($f) ) {
		require_once( CLASS_PATH.'/class.'.strtolower($c).'.php' );
	}
}

// don't dig too much the rabbit hole!
