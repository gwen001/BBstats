<?php

class Cobalt extends Platform
{
	public function __construct() {
		$this->setName( 'cobalt' );
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


	protected function grabReportListFromFile( $quantity )
	{
	}


	public function grabReports( $quantity, $t_reputation )
	{
	}


	public function grabReportsFromFile( $quantity, $t_reputation )
	{
	}


	protected function grabReport( $report_id )
	{
	}


	protected function grabReportFromFile( $report_id )
	{
	}


	public function extractReportDatas()
	{
	}


	public static function getReportLink( $report_id ) {
		return 'https://app.cobalt.io/godaddy/godaddy-beta/reports/'.$report_id;
	}


	public function grabReputation()
	{
	}


	public function grabReputationFromFile()
	{
	}
}
