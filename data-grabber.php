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
	
	if( isset($t_options['g']) ) {
		$bbstats->setProgram( $t_options['g'] );
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


// program import mode
if( $bbstats->getProgram() )
{
	$p = $bbstats->getPlatform();
	$grabber = new $p();
	
	$grabber->login();
	//echo "\n";
	
	Utils::printInfo( 'Trying to connect.' );
	if( ($c=$grabber->connect()) <= 0 ) {
		Utils::printError( 'Cannot connect to '.$grabber->getName().'! ('.$c.')' );
		exit();
	}
	Utils::printSuccess( 'Connected to '.$grabber->getName().'.' );
	
	Utils::printInfo( 'Grabbing program infos.' );
	$infos = $grabber->getProgramInfos( $bbstats->getProgram() );
	if( !$infos ) {
		Utils::printError( 'Cannot find the program "'.$bbstats->getProgram().'" on '.$grabber->getName().'!' );
		exit();
	}
	Utils::printSuccess( 'Program found.' );

	$program = new Program();
	$program->setPlatform( $grabber->getName() );
	$program->setInfos( $infos );
	
	Utils::printInfo( 'Grabbing hacktivity.' );
	$h = $grabber->grabProgramHacktivity( $bbstats->getProgram(), $t_reports );
	if( !$h ) {
		Utils::printError( 'Cannot grab hacktivity !' );
		exit();
	}
	echo "\n";
	Utils::printSuccess( 'Hacktivity grabbed.' );
	
	$cnt = $program->computeHacktivity( $t_reports );
	Utils::printSuccess( $cnt.' reports found.' );
	
	Utils::printInfo( 'Grabbing disclosed reports.' );
	$n_bugs = $grabber->grabProgramReports();
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
	
	Utils::printInfo( 'Adding new reports.' );
	$program->setReports( $grabber->getReportsFinal() );

	Utils::printInfo( 'Saving programs datas.' );
	if( !$program->save() ) {
		Utils::printError( 'Cannot save program!' );
		exit();
	}
	Utils::printSuccess( 'Program saved.' );

	Utils::printInfo( 'Exiting.' );
	exit();
}
// end of program import


// user import mode
{
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


	// someone ordered a rollback here!
	{
		if( $bbstats->getAction() == 'r' )
		{
			$t_backups = glob( DATABASE_PATH.'/db.json.*' );
			rsort( $t_backups );

			if( !count($t_backups) ) {
				Utils::printError( 'No history found!' );
				exit();
			}
			
			$restore = basename( $t_backups['0'] );
			$r = rename( DATABASE_PATH.'/'.$restore, DATABASE_FILE );
			if( !$r ) {
				Utils::printError( 'Cannot restore database!' );
				exit();
			}
			
			Utils::printSuccess( 'Backup "'.$restore.'" restored.' );
			exit();
		}
	}


	// process
	{
		$p = $bbstats->getPlatform();
		$grabber = new $p();
		
		if( !$bbstats->isImport() )
		{
			$grabber->login();
			//echo "\n";
			
			Utils::printInfo( 'Trying to connect.' );
			if( ($c=$grabber->connect()) <= 0 ) {
				Utils::printError( 'Cannot connect to '.$grabber->getName().'! ('.$c.')' );
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
} // end of user import

