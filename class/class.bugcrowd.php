<?php

/**
 * I don't believe in license
 * You can do want you want with this program
 * - gwen -
 */

class Bugcrowd extends Platform
{

	public function __construct() {
		$this->setName( 'bugcrowd' );
	}
	
	
	public function login()
	{
		$this->username = getenv( 'BUGCROWD_USERNAME' );
		$this->password = getenv( 'BUGCROWD_PASSWORD' );

		if( !$this->username || !$this->password ) {
			Utils::printError( 'Credentials not found!' );
			return false;
		}

		return true;
	}
		
	
	public function connect()
	{
		$this->cookie_file = tempnam( '/tmp', 'cook_' );

		$c = curl_init();
		curl_setopt( $c, CURLOPT_URL, 'https://bugcrowd.com/user/sign_in' );
		curl_setopt( $c, CURLOPT_TIMEOUT, 15 );
		curl_setopt( $c, CURLOPT_FOLLOWLOCATION, false );
		curl_setopt( $c, CURLOPT_COOKIEJAR, $this->cookie_file );
		curl_setopt( $c, CURLOPT_COOKIEFILE, $this->cookie_file );
		curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $c, CURLOPT_HTTPHEADER, ['User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0'] );
		$data = curl_exec($c );
		$t_info = curl_getinfo( $c );
		//echo $data;
		//var_dump( $t_info['http_code'] );
		//$data = file_get_contents( 'data/bc_signin.html' );

		if( !$data || $t_info['http_code']!=200 ) {
			return -1;
		}

		$m = preg_match( '#<meta name="csrf-token" content="(.*)" />#', $data, $t_match );
		var_dump( $t_match );
		if( !$m ) {
			return -2;
		}

		$this->csrf_token = $t_match[1];

