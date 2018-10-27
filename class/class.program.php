<?php

/**
 * I don't believe in license
 * You can do want you want with this program
 * - gwen -
 */

class program
{
	private static $_instance = null;

	
	public function __construct() {
	}

	
	public static function getInstance() {
		if( is_null(self::$_instance) ) {
			$c = __CLASS__;
			self::$_instance = new $c();
		}
		return self::$_instance;
	}

    public function setInfos( $infos )
    {
        $o_infos = json_decode( $infos );
        
        $this->setId( $o_infos->id );
        $this->setName( $o_infos->name );
        $this->setHandle( $o_infos->handle );
        $this->setUrl( $o_infos->url );
        $this->setProfilePicture( $o_infos->profile_picture_urls->small );

        return true;
    }


    private $platform = '';

    public function getPlatform() {
        return $this->platform;
    }
    public function setPlatform( $v ) {
        $this->platform = trim( $v );
        return true;
    }

    private $id = '';

    public function getId() {
        return $this->id;
    }
    public function setId( $v ) {
        $this->id = $v;
        return true;
    }


    private $name = '';

    public function getName() {
        return $this->name;
    }
    public function setName( $v ) {
        $this->name = trim( $v );
        return true;
    }


    private $handle = '';
    private $db_file = null;

    public function getHandle() {
        return $this->handle;
    }
    public function setHandle( $v ) {
        $this->handle = trim( $v );
        $this->db_file = self::getBackupFile( $this->handle );
        return true;
    }


    private $url = '';

    public function getUrl() {
        return $this->url;
    }
    public function setUrl( $v ) {
        $this->url = trim( $v );
        return true;
    }


    private $profile_picture = '';

    public function getProfilePicture() {
        return $this->profile_picture;
    }
    public function setProfilePicture( $v ) {
        $this->profile_picture = trim( $v );
        return true;
    }


    private $total_report = '';

    public function getTotalReport() {
        return $this->total_report;
    }
    public function setTotalReport( $v ) {
        $this->total_report = (int)$v;
        return true;
    }


    private $total_bounty = '';

    public function getTotalBounty() {
        return $this->total_bounty;
    }
    public function setTotalBounty( $v ) {
        $this->total_bounty = (int)$v;
        return true;
    }


    private $average_bounty = '';

    public function getAverageBounty() {
        return $this->average_bounty;
    }
    public function setAverageBounty( $v ) {
        $this->average_bounty = $v;
        return true;
    }


    private $smallest_bounty_id = '';

    public function getSmallestBountyId() {
        return $this->smallest_bounty_id;
    }
    public function setSmallestBountyId( $v ) {
        $this->smallest_bounty_id = $v;
        return true;
    }
    public function getSmallestBounty() {
		$id = $this->getSmallestBountyId();
		if( !$id || !($r=$this->getReport($id)) ) {
			return false;
		}
		return $r->getTotalBounty();
    }


    private $higher_bounty_id = '';

    public function getHigherBountyId() {
        return $this->higher_bounty_id;
    }
    public function setHigherBountyId( $v ) {
        $this->higher_bounty_id = $v;
        return true;
    }
    public function getHigherBounty() {
		$id = $this->getHigherBountyId();
		if( !$id || !($r=$this->getReport($id)) ) {
			return false;
		}
		return $r->getTotalBounty();
    }


    private $bounty_range = null;

    public function getBountyRange() {
        return $this->bounty_range;
    }
    public function setBountyRange( $v ) {
        $this->bounty_range = $v;
        return true;
    }


    public function getBountiesByMonth( $month ) {
        $n = 0;
        foreach( $this->reports as $r ) {
            if( date('m/Y',$r->getCreatedAt()) == $month ) {
                $n += $r->getTotalBounty();
            }
        }
        return $n;
    }


    private $first_report_id = '';

    public function getFirstReportId() {
        return $this->first_report_id;
    }
    public function setFirstReportId( $v ) {
        $this->first_report_id = $v;
        return true;
    }
	public function getFirstReportDate() {
        $id = $this->getFirstReportId();
		if( !$id || !($r=$this->getReport($id)) ) {
			return false;
		}
		return $r->getCreatedAt();
	}
	

    private $last_report_id = '';

    public function getLastReportId() {
        return $this->last_report_id;
    }
    public function setLastReportId( $v ) {
        $this->last_report_id = $v;
        return true;
    }
	public function getLastReportDate() {
		$id = $this->getLastReportId();
		if( !$id || !($r=$this->getReport($id)) ) {
			return false;
		}
		return $r->getCreatedAt();
	}


    public static function getBackupFile( $handle ) {
        return DATABASE_PATH.'/'.$handle.'.json';
    }


    private $reports = null;

    public function getReport( $key ) {
        if( isset($this->reports[$key]) ) {
            return $this->reports[$key];
        }
        return  false;
    }
    public function getReports() {
        return  $this->reports;
    }
    public function getReportsByMonth( $month ) {
        $n = 0;
        foreach( $this->reports as $r ) {
            if( date('m/Y',$r->getCreatedAt()) == $month ) {
                $n++;
            }
        }
        return $n;
    }
    public function setReports( $v ) {
        $this->reports = $v;
        return  true;
    }

    
    private $hacktivity = null;

