<?php

/**
 * I don't believe in license
 * You can do want you want with this program
 * - gwen -
 */

//ini_set( 'display_errors', false );
//error_reporting( 0 );

// DO NOT TOUCH THIS
function __autoload( $c ) {
	$f = __DIR__.'/class/class.'.strtolower($c).'.php';
	if( is_file($f) ) {
		require_once( __DIR__.'/class/class.'.strtolower($c).'.php' );
	}
}

$demo = (isset($_GET) && isset($_GET['demo'])) ? '.demo' : '';

define( 'APP_PATH', __DIR__ );
define( 'CLASS_PATH', APP_PATH.'/class' );
define( 'INCLUDE_PATH', APP_PATH.'/include' );
define( 'GRAPH_PATH', APP_PATH.'/graph' );
define( 'DATABASE_PATH', APP_PATH.'/data' );
define( 'DATABASE_FILE', DATABASE_PATH.'/db.json'.$demo );

// don't dig too much the rabbit hole!


// to hide a graph, simply comment the corresponding line or set the value to false
define( 'GRAPH_BOUNTIES', true );
define( 'GRAPH_BOUNTIES_REPORTS_REPUTATION', true );
define( 'GRAPH_REPORTS_RATINGS', true );
define( 'GRAPH_RATINGS_PIE', true );
define( 'GRAPH_PROGRAMS_PIE', true );
define( 'GRAPH_PLATFORMS_PIE', false );
define( 'GRAPH_STATE_PIE', true );
define( 'GRAPH_TAGS_PIE', true );
define( 'GRAPH_TOP_PROGRAMS', true );
define( 'GRAPH_TOP_TAGS', true );
define( 'GRAPH_TAGS_EVOLUTION', true );

/*
initial_rate_value (1>5) => [
	'tag_name' => [
		'tag_terms' => [ // regexp used to tag the report
			'term1',
			'term2',
			'term3',
			'...',
		],
		'rate_terms => [ // regexp used to rate the report, added to the initial_rate_value
			'term1' => 0,
			'term2' => +1,
			'term3' => -2,
			'...',
		]
	]
]
*/

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
				'write' => -1,
				'possible' => +1,
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
		'lfd' => [
			'tag_terms' => [
				'lfd',
				'local file disclosure',
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
				'Cross-Site Scripting',
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
				'ratelimit',
				'brute force',
				'bruteforce',
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
