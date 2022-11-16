<?php

class Report
{
	public const T_STATE = ['new','triaged','resolved','informative','duplicate','not-applicable'];

	public $id = 0;
	public $platform = '';
	public $reporter = '';
	public $created_at = '';
	public $title = '';
	public $program = '';
	public $severity = '';
	public $bounties = [];
	public $reputations = [];
	public $state = '';
	public $rating = 0;
	public $tags = [];
	public $manual = 0;
	public $ignore = 0;
	public $first_response_date = null;
	public $first_bounty_date = null;
	public $triage_date = null;
	public $resolution_date = null;


	public function getId() {
		return $this->id;
	}
	public function setId( $v ) {
		$this->id = $v;
		return true;
	}


	public function getIgnore() {
		return $this->ignore;
	}
	public function setIgnore( $v ) {
		$this->ignore = (int)$v;
		return true;
	}
	public function ignore() {
		$this->setIgnore( 1 );
		return true;
	}
	public function unignore() {
		$this->setIgnore( 0 );
		return true;
	}


	public function getManual() {
		return $this->manual;
	}
	public function setManual( $v ) {
		$this->manual = (int)$v;
		return true;
	}


	public function getPlatform() {
		return $this->platform;
	}
	public function setPlatform( $v ) {
		$this->platform = trim( $v );
		return true;
	}


	public function getReporter() {
		return $this->reporter;
	}
	public function setReporter( $v ) {
		$this->reporter = trim( $v );
		return true;
	}


	public function getCreatedAt() {
		return $this->created_at;
	}
	public function setCreatedAt( $v ) {
		$this->created_at = $v;
		return true;
	}


	public function getTitle() {
		return $this->title;
	}
	public function setTitle( $v ) {
		$this->title = trim( $v );
		return true;
	}


	public function getProgram() {
		return $this->program;
	}
	public function setProgram( $v ) {
		$this->program = trim( $v );
		return true;
	}


    public function getSeverity() {
        return $this->severity;
    }
    public function setSeverity( $v ) {
        $this->severity = trim( $v );
        return true;
    }


	public function addReputation( $created_at, $points ) {
		$reputation = new stdClass();
		$reputation->created_at = $created_at;
		$reputation->points = $points;
		return $this->reputations[] = $reputation;
	}
	public function getReputations() {
		return $this->reputations;
	}
	public function setReputations( $v ) {
		$this->reputations = $v;
		return true;
	}
	public function resetReputation() {
		$this->reputations = [];
		return true;
	}

	public function getTotalReputation() {
		$total = 0;
		foreach( $this->reputations as $r ) {
			$total += $r->points;
		}
		return $total;
	}


	public function addBounty( $created_at, $amount ) {
		$bounty = new stdClass();
		$bounty->created_at = $created_at;
		$bounty->amount = $amount;
		return $this->bounties[] = $bounty;
	}
	public function getBounties() {
		return $this->bounties;
	}
	public function setBounties( $v ) {
		$this->bounties = $v;
		return true;
	}


	public function getTotalBounty() {
		$total = 0;
		foreach( $this->bounties as $b ) {
			$total += $b->amount;
		}
		return $total;
	}
	public function setManualBounty( $v ) {
		$bounty = new stdClass();
		$bounty->created_at = $this->getCreatedAt();
		$bounty->amount = $v;
		$this->setBounties( [$bounty] );
		return true;
	}


	public function getState() {
		return $this->state;
	}
	public function setState( $v ) {
		$this->state = trim( $v );
		return true;
	}


	public function getRating() {
		return $this->rating;
	}
	public function setRating( $v ) {
		$this->rating = (int)$v;
		return true;
	}


	public function getImpact() {
		$i = ($this->rating) ? (6-$this->rating) : 0;
		return $i;
	}