    public function getHacktivity() {
        return  $this->hacktivity;
    }
    public function computeHacktivity( $t_reports )
    {
        $t_hacktivity = [];

        foreach( $t_reports as $r )
        {
            $id = is_numeric($r['id']) ? $r['id'] : md5(uniqid(true));

            $tmp = new Report();
            $tmp->setId( $id );
            $tmp->setReporter( $r['reporter']['username'] );
            $tmp->setPlatform( $this->platform );
            $tmp->setTitle( isset($r['title']) ? $r['title'] : '' );
            $ttt = explode( 'T', $r['latest_disclosable_activity_at'] );
            $tmp->setCreatedAt( strtotime($ttt[0]) );

            if( $r['bounty_disclosed'] && isset($r['total_awarded_bounty_amount']) ) {
                $r['total_awarded_bounty_amount'] = (int)$r['total_awarded_bounty_amount'];
            } else {
                $r['total_awarded_bounty_amount'] = 0;
            }
            $tmp->addBounty( $tmp->getCreatedAt(), $r['total_awarded_bounty_amount'] ) ;

            $t_hacktivity[ $id ] = $tmp;
        }

        $this->hacktivity = $t_hacktivity;

        return count($this->hacktivity);
    }
    


    public static function load( $handle )
    {
        $handle = trim( $handle );
        $db_file = self::getBackupFile( $handle );
        if( is_null($db_file) || !is_file($db_file) ) {
            return false;
        }
                    
        $datas = file_get_contents( $db_file );
		if( $datas === false ) {
			return false;
        }
        
        if( strlen($datas) )
        {
            $program = json_decode( $datas );
            $program = Utils::array2object( $program, 'Program' );
            $program->bounty_range = (array)$program->bounty_range;

            $program->hacktivity = (array)$program->hacktivity;
            foreach( $program->hacktivity as $k=>$v ) {
                $program->hacktivity[ $k ] = Utils::array2object( $v, 'Report' );
            }

            $program->reports = (array)$program->reports;
            foreach( $program->reports as $k=>$v ) {
                $program->reports[ $k ] = Utils::array2object( $v, 'Report' );
            }
        }

		return $program;
    }


    private function computeDatas()
    {
        $total_bounty = 0;
        $first_report = $first_report_id = 2147483647;
        $last_report = $last_report_id = 0;
        $smallest_bounty = $smallest_bounty_id = 2147483647;
        $higher_bounty = $higher_bounty_id = 0;

        foreach( $this->reports as $key=>$r )
        {
            if( $r->getCreatedAt() < $first_report ) {
                $first_report_id = $key;
                $first_report = $r->getCreatedAt();
            }
            if( $r->getCreatedAt() > $last_report ) {
                $last_report_id = $key;
                $last_report = $r->getCreatedAt();
            }

            $total_bounty += $r->getTotalBounty();

            if( $r->getTotalBounty() < $smallest_bounty && $r->getTotalBounty() != 0 ) {
                $smallest_bounty_id = $key;
                $smallest_bounty = $r->getTotalBounty();
            }
            if( $r->getTotalBounty() > $higher_bounty ) {
                $higher_bounty_id = $key;
                $higher_bounty = $r->getTotalBounty();
            }
        }

        $this->setTotalReport( count($this->reports) );
        $this->setTotalBounty( $total_bounty );
        $this->setFirstReportId( $first_report_id );
        $this->setLastReportId( $last_report_id );
        $this->setSmallestBountyId( $smallest_bounty_id );
        $this->setHigherBountyId( $higher_bounty_id );
        $this->setAverageBounty( (int)($total_bounty/$this->getTotalReport()) );
        
        $range = $higher_bounty / 4;
        $average_bounty = $total_bounty / $this->getTotalReport();
        $average_bounty2 = $average_bounty / 2;
        $average_bounty3 = $average_bounty / 3;
        
        $t_ranges = [
			'none'     => 0,
			'low'      => 1,
			'medium'   => (int)($average_bounty - $average_bounty2),
			'high'     => (int)($average_bounty + $average_bounty2),
			'critical' => (int)(($average_bounty + $average_bounty2) * 2),
        ];
        //var_dump( $t_ranges );

        foreach( $this->reports as $r )
        {
            $bounty = $r->getTotalBounty();

            foreach( $t_ranges as $k=>$v ) {
                if( $bounty < $v ) {    
                    break;
                }
                $severity = $k;
            }

            $r->setSeverity( $severity );
        }

        $this->setBountyRange( $t_ranges );
    }


    public function save()
    {
        $this->computeDatas();
        $datas = json_encode( get_object_vars($this), JSON_PRETTY_PRINT );

        $r = file_put_contents( $this->db_file, $datas );
		if( $r === false ) {
			return false;
		}
		
		chmod( $this->db_file, 0777 );
		return true;
    }
}

