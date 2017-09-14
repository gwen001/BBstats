<?php

/**
 * I don't believe in license
 * You can do want you want with this program
 * - gwen -
 */

abstract class Platform
{
	protected $t_bugs = []; // array of reports from the platform, untouched
	protected $t_reports = []; // array of reports but only needed informations
	
	
	protected $t_reports_final = []; // array of final reports, objects

	public function getReportsFinal() {
		return $this->t_reports_final;
	}
	public function setReportsFinal( $t_reports ) {
		$this->t_reports_final = $t_reports;
		return true;
	}
	
	
	private $name = '';
	
	public function getName() {
		return $this->name;
	}
	protected function setName( $v ) {
		$this->name = strtolower( trim($v) );
		return true;
	}
	
	
	// ask for login/password/credentials
	abstract public function login();
	
	// connect to the platform
	abstract public function connect();
	
	// grab reports list from the concerned platform
	abstract public function grabReportList( $quantity );
	
	// grab reports list from import file
	abstract protected function grabReportListFromFile( $quantity );
	
	// grab reports from the concerned platform
	abstract public function grabReports( $quantity, $t_reputation );
	
	// grab reports from import file
	abstract public function grabReportsFromFile( $quantity, $t_reputation );
	
	// grab reports from the concerned platform
	abstract protected function grabReport( $report_id );
	
	// grab reports from import file
	abstract protected function grabReportFromFile( $report_id );
	
	// get reputation datas the concerned platform
	abstract public function grabReputation();

	// get reputation datas from import file
	abstract public function grabReputationFromFile();
	
	// extract datas form previously grabbed reports datas
	abstract public function extractReportDatas();

	// return direct link to a report
	abstract public static function getReportLink( $report_id );
}
