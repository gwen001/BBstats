<?php

/**
 * I don't believe in license
 * You can do want you want with this program
 * - gwen -
 */

class Cobalt
{
	public $name = 'Cobalt';
	
	public $username = '';
	public $password = '';
	private $cookie_file;
	
	private $cookies = '';
	private $t_bugs = [];
	private $t_reports = [];
	private $t_reports_final = [];
	
	
	public function getReportsFinal() {
		return $this->t_reports_final;
	}
	public function setReportsFinal( $t_reports ) {
		$this->t_reports_final = $t_reports;
		return true;
	}
	
	
	public function login()
	{
	}
	
	
	public function connect()
	{
	}
	
	
	public function grabReportList( $quantity )
	{
	}
	
	
	public function grabReports( $db, $quantity, $update, $overwrite )
	{
	}
	
	
	private function grabReport( $report_id )
	{
	}
	
	
	public function extractDatas()
	{
	}
	
	
	public static function getReportLink( $report_id ) {
		return 'https://app.cobalt.io/godaddy/godaddy-beta/reports/'.$report_id;
	}
}
