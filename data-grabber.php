<?php

/**
 * I don't believe in license
 * You can do want you want with this program
 * - gwen -
 */

require_once( 'config.php' );


$options = '';
$options .= 'a:'; // action
$options .= 'e'; // grab reputation
$options .= 'h'; // help
$options .= 'n:'; // n reports concerned
$options .= 'p:'; // platform
$options .= 'r'; // auto rate
$options .= 'rr'; // auto rate and overwrite
$options .= 't'; // auto tag
$options .= 'tt'; // auto tag and overwrite
$long_options = ['demo',];
$t_options = getopt( $options, $long_options );
//var_dump( $t_options );

if( !count($t_options) ) {
	Utils::help();
}

if( isset($t_options['h']) ) {
	Utils::help();
}

if( isset($t_options['demo']) ) {
	$_demo = true;
} else {
	$_demo = false;
}

if( isset($t_options['p']) ) {
	$_platform = $t_options['p'];
	$class = APP_PATH.'/class/class.'.$_platform.'.php';
	//var_dump($class);
	if( !is_file($class) ) {
		Utils::help( '"'.$_platform.'" platform not found!' );
	}
}

if( isset($t_options['a']) ) {
	$_action = strtolower( trim($t_options['a']) );
} else {
	$_action = 'n';
}

if( $_action == 'n' ) {
	$_update = false;
	$_overwrite = true;
} elseif( $_action == 'u' ) {
	$_update = true;
	$_overwrite = false;
} elseif( $_action == 'o' ) {
	$_update = false;
	$_overwrite = true;
} else {
	Utils::help( 'Unknown action!' );
}

if( isset($t_options['n']) ) {
	$_quantity = (int)$t_options['n'];
} else {
	$_quantity = 999999999;
}

if( isset($t_options['r']) ) {
	$_autorate = is_array($t_options['r']) ? 2 : 1;
} else {
	$_autorate = 0;
}

if( isset($t_options['t']) ) {
	$_autotag = is_array($t_options['t']) ? 2 : 1;
} else {
	$_autotag = 0;
}

if( isset($t_options['e']) ) {
	$_reputation = true;
} else {
	$_reputation = false;
}


$grabber = new $_platform();
$grabber->login();
echo "\n";

Utils::printInfo( 'Trying to connect.' );
if( !$grabber->connect() ) {
	Utils::printError( 'Cannot connect to '.$grabber->name.'!' );
	exit();
}
Utils::printSuccess( 'Connected to '.$grabber->name.'.' );

Utils::printInfo( 'Loading current database.' );
$db = Database::getInstance();
if( !$db->load(DATABASE_FILE) ) {
	Utils::printError( 'Cannot load database!' );
	exit();
}
Utils::printSuccess( 'Database loaded.' );

Utils::printInfo( 'Retrieving report list.' );
$n_page = $grabber->grabReportList( $_quantity );
if( !$n_page ) {
	Utils::printError( 'Cannot retrieve report list!' );
	exit();
}
Utils::printSuccess( $n_page.' pages imported.' );

Utils::printInfo( 'Grabbing reports.' );
$n_bugs = $grabber->grabReports( $db, $_quantity, $_update, $_overwrite );
if( !$n_bugs ) {
	Utils::printError( 'Cannot retrieve reports!' );
	exit();
}
Utils::printSuccess( $n_bugs.' reports imported.' );

$grabber->extractDatas();
Utils::printSuccess( 'Datas extracted.' );

if( $_autotag ) {
	Utils::printInfo( 'Trying to guess tags.' );
	Report::massAutoTag( $grabber->getReportsFinal() );
	Utils::printSuccess( 'Autotag finished.' );
}

if( $_autorate ) {
	Utils::printInfo( 'Trying to guess rating.' );
	Report::massAutoRate( $grabber->getReportsFinal() );
	Utils::printSuccess( 'Autorate finished.' );
}

if( $_reputation ) {
	Utils::printInfo( 'Grabbing reputation.' );
	$grabber->grabReputation( $db );
	Utils::printSuccess( 'Got reputation points.' );
}

if( $_demo ) {
	$grabber->setReportsFinal( Utils::demonize($grabber->getReportsFinal()) );
}

Utils::printInfo( 'Adding new reports.' );
list($n_new,$n_update) = $db->add( $grabber->getReportsFinal(), $_update, $_overwrite, $_autorate, $_autotag, $_reputation );
if( !$n_new && !$n_update ) {
	Utils::printError( 'Nothing new!' );
	exit();
}
Utils::printSuccess( $n_new.' new reports added, '.$n_update.' reports updated.' );

Utils::printInfo( 'Backuping the database.' );
$bak = $db->backup();
if( !$bak ) {
	Utils::printError( 'Cannot create backup!' );
	exit();
}
Utils::printSuccess( 'Backup created -> '.$bak );

Utils::printInfo( 'Saving new database.' );
if( !$db->save() ) {
	Utils::printError( 'Cannot save database!' );
	exit();
}
Utils::printSuccess( 'Database saved.' );

Utils::printInfo( 'Exiting.' );
exit();
