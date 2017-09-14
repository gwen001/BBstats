<?php

/**
 * I don't believe in license
 * You can do want you want with this program
 * - gwen -
 */

class Database
{
	private static $_instance = null;

	private $db_file;
	private $datas;
	private $loaded = false;

	
	private function __construct() {
	}

	
	public static function getInstance() {
		if( is_null(self::$_instance) ) {
			$c = __CLASS__;
			self::$_instance = new $c();
		}
		return self::$_instance;
	}
	
	
	private function getData( $key ) {
		if( isset($this->datas->$key) ) {
			return $this->datas->$key;
		} else {
			return false;
		}
	}
	private function setData( $key, $value ) {
		$this->datas->$key = $value;
	}
	
	
	public function getReportById( $platform, $report_id ) {
		$t_reports = $this->getReports();
		$platform = strtolower( $platform );
		foreach( $t_reports as $r ) {
			if( $r->getPlatform()==$platform && $r->getId()==$report_id ) {
				return $r;
			}
		}
		return false;
	}
	public function getReport( $key ) {
		$t_reports = $this->getReports();
		if( !isset($t_reports[$key]) ) {
			return false;
		}
		return $t_reports[$key];
	}
	public function setReport( $key, $report ) {
		$t_reports = $this->getReports();
		$t_reports[ $key ] = $report;
		$this->setReports( $t_reports );
		return true;
	} // this is not a hole...
	public function deleteReport( $key ) {
		if( !$this->getReport($key) ) {
			return false;
		}
		$t_reports = $this->getReports();
		unset( $t_reports[$key] );
		$this->setReports( $t_reports );
		return true;
	}
	
	public function getReports() {
		return $this->getData( 'reports' );
	}
	private function setReports( $t_reports ) {
		$this->setData( 'reports', $t_reports );
		$this->setData( 'total_report', count($t_reports) );
		return true;
	}
	
	
	public function getTotalReport() {
		return $this->getData('total_report');
	}
	private function setTotalReport( $v ) {
		$this->setData( 'total_report', (int)$v );
		return true;
	}
	
	
	public function getTotalBounty() {
		return $this->getData('total_bounty');
	}
	private function setTotalBounty( $v ) {
		$this->setData( 'total_bounty', (int)$v );
		return true;
	}
	
	
	public function getTotalReputation() {
		return $this->getData('total_reputation');
	}
	private function setTotalReputation( $v ) {
		$this->setData( 'total_reputation', (int)$v );
		return true;
	}
	
	
	public function getFirstReport() {
		if( $this->getData('first_report') ) {
			return $this->getReport( $this->getData('first_report') );
		} else {
			return false;
		}
	}
	private function setFirstReport( $key ) {
		$this->setData( 'first_report', $key );
		return true;
	}
	
	
	public function getFirstReportDate() {
		$first_report = $this->getFirstReport();
		if( !$first_report ) {
			return false;
		}
		return $first_report->getCreatedAt();
	}
	
	
	public function exists( $key )
	{
		$t_reports = $this->getReports();
		return isset($t_reports[$key]);
	}
	
	
	public function load( $source, $force=false )
	{
		if( !$this->loaded || $force )
		{
			$this->datas = new stdClass();
			$this->db_file = $source;
			
			if( !is_file($source) ) {
				$a = file_put_contents( $source, '' );
				if( $a === false ) {
					return false;
				}
			}
			
			$datas = file_get_contents( $source );
			if( $datas === false ) {
				return false;
			}
			
			if( strlen($datas) )
			{
				$this->loaded = true;
				$this->datas = json_decode( $datas );
				$this->datas->reports = (array)$this->datas->reports;
				
				foreach( $this->datas->reports as $k=>$v ) {
					$this->datas->reports[ $k ] = Utils::array2object( $v, 'Report' );
				}
			}
		}
		
		return true;
	}
	
	
	public function add( $t_new )
	{
		$bbstats = BBstats::getInstance();
		$autorate = $bbstats->getAutoRateMode();
		$autotag = $bbstats->getAutoTagMode();
		$reputation = $bbstats->isReputation();
		$n_new = $n_update = 0;

		// $report = new report to add
		// $r = the old report in the current database
		
		foreach( $t_new as $key=>$report )
		{
			$r = $this->getReport( $key );
			
			if( !$r ) {
				$n_new++;
				$this->setReport( $key, $report );
			}
			elseif( $bbstats->overwriteAllowed() ) {
				$n_update++;
				if( $reputation ) {
					$report->setReputations( $r->getReputations() );
				}
				if( $autotag == 0 || $autotag == 1 ) {
					if( count($r->getTags()) != 0 ) {
						$report->setTags( $r->getTags() ); // we want to keep tags that were manually setted, the new report got the old tags
					}
				} elseif( $autotag == 2 ) {
					;
				}
				if( $autorate == 0 || $autorate == 1 ) {
					if( $r->getRating() != 0 ) {
						$report->setRating( $r->getRating() ); // we want to keep rating that was manually setted, the new report got the old rating
					}
				} elseif( $autorate == 2 ) {
					;
				}
				$this->setReport( $key, $report ); // adding the new report (or overwrite the existing one)
			}
			elseif( $bbstats->updateAllowed() ) {
				$n_update++;
				// copy the properties of the new report to the old one
				$r->setTitle( $report->getTitle() );
				$r->setBounties( $report->getBounties() );
				$r->setState( $report->getState() );
				if( $reputation ) {
					$r->setReputation( $report->getReputation() ); // we DON'T want to keep the old reputation, overwrite
				}
				if( $autotag == 1 ) {
					//if( count($r->getTags()) == 0 ) {
						$r->setTags( array_merge($r->getTags(),$report->getTags()) ); // we want to keep old and new tags, merge
					//}
				} elseif( $autotag == 2 ) {
					$r->setTags( $report->getTags() ); // we DON'T want to keep the old tags, overwrite
				}
				if( $autorate == 1 ) {
					if( $r->getRating() == 0 ) {
						$r->setRating( $report->getRating() ); // force overwrite only if the rating has not been setted before
					}
				} elseif( $autorate == 2 ) {
					$r->setRating( $report->getRating() ); // we DON'T want to keep the old rating, overwrite
				}
			}
		}
		
		return [$n_new,$n_update];
	}
	
	
	public function save()
	{
		$t_date = [];
		$t_reports = $this->getReports();
		$first_report_date = null;
		$total_report = $total_bounty = $total_reputation = 0;
		
		foreach( $t_reports as $key=>$report )
		{
			$t_date[$key] = $report->getCreatedAt();
			if( is_null($first_report_date) || $report->getCreatedAt()<$first_report_date ) {
				$this->setFirstReport( $key );
				$first_report_date = $report->getCreatedAt();
			}

			$total_report++;
			$total_bounty += $report->getTotalBounty();
			$total_reputation += $report->getTotalReputation();
		}
		
		array_multisort( $t_date, SORT_ASC, $t_reports );
		
		$this->setReports( $t_reports );
		$this->setTotalReport( $total_report );
		$this->setTotalBounty( $total_bounty );
		$this->setTotalReputation( $total_reputation );
		
		return file_put_contents( $this->db_file, json_encode($this->datas) );
	}

	
	public function backup()
	{
		if( !is_file($this->db_file) ) {
			return false;
		}
		
		$bak_file = $this->db_file . '.' . time();
		$bak = copy( $this->db_file, $bak_file );
		
		if( $bak ) {
			return $bak_file;
		} else {
			return false;
		}
	}
}