		$c = curl_init();
		curl_setopt( $c, CURLOPT_URL, 'https://bugcrowd.com/user/sign_in' );
		curl_setopt( $c, CURLOPT_TIMEOUT, 15 );
		curl_setopt( $c, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $c, CURLOPT_COOKIEJAR, $this->cookie_file );
		curl_setopt( $c, CURLOPT_COOKIEFILE, $this->cookie_file );
		curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $c, CURLOPT_HTTPHEADER, ['User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0'] );
		curl_setopt( $c, CURLOPT_POST, true );
		curl_setopt( $c, CURLOPT_POSTFIELDS, 'utf8=%E2%9C%93&authenticity_token='.urlencode($this->csrf_token).'&user%5Bredirect_to%5D=&user%5Bemail%5D='.urlencode($this->username).'&user%5Bpassword%5D='.urlencode($this->password).'&commit=Log+in' );
		$data = curl_exec($c );
		$t_info = curl_getinfo( $c );
		//echo $data;
		//var_dump( $t_info['http_code'] );
		//$data = file_get_contents( 'data/bc_signin.html' );

		if( !$data || $t_info['http_code']!=200 ) {
			return -3;
		}
		
		return true;
	}
	
	
	public function grabReportList( $quantity )
	{
		$c = curl_init();
		curl_setopt( $c, CURLOPT_URL, 'https://bugcrowd.com/submissions' );
		curl_setopt( $c, CURLOPT_TIMEOUT, 15 );
		curl_setopt( $c, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $c, CURLOPT_COOKIEJAR, $this->cookie_file );
		curl_setopt( $c, CURLOPT_COOKIEFILE, $this->cookie_file );
		curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $c, CURLOPT_HTTPHEADER, ['User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0'] );
		$data = curl_exec($c );
		$t_info = curl_getinfo( $c );
		//echo $data;
		//var_dump( $t_info['http_code'] );
		//file_put_contents( 'data/bc_submission.html', $data );
		$data = file_get_contents( 'data/bc_submission.html' );

		if( !$data || $t_info['http_code']!=200 ) {
			return false;
		}

		$m = preg_match( '#<div data-react-class="ResearcherSubmissionsApp" data-react-props="(.*)" data-reducer="researcherSubmissionsApp" class="react-component react-component-researcher-submissions-app "></div>#', $data, $t_match );
		//var_dump( $t_match );
		$t_data = json_decode( html_entity_decode( urldecode( $t_match[1] ) ), true );
		//var_dump( $t_submission );

		// deal with page
		{
			$this->t_bugs = array_merge( $this->t_bugs, $t_data['submissions'] );
			Utils::_print( '.', 'white' );
			$n_page = 1;
		}

		return $n_page;
	}
	
	
	protected function grabReportListFromFile( $quantity )
	{
	}

	
	public function grabReports( $quantity, $t_reputation )
	{
		$bbstats = BBstats::getInstance();
		$db = $bbstats->getDatabase();
		
		for( $n=0 ; $n<$quantity && list($k,$bug)=each($this->t_bugs) ; $n++ )
		{
			$report_id = $bug['reference_number'];
			$key = Report::generateKey( $this->getName(), $bug['program_name'], $report_id );

			if( !$db->exists($key) || $bbstats->updateAllowed() || $bbstats->overwriteAllowed() )
			{
				$report = array_merge( $bug, $this->grabReport($report_id) );
				//$report['reputation'] = $bug['points'];
				$this->t_reports[$key] = $report;
			}
		}
		
		echo "\n";

		return count($this->t_reports);
	}
	
	
	public function grabReportsFromFile( $quantity, $t_reputation )
	{
	}
	
	
	protected function grabReport( $report_id )
	{
		$c = curl_init();
		curl_setopt( $c, CURLOPT_URL, 'https://bugcrowd.com/submissions/'.$report_id );
		curl_setopt( $c, CURLOPT_TIMEOUT, 15 );
		curl_setopt( $c, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $c, CURLOPT_COOKIEJAR, $this->cookie_file );
		curl_setopt( $c, CURLOPT_COOKIEFILE, $this->cookie_file );
		curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $c, CURLOPT_HTTPHEADER, ['User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0'] );
		$data = curl_exec($c );
		$t_info = curl_getinfo( $c );
		//echo $data;
		//var_dump( $t_info['http_code'] );
		//file_put_contents( 'data/'.$report_id.'.html', $data );
		//$data = file_get_contents( 'data/'.$report_id.'.html' );

		if( !$data || $t_info['http_code']!=200 ) {
			Utils::_print( '.', 'dark_grey' );
			return false;
		}

		Utils::_print( '.', 'white' );
		
		$m = preg_match( '#<div data-react-class="ResearcherNavbar" data-react-props="(.*)" data-reducer="researcherNavbar" class="react-component react-component-researcher-navbar "></div>#', $data, $t_match );
		//var_dump( $t_match );
		$t_user = json_decode( html_entity_decode( urldecode( $t_match[1] ) ), true );
		
		$m = preg_match( '#<div data-react-class="ActivityFeed" data-react-props="(.*)" data-reducer="activityFeed" class="react-component react-component-activity-feed "></div>#', $data, $t_match );
		//var_dump( $t_match );
		$t_data = json_decode( html_entity_decode( urldecode( $t_match[1] ) ), true );

		return array_merge( $t_user, $t_data );
	}
	
	
	protected function grabReportFromFile( $report_id )
	{
	}
	
	
	public function extractReportDatas()
	{
		foreach( $this->t_reports as $key=>$report )
		{
			$t = [];

			$r = new Report();
			$r->setPlatform( $this->getName() );
			$r->setId( $report['reference_number'] );
			$r->setReporter( $report['username'] );
			$r->setTitle( $report['caption'] );
			$r->setCreatedAt( strtotime($report['created_at']) );
			$r->setProgram( $report['program_name'] );
			$r->setState( $report['substate'] );
			$r->addReputation( strtotime($report['created_at']), (int)$report['points'] );
			
			foreach( $report['activities'] as $activity )
			{
				switch( $activity['key'] )
				{
					/*case 'submission.created':
						$r->setCreatedAt( strtotime($activity['created_at']) );
						break;*/
					/*case 'point_reward.created':
						$r->addReputation( $activity['created_at'], (int)$activity['params']['amount'] );
						break;*/
					case 'submission.updated':
					case 'submission.vrt_updated':
						if( (int)$activity['params']['priority'] ) {
							$r->setRating( (int)$activity['params']['priority'] );
						}
						break;
					case 'submission.reward_created':
						$bounty_amount = str_replace( '$', '', $activity['params']['amount'] );
						$r->addBounty( strtotime($activity['created_at']), $bounty_amount );
						if( !$r->getFirstBountyDate() ) {
							$r->setFirstBountyDate( strtotime($activity['created_at']) );
						}
						break;
					case 'tester_message.created':
						if( $activity['actor']['name'] != $report['username'] ) {
							$r->setFirstResponseDate( strtotime($activity['created_at']) );
						}
						break;
						case 'submission.transitioned':
						if( $activity['params']['substate'] == 'triaged' ) {
							$r->setTriageDate( strtotime($activity['created_at']) );
						}
						break;
					case 'submission.transitioned':
						if( $activity['params']['substate'] == 'resolved' ) {
							$r->setResolutionDate( strtotime($activity['created_at']) );
						}
						break;
				}
			}

			$this->t_reports_final[ $key ] = $r;
		}
	}
	
	
	public static function getReportLink( $report_id ) {
		return 'https://bugcrowd.com/submissions/'.$report_id;
	}


	public function grabReputation()
	{
		return true;
	}


	public function grabReputationFromFile()
	{
	}
}
