<?php

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


    public function getDbFile() {
        return $this->db_file;
    }


    public function getId() {
        return $this->getData( 'id' );
    }
    public function setId( $v ) {
        $this->setData( 'id', $v );
        return true;
    }


    public function getName() {
        return $this->getData( 'name' );
    }
    public function setName( $v ) {
        $this->setData( 'name', trim($v) );
        return true;
    }


    public function getHandle() {
        return $this->getData( 'handle' );
    }
    public function setHandle( $v ) {
        $this->setData( 'handle', trim($v) );
        return true;
    }


    public function getUrl() {
        return $this->getData( 'url' );
    }
    public function setUrl( $v ) {
        $this->setData( 'url', trim($v) );
        return true;
    }


    public function getProfilePicture() {
        return $this->getData( 'profile_picture' );
    }
    public function setProfilePicture( $v ) {
        $this->setData( 'profile_picture', trim($v) );
        return true;
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
	}
	public function addReport( $key, $report ) {
		$this->setReport( $key, $report );
		$this->setTotalReport( $this->getTotalReport()+1 );
		return true;
	} // this is not a hole...
	public function deleteReport( $key ) {
		if( !$this->getReport($key) ) {
			return false;
		}
		$t_reports = $this->getReports();
		unset( $t_reports[$key] );
		$this->setReports( $t_reports );
		$this->setTotalReport( $this->getTotalReport()-1 );
		return true;
	}

	public function getReports() {
		return $this->getData( 'reports' );
	}
	private function setReports( $t_reports ) {
		$this->setData( 'reports', $t_reports );
		return true;
	}


	public function getTotalReport() {
		return $this->getData('total_report');
	}
	private function setTotalReport( $v ) {
		$this->setData( 'total_report', (int)$v );
		return true;
	}


	public function getTotalReputation() {
		return $this->getData('total_reputation');
	}
	private function setTotalReputation( $v ) {
		$this->setData( 'total_reputation', (int)$v );
		return true;
	}


	public function getSignal() {
		return $this->getData('signal');
	}
	private function setSignal( $v ) {
		$this->setData( 'signal', $v );
		return true;
	}



	public function getImpact() {
		$i = $this->getData('impact');
		if( $i === false ) {
			$this->setData( 'impact', $this->calculateImpact() );
		}
		return $this->getData('impact');
	}
	private function setImpact( $v ) {
		$this->setData( 'impact', $v );
		return true;
	}
	private function calculateImpact() {
		$n = $i = $impact = 0;
		$reports = $this->getReports();
		foreach( $reports as $r ) {
			if( !$r->getIgnore() ) {
				$i = $r->getImpact();
				if( $i ) {
					$n++;
					$impact += $i;
				}
			}
		}
		return $impact/$n;
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


	public function getLastReport() {
		if( $this->getData('last_report') ) {
			return $this->getReport( $this->getData('last_report') );
		} else {
			return false;
		}
	}
	private function setLastReport( $key ) {
		$this->setData( 'last_report', $key );
		return true;
	}
	public function getLastReportDate() {
		$last_report = $this->getLastReport();
		if( !$last_report ) {
			return false;
		}
		return $last_report->getCreatedAt();
	}


	public function getReportsByMonth( $month ) {
		$n = 0;
		$t_reports = $this->getReports();
        foreach( $t_reports as $r ) {
            if( date('m/Y',$r->getCreatedAt()) == $month ) {
                $n++;
            }
        }
        return $n;
    }


	public function getTotalBounty() {
		return $this->getData('total_bounty');
	}
	private function setTotalBounty( $v ) {
		$this->setData( 'total_bounty', (int)$v );
		return true;
	}


    public function getAverageBounty() {
        return $this->getData( 'average_bounty' );
    }
    public function setAverageBounty( $v ) {
        $this->setData( 'average_bounty', $v );
        return true;
    }


    public function getSmallestBountyId() {
        return $this->getData('smallest_bounty_id');
    }
    public function setSmallestBountyId( $v ) {
        $this->setData( 'smallest_bounty_id', $v );
        return true;
    }
    public function getSmallestBounty() {
		$id = $this->getSmallestBountyId();
		if( !$id || !($r=$this->getReport($id)) ) {
			return false;
		}
		return $r->getTotalBounty();
    }


    public function getHigherBountyId() {
        return $this->getData('higher_bounty_id');
    }
    public function setHigherBountyId( $v ) {
        $this->setData( 'higher_bounty_id', $v );
        return true;
    }
    public function getHigherBounty() {
		$id = $this->getHigherBountyId();
		if( !$id || !($r=$this->getReport($id)) ) {
			return false;
		}
		return $r->getTotalBounty();
    }


    public function getBountiesByMonth( $month ) {
		$n = 0;
		$t_reports = $this->getReports();
        foreach( $t_reports as $r ) {
            if( date('m/Y',$r->getCreatedAt()) == $month ) {
                $n += $r->getTotalBounty();
            }
        }
        return $n;
    }


	public function setUserInfos( $t_user_infos )
	{
		$this->setData( 'id', $t_user_infos['id'] );
		$this->setData( 'url', $t_user_infos['url'] );
		$this->setData( 'name', $t_user_infos['name'] );
		$this->setData( 'handle', $t_user_infos['username'] );
		$this->setData( 'signal', $t_user_infos['signal'] );
		$this->setData( 'profile_picture', $t_user_infos['profile_picture_urls']['small'] );

		return true;
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
			$dir = dirname( $source );
			$this->datas = new stdClass();
			$this->db_file = $source;

			if( !is_dir($dir) ) {
				if( !mkdir($dir,0777,true) ) {
					return false;
				}
			}

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
					$r->setReputations( $report->getReputations() ); // we DON'T want to keep the old reputation, overwrite
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
		$last_report_date = null;
        $smallest_bounty = $smallest_bounty_id = 2147483647;
        $higher_bounty = $higher_bounty_id = 0;
		$total_report = $total_bounty = $total_reputation = 0;

		foreach( $t_reports as $key=>$report )
		{
			$t_date[$key] = $report->getCreatedAt();

			if( is_null($first_report_date) || $report->getCreatedAt()<$first_report_date ) {
				$this->setFirstReport( $key );
				$first_report_date = $report->getCreatedAt();
			}

			if( is_null($last_report_date) || $report->getCreatedAt()>$last_report_date ) {
				$this->setLastReport( $key );
				$last_report_date = $report->getCreatedAt();
			}

			if( !$report->getIgnore() ) {
				$total_report++;
			}

			if( $report->getTotalBounty() < $smallest_bounty && $report->getTotalBounty() != 0 ) {
                $smallest_bounty_id = $key;
                $smallest_bounty = $report->getTotalBounty();
            }
            if( $report->getTotalBounty() > $higher_bounty ) {
                $higher_bounty_id = $key;
                $higher_bounty = $report->getTotalBounty();
            }

			$total_bounty += $report->getTotalBounty();
			$total_reputation += $report->getTotalReputation();
		}

		array_multisort( $t_date, SORT_ASC, $t_reports );

		$this->setReports( $t_reports );
		$this->setTotalReport( $total_report );
		$this->setTotalBounty( $total_bounty );
		$this->setTotalReputation( $total_reputation );
        $this->setSmallestBountyId( $smallest_bounty_id );
        $this->setHigherBountyId( $higher_bounty_id );
		$this->setAverageBounty( (int)($total_bounty/$total_report) );

		$r = file_put_contents( $this->db_file, json_encode($this->datas,JSON_PRETTY_PRINT) );
		if( $r === false ) {
			return false;
		}

		chmod( $this->db_file, 0777 );
		return true;
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
