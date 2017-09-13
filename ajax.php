<?php

/**
 * I don't believe in license
 * You can do want you want with this program
 * - gwen -
 */

if( !isset($_POST['_a']) ) {
	exit();
}
//var_dump($_POST);


require_once( 'config.php' );

$db = Database::getInstance();
if( !$db->load(DATABASE_FILE) ) {
	exit( 'Cannot load database, you should run the grabber first!' );
}


if( $_POST['_a'] == 'graph-reload' && isset($_POST['graph']) )
{
	$datas = null;

	switch( $_POST['graph'] ) {
		case 'bounties':
			$datas = Statistics::bounties( $db );
			break;
		case 'bounties-reports':
			$datas = Statistics::bounties_reports_evolution( $db );
			break;
		case 'reports-program-pie':
			$datas = Statistics::reports_program_pie( $db );
			break;
		case 'reports-tag-pie':
			$datas = Statistics::reports_tags_pie( $db );
			break;
		case 'reports-platform-pie':
			$datas = Statistics::reports_platform_pie( $db );
			break;
		case 'reports-rating':
			$datas = Statistics::reports_rating( $db );
			break;
		case 'reports-rating-pie':
			$datas = Statistics::reports_rating_pie( $db );
			break;
	}
	
	if( !is_null($datas) ) {
		echo $datas;
	}
	exit();
	
}


if( $_POST['_a'] == 'report-get' )
{
	if( !isset($_POST['key']) || ($key=trim($_POST['key']))=='' ) {
		exit();
	}
	
	$report = $db->getReport( $key );
	if( !$report ) {
		exit();
	}
	
	$report->total_bounty = $report->getTotalBounty();
	$report->str_tags = $report->getTags( true );
	$report->setCreatedAt( date('Y/m/d',$report->getCreatedAt()) );
	echo json_encode( $report );
	exit();
}


if( $_POST['_a'] == 'report-add' )
{
	$report = new Report();
	$report->setManual( true );
	
	if( isset($_POST['id']) ) {
		$report->setId( trim($_POST['id']) );
	} else {
		$report->setId( uniqid() );
	}
	if( isset($_POST['platform']) ) {
		$report->setPlatform( trim($_POST['platform']) );
	}
	if( isset($_POST['rating']) ) {
		$report->setRating( trim($_POST['rating']) );
	}
	if( isset($_POST['title']) ) {
		$report->setTitle( trim($_POST['title']) );
	}
	if( isset($_POST['program']) ) {
		$report->setProgram( trim($_POST['program']) );
	}
	if( isset($_POST['created_at']) ) {
		$report->setCreatedAt( strtotime(trim($_POST['created_at'])) );
	}
	if( isset($_POST['bounty']) ) {
		$report->setManualBounty( (int)$_POST['bounty'] );
	}
	if( isset($_POST['tags']) ) {
		if( trim($_POST['tags']) == '' ) {
			$report->setTags( [] );
		} else {
			$report->setTags( explode(',',trim($_POST['tags'],', ')) );
		}
	}
	
	$key = Report::generateKey( $report->getProgram(), $report->getId() );
	
	$db->setReport( $key, $report );
	$db->save();
	exit();
}


if( $_POST['_a'] == 'report-edit' )
{
	if( !isset($_POST['key']) || ($key=trim($_POST['key']))=='' ) {
		exit();
	}
	
	$report = $db->getReport( $key );
	if( !$report ) {
		exit();
	}
	
	if( isset($_POST['id']) && $report->getManual() ) {
		$report->setId( trim($_POST['id']) );
	}
	if( isset($_POST['platform']) && $report->getManual() ) {
		$report->setPlatform( trim($_POST['platform']) );
	}
	if( isset($_POST['rating']) ) {
		$report->setRating( trim($_POST['rating']) );
	}
	if( isset($_POST['title']) ) {
		$report->setTitle( trim($_POST['title']) );
	}
	if( isset($_POST['program']) ) {
		$report->setProgram( trim($_POST['program']) );
	}
	if( isset($_POST['bounty']) ) {
		$b = (int)$_POST['bounty'];
		if( $b != $report->getTotalBounty() ) {
			$report->setManualBounty( $b );
		}
	}
	if( isset($_POST['tags']) ) {
		if( trim($_POST['tags']) == '' ) {
			$report->setTags( [] );
		} else {
			$report->setTags( explode(',',trim($_POST['tags'],', ')) );
		}
	}
	if( isset($_POST['created_at']) ) {
		$report->setCreatedAt( strtotime(trim($_POST['created_at'])) );
	}
	
	$db->setReport( $key, $report );
	$db->save();
	exit();
}


if( $_POST['_a'] == 'report-delete' )
{
	if( !isset($_POST['key']) || ($key=trim($_POST['key']))=='' ) {
		exit();
	}
	
	$report = $db->getReport( $key );
	if( !$report ) {
		exit();
	}
	
	$db->deleteReport( $key );
	$db->save();
	exit();
}


if( $_POST['_a'] == 'tag-add' )
{
	if( !isset($_POST['key']) || ($key=trim($_POST['key']))=='' ) {
		exit();
	}
	
	$report = $db->getReport( $key );
	if( !$report ) {
		exit();
	}
	
	if( !isset($_POST['tag']) || ($tags=trim($_POST['tag']))=='' ) {
		exit();
	}

	$report->setTags( explode(',',trim($_POST['tag'],', ')) );
	
	$db->setReport( $key, $report );
	$db->save();
	exit();
}


exit( '-1' );
