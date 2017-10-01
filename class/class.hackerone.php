<?php

/**
 * I don't believe in license
 * You can do want you want with this program
 * - gwen -
 */

class Hackerone extends Platform
{
	CONST REPORT_PAGE_LIMIT = 100;
	
	private $cookies = '';
	
	
	public function __construct() {
		$this->setName( 'hackerone' );
	}
	
	
	public function login()
	{
		echo "Copy here your Hackerone cookie after you login: \n";
		$this->cookies = trim( fgets(STDIN) );
	}
	
	
	public function connect()
	{
		$c = curl_init();
		curl_setopt( $c, CURLOPT_URL, 'https://hackerone.com/bugs.json?subject=user&report_id=0&view=all&substates%5B%5D=new&substates%5B%5D=triaged&substates%5B%5D=needs-more-info&substates%5B%5D=resolved&substates%5B%5D=informative&substates%5B%5D=not-applicable&substates%5B%5D=duplicate&substates%5B%5D=spam&reported_to_team=&text_query=&program_states%5B%5D=2&program_states%5B%5D=3&program_states%5B%5D=4&program_states%5B%5D=5&sort_type=latest_activity&sort_direction=descending&limit=25&page=1' );
		curl_setopt( $c, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:55.0) Gecko/20100101 Firefox/55.0' );
		//curl_setopt( $c, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $c, CURLOPT_TIMEOUT, 15 );
		curl_setopt( $c, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $c, CURLOPT_COOKIE, $this->cookies );
		//curl_setopt( $c, CURLOPT_COOKIEJAR, $this->cookie_file );
		//curl_setopt( $c, CURLOPT_COOKIEFILE, $this->cookie_file );
		curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
		$data = curl_exec($c );
		$t_info = curl_getinfo( $c );
		//var_dump( $data );
		//var_dump( $t_info );
		
		if( !$data || stristr($data,'You need to sign in') || stristr($data,'Sign in to HackerOne') ) {
		//if( !$data || $t_info['http_code']!=200 || !$t_info['size_download'] ) {
			return false;
		}
		
		return true;
	}
	
	
	public function grabReportList( $quantity )
	{
		$bbstats = BBstats::getInstance();
		if( $bbstats->isImport() ) {
			return $this->grabReportListFromFile( $quantity );
		}

		$n_page = ceil( $quantity/self::REPORT_PAGE_LIMIT );
		
		for( $page=1 ; $page<=$n_page ; $page++ )
		{
			$c = curl_init();
			curl_setopt( $c, CURLOPT_URL, 'https://hackerone.com/bugs.json?subject=user&report_id=0&view=all&substates%5B%5D=new&substates%5B%5D=triaged&substates%5B%5D=needs-more-info&substates%5B%5D=resolved&substates%5B%5D=informative&substates%5B%5D=not-applicable&substates%5B%5D=duplicate&substates%5B%5D=spam&reported_to_team=&text_query=&program_states%5B%5D=2&program_states%5B%5D=3&program_states%5B%5D=4&program_states%5B%5D=5&sort_type=latest_activity&sort_direction=descending&limit='.self::REPORT_PAGE_LIMIT.'&page='.$page );
			curl_setopt( $c, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:55.0) Gecko/20100101 Firefox/55.0' );
			//curl_setopt( $c, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $c, CURLOPT_TIMEOUT, 15 );
			curl_setopt( $c, CURLOPT_FOLLOWLOCATION, true );
			curl_setopt( $c, CURLOPT_COOKIE, $this->cookies );
			//curl_setopt( $c, CURLOPT_COOKIEJAR, $this->cookie_file );
			//curl_setopt( $c, CURLOPT_COOKIEFILE, $this->cookie_file );
			curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
			$data = curl_exec($c );
			$t_info = curl_getinfo( $c );
			//file_put_contents( DATABASE_PATH.'/page_'.$page.'.json', $data );
			//$data = @file_get_contents( 'data/page_'.$page.'.json' );
			//var_dump( $data );
			//var_dump( $t_info );
			
			if( !$data || stristr($data,'You need to sign in') || stristr($data,'Sign in to HackerOne') ) {
			//if( !$data || $t_info['http_code']!=200 || !$t_info['size_download'] ) {
				Utils::_print( '.', 'dark_grey' );
				return false;
			} else {
				$t_data = json_decode( $data, true );
				$this->t_bugs = array_merge( $this->t_bugs, $t_data['bugs'] );
				if( $t_data['pages'] < $n_page ) {
					$n_page = $t_data['pages'];
				}
			}
			
			Utils::_print( '.', 'white' );
		}
		
		return $n_page;
	}
	
	
	protected function grabReportListFromFile( $quantity )
	{
		$bbstats = BBstats::getInstance();
		
		$fp = fopen( $bbstats->getSourceFile(), 'r' );
		if( !$fp ) {
			return false;
		}
		
		for( $i=0 ; ($line=fgetcsv($fp)) && $i<=$quantity ; $i++ )
		{
			if( !$i ) {
				continue;
			}
			
			$report_id = $line[0];
			$this->t_bugs[ $report_id ] = array_merge( ['id'=>$report_id], $line);
		}
		
		Utils::_print( '.', 'white' );

		return $i;
	}
	
	
	public function grabReports( $quantity, $t_reputation )
	{
		$bbstats = BBstats::getInstance();
		if( $bbstats->isImport() ) {
			return $this->grabReportsFromFile( $quantity, $t_reputation );
		}
		
		$db = $bbstats->getDatabase();
		
		for( $n=0 ; $n<$quantity && list($k,$bug)=each($this->t_bugs) ; $n++ )
		{
			$report_id = $bug['id'];
			$key = Report::generateKey( $this->getName(), $bug['team']['handle'], $report_id );

			if( !$db->exists($key) || $bbstats->updateAllowed() || $bbstats->overwriteAllowed() )
			{
				$report = $this->grabReport( $report_id );
				
				if( $report ) {
					if( $t_reputation && isset($t_reputation[$report_id]) ) {
						$report['reputation'] = $t_reputation[$report_id];
					}
					$this->t_reports[$key] = $report;
				}
			}
		}
		
		echo "\n";

		return count($this->t_reports);
	}
	
	
	public function grabReportsFromFile( $quantity, $t_reputation )
	{
		$bbstats = BBstats::getInstance();
		$db = $bbstats->getDatabase();

		for( $n=0 ; $n<$quantity && list($k,$bug)=each($this->t_bugs) ; $n++ )
		{
			$report_id = $bug['id'];
			$key = Report::generateKey( $this->getName(), $bug['team']['handle'], $report_id );

			if( !$db->exists($key) || $bbstats->updateAllowed() || $bbstats->overwriteAllowed() )
			{
				$report = $this->grabReportFromFile( $report_id );
				
				if( $report ) {
					if( $t_reputation && isset($t_reputation[$report_id]) ) {
						$report['reputation'] = $t_reputation[$report_id];
					}
					$this->t_reports[$key] = $report;
				}
			}
		}
		
		echo "\n";

		return count($this->t_reports);
	}
			
	
	protected function grabReport( $report_id )
	{
		$c = curl_init();
		curl_setopt( $c, CURLOPT_URL, 'https://hackerone.com/reports/'.$report_id.'.json' );
		curl_setopt( $c, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:55.0) Gecko/20100101 Firefox/55.0' );
		//curl_setopt( $c, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $c, CURLOPT_TIMEOUT, 15 );
		curl_setopt( $c, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $c, CURLOPT_COOKIE, $this->cookies );
		//curl_setopt( $c, CURLOPT_COOKIEJAR, $this->cookie_file );
		//curl_setopt( $c, CURLOPT_COOKIEFILE, $this->cookie_file );
		curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
		$data = curl_exec($c );
		$t_info = curl_getinfo( $c );
		//file_put_contents( DATABASE_PATH.'/report_'.$report_id.'.json', $data );
		//$data = @file_get_contents( 'data/report_'.$report_id.'.json' );
		//var_dump( $data );
		//var_dump( $t_info );
		
		if( !$data || stristr($data,'You need to sign in') || stristr($data,'Sign in to HackerOne') ) {
		//if( !$data || $t_info['http_code']!=200 || !$t_info['size_download'] ) {
			Utils::_print( '.', 'dark_grey' );
			return false;
		}

		Utils::_print( '.', 'white' );
		
		$t_data = json_decode( $data, true );
		
		return $t_data;
	}

	
	protected function grabReportFromFile( $report_id )
	{
		if( !isset($this->t_bugs[$report_id]) ) {
			return false;
		}
		
		$bug = $this->t_bugs[$report_id];
		
		$tmp = [];
		$tmp['id'] = $bug[0];
		$tmp['title'] = $bug[1];
		$tmp['team'] = [];
		$tmp['team']['handle'] = $bug[2];
		$tmp['activities'] = [];
		$tmp['activities'][] = [ 'bounty_amount'=>$bug[3], 'created_at'=>$bug[7] ];
		$tmp['created_at'] = $bug[7];
		$tmp['substate'] = $bug[8];
		
		Utils::_print( '.', 'white' );
		
		return $tmp;
	}

	
	public function extractReportDatas()
	{
		foreach( $this->t_reports as $key=>$report )
		{
			$t = [];
			
			$r = new Report();
			$r->setPlatform( $this->getName() );
			$r->setId( $report['id'] );
			$r->setTitle( $report['title'] );
			$r->setCreatedAt( strtotime($report['created_at']) );
			$r->setProgram( $report['team']['handle'] );
			$r->setState( $report['substate'] );
			
			if( isset($report['reputation']) ) {
				foreach( $report['reputation'] as $reput ) {
					$r->addReputation( $reput['created_at'], $reput['points'] );
				}
			}
			
			foreach( $report['activities'] as $activity )
			{
				$bounty_amount = 0;
				
				if( isset($activity['bonus_amount']) ) {
					$bounty_amount += $activity['bonus_amount'];
				}
				if( isset($activity['bounty_amount']) ) {
					$bounty_amount += $activity['bounty_amount'];
				}
				
				if( $bounty_amount ) {
					$r->addBounty( strtotime($activity['created_at']), $bounty_amount );
				}
			}

			$this->t_reports_final[ $key ] = $r;
		}
	}
	
	
	public static function getReportLink( $report_id ) {
		return 'https://hackerone.com/reports/'.$report_id;
	}
	
	
	public function grabReputation()
	{
		$bbstats = BBstats::getInstance();
		if( $bbstats->isImport() ) {
			return false;
		}
			
		$page = 1;
		$t_reput = [];
		$t_headers = [
			'Accept: application/json, text/javascript, */*; q=0.01',
			'Accept-Language: en-US,en;q=0.5',
			'X-Requested-With: XMLHttpRequest',
			'Referer: https://hackerone.com/settings/reputation/log',
		];
		
		do
		{
			$c = curl_init();
			curl_setopt( $c, CURLOPT_URL, 'https://hackerone.com/settings/reputation/log?page='.$page );
			curl_setopt( $c, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:55.0) Gecko/20100101 Firefox/55.0' );
			//curl_setopt( $c, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $c, CURLOPT_HTTPHEADER, $t_headers );
			curl_setopt( $c, CURLOPT_TIMEOUT, 15 );
			curl_setopt( $c, CURLOPT_FOLLOWLOCATION, true );
			curl_setopt( $c, CURLOPT_COOKIE, $this->cookies );
			//curl_setopt( $c, CURLOPT_COOKIEJAR, $this->cookie_file );
			//curl_setopt( $c, CURLOPT_COOKIEFILE, $this->cookie_file );
			curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
			$data = curl_exec($c );
			$t_info = curl_getinfo( $c );
			//file_put_contents( DATABASE_PATH.'/reput_'.$page.'.json', $data );
			//$data = @file_get_contents( 'data/reput_'.$page.'.html' );
			//var_dump( $data );
			//var_dump( $t_info );
			
			if( !$data || stristr($data,'You need to sign in') || stristr($data,'Sign in to HackerOne') ) {
			//if( !$data || $t_info['http_code']!=200 || !$t_info['size_download'] ) {
				return false;
			}
			
			echo '.';
			$page++;
			
			$t_reput = array_merge( $t_reput, $this->extractReputationJson($data) );
			//var_dump( $t_reput );
			
			$grab = !preg_match( '#user_joined#', $data );
			//$grab = preg_match( '#audit-log-item#', $data );
		}
		while( $grab );
		
		$t_final = [];
		foreach( $t_reput as $reput )
		{
			$report_id = $reput['report_id'];

			if( !isset($t_final[$report_id]) ) {
				$t_final[$report_id] = [];
			}
			
			$t_final[$report_id][] = [ 'created_at'=>$reput['created_at'], 'points'=>$reput['points'] ];
		}

		echo "\n";

		return $t_final;
	}
	
	
	public function grabReputationFromFile() {
		
	}

	
	protected function extractReputation( $data )
	{
		$doc = new DOMDocument();
		$doc->preserveWhiteSpace = false;
		@$doc->loadHTML( $data );
		
		$t_reput = [];
		$xpath = new DOMXPath( $doc );
		$t_items = $xpath->query("//div[contains(@class,'audit-log-item')]");
		//var_dump( $t_items );
		
		foreach( $t_items as $item )
		{
			$date = $xpath->query("span[contains(@class,'meta-text')]/span[@title]", $item );
			foreach( $date[0]->attributes as $attr ) {
	            $ts = strtotime( $attr->nodeValue );
	        }
			//var_dump($ts);

			$point = $xpath->query("span[contains(@class,'reputation-change-badge')]", $item );
			$points = (int)($point[0]->nodeValue);
			//var_dump( $points );
			
			$report = $xpath->query("a[contains(@href,'/reports/')]", $item );
			if( $report && isset($report[0]) ) {
				foreach( $report[0]->attributes as $attr ) {
		            $report_id = (int)str_replace( '/reports/', '', $attr->nodeValue );
		        }
		        //var_dump( $report_id );
	        	$t_reput[] = [ 'report_id'=>$report_id, 'created_at'=>$ts, 'points'=>$points ];
			}
		}
		
		return $t_reput;
	}
	
	
	protected function extractReputationJson( $data )
	{
		$data = json_decode( $data, true );
		$t_reput = [];
		
		foreach( $data['reputations'] as $item )
		{
			if( $item['type'] == 'user_joined' ) {
				continue;
			}
			
	        $ts = strtotime( $item['created_at'] );
	        $report_id = $item['report']['id'];
	        $points = $item['change'];

	        $t_reput[] = [ 'report_id'=>$report_id, 'created_at'=>$ts, 'points'=>$points ];
		}

		return $t_reput;
	}
}
