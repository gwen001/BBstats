<?php

/**
 * I don't believe in license
 * You can do want you want with this program
 * - gwen -
 */

class Hackerone
{
	public $name = 'Hackerone';
	
	public $username = '';
	public $password = '';
	private $cookie_file;
	
	private $cookies = '';
	private $t_bugs = [];
	private $t_reports = [];
	private $t_reports_final = [];

	
	public function __construct() {
		//$this->cookie_file = tempnam('/tmp', 'cook_');
	}
	
	
	public function getReportsFinal() {
		return $this->t_reports_final;
	}
	public function setReportsFinal( $t_reports ) {
		$this->t_reports_final = $t_reports;
		return true;
	}
	
	
	public function login()
	{
		echo "Copy here your Hackerone cookie after you login: \n";
		$this->cookies = trim( fgets(STDIN) );
	}
	
	
	public function connect()
	{
		/*
		$c = curl_init();
		curl_setopt( $c, CURLOPT_URL, 'https://hackerone.com/sessions' );
		//curl_setopt( $c, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $c, CURLOPT_TIMEOUT, 5 );
		curl_setopt( $c, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $c, CURLOPT_COOKIEJAR, $this->cookie_file );
		curl_setopt( $c, CURLOPT_COOKIEFILE, $this->cookie_file );
		curl_setopt( $c, CURLOPT_POST, true );
		curl_setopt( $c, CURLOPT_POSTFIELDS, 'email=g@10degres.net&password=AQWzsx123&remember_me=false&fingerprint=257e5f05a9c037c485abf291e5c73607' );
		//curl_setopt( $c, CURLOPT_POSTFIELDS, 'email='.$this->username.'&password='.$this->password.'&remember_me=false&fingerprint=257e5f05a9c037c485abf291e5c73607' );
		curl_setopt( $c, CURLOPT_HTTPHEADER, ['User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:55.0) Gecko/20100101 Firefox/55.0'] );
		curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
		$r = curl_exec($c );
		$t_info = curl_getinfo( $c );
		var_dump( $r );
		var_dump( $t_info );
		*/
		/*
		$c = curl_init();
		curl_setopt( $c, CURLOPT_URL, 'https://hackerone.com/users/sign_in' );
		//curl_setopt( $c, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $c, CURLOPT_TIMEOUT, 5 );
		curl_setopt( $c, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $c, CURLOPT_COOKIEJAR, $this->cookie_file );
		curl_setopt( $c, CURLOPT_COOKIEFILE, $this->cookie_file );
		curl_setopt( $c, CURLOPT_POST, true );
		//curl_setopt( $c, CURLOPT_POSTFIELDS, 'authenticity_token=q%2FeHCkQA8lbaNA3Ykyu8pj%2B8smlAzI2yAsqv1pMu4pUw1kh4FqEHr%2BjLUx5t6Cbt1T7dmRNfUSTbJrVxXez6vA%3D%3D&user%5Bemail%5D='.$this->username.'&user%5Bpassword%5D='.$this->password );
		curl_setopt( $c, CURLOPT_POSTFIELDS, ['authenticity_token'=>'uQUzmITdJKDaMhWo1cyoVCUoRZFJRjPz 4mntlqI6xKtvYywDOxkuuortLnQ6tcLzBQDif2xPGBFYP4vhw LvQ==','user[email]'=>'g@10degres.net','user[password]'=>'AQWzsx123'] );
		curl_setopt( $c, CURLOPT_HTTPHEADER, ['User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:55.0) Gecko/20100101 Firefox/55.0'] );
		curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
		$r = curl_exec($c );
		$t_info = curl_getinfo( $c );
		var_dump( $r );
		var_dump( $t_info );
		*/

		return true;
	}
	
	
	public function grabReportList( $quantity )
	{
		$limit = 100;
		$n_page = ceil( $quantity/$limit );
		
		for( $page=1 ; $page<=$n_page ; $page++ )
		{
			echo '.';
			
			$c = curl_init();
			curl_setopt( $c, CURLOPT_URL, 'https://hackerone.com/bugs.json?subject=user&report_id=0&view=all&substates%5B%5D=new&substates%5B%5D=triaged&substates%5B%5D=needs-more-info&substates%5B%5D=resolved&substates%5B%5D=informative&substates%5B%5D=not-applicable&substates%5B%5D=duplicate&substates%5B%5D=spam&reported_to_team=&text_query=&program_states%5B%5D=2&program_states%5B%5D=3&program_states%5B%5D=4&program_states%5B%5D=5&sort_type=latest_activity&sort_direction=descending&limit='.$limit.'&page='.$page );
			//curl_setopt( $c, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $c, CURLOPT_TIMEOUT, 15 );
			curl_setopt( $c, CURLOPT_FOLLOWLOCATION, true );
			curl_setopt( $c, CURLOPT_COOKIE, $this->cookies );
			//curl_setopt( $c, CURLOPT_COOKIEJAR, $this->cookie_file );
			//curl_setopt( $c, CURLOPT_COOKIEFILE, $this->cookie_file );
			curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
			$data = curl_exec($c );
			$t_info = curl_getinfo( $c );
			file_put_contents( DATABASE_PATH.'/page_'.$page.'.json', $data );
			//$data = @file_get_contents( 'data/page_'.$page.'.json' );
			//var_dump( $data );
			//var_dump( $t_info );
			
			if( !$data ) {
			//if( !$data || $t_info['http_code']!=200 || !$t_info['size_download'] ) {
				return false;
			} else {
				$t_data = json_decode( $data, true );
				$this->t_bugs = array_merge( $this->t_bugs, $t_data['bugs'] );
				if( $t_data['pages'] < $n_page ) {
					$n_page = $t_data['pages'];
				}
			}
		}
		
		echo "\n";

		return $n_page;
	}
	
	
	public function grabReports( $db, $quantity, $update, $overwrite )
	{
		for( $n=0 ; $n<$quantity && list($k,$bug)=each($this->t_bugs) ; $n++ )
		{
			$report_id = $bug['id'];
			$key = Report::generateKey( $this->name, $report_id );

			if( !$db->exists($key) || $update || $overwrite )
			{
				$report = $this->grabReport( $report_id );
				if( $report ) {
					$this->t_reports[$key] = $report;
				}
			}
		}
		
		echo "\n";

		return count($this->t_reports);
	}
	
	
	private function grabReport( $report_id )
	{
		$c = curl_init();
		curl_setopt( $c, CURLOPT_URL, 'https://hackerone.com/reports/'.$report_id.'.json' );
		//curl_setopt( $c, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $c, CURLOPT_TIMEOUT, 15 );
		curl_setopt( $c, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $c, CURLOPT_COOKIE, $this->cookies );
		//curl_setopt( $c, CURLOPT_COOKIEJAR, $this->cookie_file );
		//curl_setopt( $c, CURLOPT_COOKIEFILE, $this->cookie_file );
		curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
		$data = curl_exec($c );
		$t_info = curl_getinfo( $c );
		file_put_contents( DATABASE_PATH.'/report_'.$report_id.'.json', $data );
		//$data = @file_get_contents( 'data/report_'.$report_id.'.json' );
		//var_dump( $data );
		//var_dump( $t_info );
		
		if( !$data ) {
		//if( !$data || $t_info['http_code']!=200 || !$t_info['size_download'] ) {
			return false;
		}

		echo '.';
		$t_data = json_decode( $data, true );
		
		return $t_data;
	}
	
	
	public function grabReputation( $db )
	{
		$page = 1;
		$t_reput = [];
		
		do
		{
			$c = curl_init();
			curl_setopt( $c, CURLOPT_URL, 'https://hackerone.com/settings/reputation/log?page='.$page );
			//curl_setopt( $c, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $c, CURLOPT_TIMEOUT, 15 );
			curl_setopt( $c, CURLOPT_FOLLOWLOCATION, true );
			curl_setopt( $c, CURLOPT_COOKIE, $this->cookies );
			//curl_setopt( $c, CURLOPT_COOKIEJAR, $this->cookie_file );
			//curl_setopt( $c, CURLOPT_COOKIEFILE, $this->cookie_file );
			curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
			$data = curl_exec($c );
			$t_info = curl_getinfo( $c );
			file_put_contents( DATABASE_PATH.'/reput_'.$page.'.html', $data );
			//$data = @file_get_contents( 'data/reput_'.$page.'.html' );
			//var_dump( $data );
			//var_dump( $t_info );
			
			if( !$data ) {
			//if( !$data || $t_info['http_code']!=200 || !$t_info['size_download'] ) {
				return false;
			}
	
			echo '.';
			$page++;
			
			$t_reput = array_merge( $t_reput, $this->extractReputation($data) );
			//var_dump($t_reput);
		
			$grab = preg_match( '#audit-log-item#', $data );
		}
		while( $grab );
		
		$t_reports = $db->getReports();
		foreach( $t_reports as $report ) {
			$report->resetReputation();
		}
		
		foreach( $t_reput as $reput ) {
			$report = $db->getReportById( $this->name, $reput['report_id'] );
			if( $report ) {
				$report->addReputation( $reput['created_at'], $reput['points'] );
			}
		}
		
		echo "\n";

		return ($page-1);
	}
	
	
	private function extractReputation( $data )
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
	
	
	public function extractDatas()
	{
		foreach( $this->t_reports as $key=>$report )
		{
			$t = [];
			
			$r = new Report();
			$r->setPlatform( strtolower($this->name) );
			$r->setId( $report['id'] );
			$r->setTitle( $report['title'] );
			$r->setCreatedAt( strtotime($report['created_at']) );
			$r->setProgram( $report['team']['handle'] );
			$r->setState( $report['substate'] );
			
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
}