	public function getTags( $str=false ) {
		if( $str ) {
			return implode(', ',$this->tags);
		} else {
			return $this->tags;
		}
	}
	public function setTags( $v ) {
		$this->tags = array_unique( array_map('trim',$v), SORT_STRING );
		sort( $this->tags );
		return true;
	}
	public function addTag( $v ) {
		$v = trim( $v );
		if( $v == '' ) {
			return false;
		}
		$t_tags = $this->getTags();
		if( !in_array($v,$t_tags) ) {
			$t_tags[] = $v;
			$this->setTags( $t_tags );
		}
		return true;
	}


	public function getLink() {
		$class = $this->getPlatform();
		if( is_callable([$class,'getReportLink']) ) {
			return $class::getReportLink( $this->id );
		} else {
			return false;
		}
	}


	public function getFirstResponseDate() {
		return $this->first_response_date;
	}
	public function setFirstResponseDate( $v ) {
		$this->first_response_date = $v;
		return true;
	}
	public function getFirstResponseTime() {
		$scd = Utils::datetimeDiff( $this->getCreatedAt(), $this->getFirstResponseDate() )->total_sec / 3600 / 24;
		return $scd;
	}


	public function getFirstBountyDate() {
		return $this->first_bounty_date;
	}
	public function setFirstBountyDate( $v ) {
		$this->first_bounty_date = $v;
		return true;
	}
	public function getFirstBountyTime() {
		$scd = Utils::datetimeDiff( $this->getCreatedAt(), $this->getFirstBountyDate() )->total_sec / 3600 / 24;
		return $scd;
	}


	public function getTriageDate() {
		return $this->triage_date;
	}
	public function setTriageDate( $v ) {
		$this->triage_date = $v;
		return true;
	}
	public function getTriageTime() {
		$scd = Utils::datetimeDiff( $this->getCreatedAt(), $this->getTriageDate() )->total_sec / 3600 / 24;
		return $scd;
	}


	public function getResolutionDate() {
		return $this->resolution_date;
	}
	public function setResolutionDate( $v ) {
		$this->resolution_date = $v;
		return true;
	}
	public function getResolutionTime() {
		$scd = Utils::datetimeDiff( $this->getCreatedAt(), $this->getResolutionDate() )->total_sec / 3600 / 24;
		return $scd;
	}


	public static function generateKey( $platform, $program, $report_id )
	{
		return md5( $platform.'.'.$program.'.'.$report_id );
	}


	public static function massAutoTag( $t_reports )
	{
		foreach( $t_reports as $key=>$report ) {
			$report->autoTag();
		}

		return $t_reports;
	}


	public function autoTag()
	{
		$t_tags = self::guessTag( $this->getTitle() );
		$this->setTags( $t_tags );

		return count($t_tags);
	}


	public static function guessTag( $title )
	{
		$t_guess = [];

		foreach( AUTO_RATE_TAG as $rating=>$t_tags ) {
			foreach( $t_tags as $tag=>$t_terms ) {
				foreach( $t_terms['tag_terms'] as $tterm ) {
					if( preg_match('#'.$tterm.'#i',$title) ) {
						$t_guess[] = $tag;
						break;
					}
				}
			}
		}

		$t_guess = array_unique($t_guess,SORT_STRING);
		sort( $t_guess );

		return $t_guess;
	}


	public static function massAutoRate( $t_reports )
	{
		foreach( $t_reports as $key=>$report ) {
			$report->autoRate();
		}

		return $t_reports;
	}


	public function autoRate()
	{
		$rating = self::guessRate( $this->getTitle() );
		$this->setRating( $rating );

		return $rating;
	}


	public static function guessRate( $title )
	{
		foreach( AUTO_RATE_TAG as $rating=>$t_tags ) {
			foreach( $t_tags as $tag=>$t_terms ) {
				foreach( $t_terms['tag_terms'] as $tterm ) {
					if( preg_match('#'.$tterm.'#i',$title) ) {
						$guess = $rating;
						if( isset($t_terms['rate_terms']) ) {
							foreach( $t_terms['rate_terms'] as $rterm=>$point ) {
								if( preg_match('#'.$rterm.'#i',$title) ) {
									$guess += $point;
								}
							}
						}
						return $guess;
					}
				}
			}
		}

		return 0;
	}
}
