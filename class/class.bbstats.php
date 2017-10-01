<?php

/**
 * I don't believe in license
 * You can do want you want with this program
 * - gwen -
 */

class BBstats
{
	const SHORT_OPTIONS = 'a:ef:hn:p:rrrttt';
	const LONG_OPTIONS = ['demo'];
	const TOP_LIMIT = 10;

	
	private static $_instance = null;

	private function __construct() {
	}
	public static function getInstance() {
		if( is_null(self::$_instance) ) {
			$c = __CLASS__;
			self::$_instance = new $c();
		}
		return self::$_instance;
	}
	
	
	private $database = null;
	
	public function getDatabase() {
		return $this->database;
	}
	public function setDatabase( $v ) {
		$this->database = $v;
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
	
	
	private $source_file = null;
	private $is_import = false;
	
	public function isImport() {
		return $this->is_import;
	}
	public function getSourceFile() {
		return $this->source_file;
	}
	public function setSourceFile( $v ) {
		$f = trim( $v );
		if( !is_file($f) ) {
			return false;
		}
		$this->is_import = true;
		$this->source_file = $f;
		return true;
	}
	
	
	private $action = 'n'; // new
	
	public function getAction() {
		return $this->action;
	}
	public function setAction( $v )
	{
		$action = strtolower( trim($v) );
		switch( $action ) {
			case 'n':
				break;
			case 'o':
				break;
			case 'r':
				break;
			case 'u':
				$this->allowUpdate();
				$this->disallowOverwrite();
				break;
			default:
				return false;
		}
		$this->action = $v;
		return true;
	}
	
	
	private $allow_update = false;
	
	public function updateAllowed() {
		return $this->allow_update;
	}
	private function allowUpdate() {
		$this->allow_update = true;
	}
	
	private $allow_overwrite = true;
	
	public function overwriteAllowed() {
		return $this->allow_overwrite;
	}
	private function disallowOverwrite() {
		$this->allow_overwrite = false;
	}

	
	private $demo = false;
	
	public function isDemo() {
		return $this->demo;
	}
	public function enableDemoMode() {
		$this->demo = true;
	}
	
	
	private $quantity = 2147483647;
	
	public function getQuantity() {
		return $this->quantity;
	}
	public function setQuantity( $v ) {
		$v = (int)$v;
		if( $v > 0 ) {
			$this->quantity = $v;
		}
		return true;
	}
	
	
	private $autotag = 0;
	
	public function getAutoTagMode() {
		return $this->autotag;
	}
	public function setAutoTagMode( $v ) {
		$this->autotag = (int)$v;
		return true;
	}
	
	
	private $autorate = 0;
	
	public function getAutoRateMode() {
		return $this->autorate;
	}
	public function setAutoRateMode( $v ) {
		$this->autorate = (int)$v;
		return true;
	}
	
	
	private $reputation = false;
	
	public function isReputation() {
		return $this->reputation;
	}
	public function enableReputation() {
		$this->reputation = true;
	}
	
}
