<?php

/**
 * I don't believe in license
 * You can do want you want with this program
 * - gwen -
 */

require_once( 'config.php' );


// manage options
{
	$bbstats = BBstats::getInstance();
	$t_options = getopt( BBstats::SHORT_OPTIONS, BBstats::LONG_OPTIONS );
	//var_dump( $t_options );
	
	if( !count($t_options) ) {
		Utils::help();
	}
	
	if( isset($t_options['h']) ) {
		Utils::help();
	}
	
	if( isset($t_options['demo']) ) {
		$bbstats->enableDemoMode();
	}
	
	if( isset($t_options['p']) ) {
		$p = $t_options['p'];
		$class = CLASS_PATH.'/class.'.$p.'.php';
		if( !is_file($class) ) {
			Utils::help( '"'.$p.'" platform not found!' );
		}
		$bbstats->setPlatform( $p );
	}
	
	if( isset($t_options['f']) ) {
		if( !$bbstats->setSourceFile($t_options['f']) ) {
			Utils::help( '"'.$t_options['f'].'" source file not found!' );
		}
	}
	
	if( isset($t_options['a']) ) {
		if( !$bbstats->setAction($t_options['a']) ) {
			Utils::help( 'Unknown action!' );
		}
	}
	
	if( isset($t_options['n']) ) {
		$bbstats->setQuantity( (int)$t_options['n'] );
	}
	
	if( isset($t_options['r']) ) {
		$bbstats->setAutoRateMode( is_array($t_options['r']) ? 2 : 1 );
	}
	
	if( isset($t_options['t']) ) {
		$bbstats->setAutoTagMode( is_array($t_options['t']) ? 2 : 1 );
	}
	
	if( isset($t_options['e']) ) {
		$bbstats->enableReputation();
	}
}
// ---


//load current database
{
	Utils::printInfo( 'Loading current database.' );
	$db = Database::getInstance();
	if( !$db->load(DATABASE_FILE) ) {
		Utils::printError( 'Cannot load database!' );
		exit();
	}
	Utils::printSuccess( 'Database loaded.' );
	$bbstats->setDatabase( $db );
	
	echo "\n";
}
// ---


// process
{
	$p = $bbstats->getPlatform();
	$grabber = new $p();
	
	if( !$bbstats->isImport() )
	{
		$grabber->login();
		echo "\n";
		
		Utils::printInfo( 'Trying to connect.' );
		if( !$grabber->connect() ) {
			Utils::printError( 'Cannot connect to '.$grabber->getName().'!' );
			exit();
		}
		Utils::printSuccess( 'Connected to '.$grabber->getName().'.' );
	}
	
	if( $bbstats->isReputation() ) {
		Utils::printInfo( 'Grabbing reputation.' );
		$t_reputation = $grabber->grabReputation();
		if( $t_reputation == null ) {
			Utils::printError( 'Cannot grab reputation!' );
			$t_reputation = null;
		} else {
			Utils::printSuccess( 'Got reputation points.' );
		}
	} else {
		$t_reputation = null;
	}
	
	Utils::printInfo( 'Retrieving report list.' );
	$n_page = $grabber->grabReportList( $bbstats->getQuantity() );
	echo "\n";
	if( !$n_page ) {
		Utils::printError( 'Cannot retrieve report list!' );
		exit();
	}
	Utils::printSuccess( $n_page.' pages imported.' );
	
	Utils::printInfo( 'Grabbing reports.' );
	$n_bugs = $grabber->grabReports( $bbstats->getQuantity(), $t_reputation );
	if( !$n_bugs ) {
		Utils::printError( 'Cannot retrieve reports!' );
		exit();
	}
	Utils::printSuccess( $n_bugs.' reports imported.' );
	
	$grabber->extractReportDatas();
	Utils::printSuccess( 'Datas extracted.' );
	
	if( $bbstats->getAutoTagMode() ) {
		Utils::printInfo( 'Trying to guess tags.' );
		Report::massAutoTag( $grabber->getReportsFinal() );
		Utils::printSuccess( 'Autotag finished.' );
	}
	
	if( $bbstats->getAutoRateMode() ) {
		Utils::printInfo( 'Trying to guess rating.' );
		Report::massAutoRate( $grabber->getReportsFinal() );
		Utils::printSuccess( 'Autorate finished.' );
	}
	
	if( $bbstats->isDemo() ) {
		$grabber->setReportsFinal( Utils::demonize($grabber->getReportsFinal()) );
	}
	
	Utils::printInfo( 'Adding new reports.' );
	list($n_new,$n_update) = $db->add( $grabber->getReportsFinal() );
	if( !$n_new && !$n_update ) {
		Utils::printError( 'Nothing new!' );
		exit();
	}
	Utils::printSuccess( $n_new.' new reports added, '.$n_update.' reports updated.' );
}
// ---


// save datas
{
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
}
// ---


Utils::printInfo( 'Exiting.' );
exit();
