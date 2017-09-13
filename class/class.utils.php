<?php

/**
 * I don't believe in license
 * You can do want you want with this program
 * - gwen -
 */

class Utils
{
	const TMP_DIR = '/tmp/';
	const T_SHELL_COLORS = array(
		'nc' => '0',
		'black' => '0;30',
		'red' => '0;31',
		'green' => '0;32',
		'orange' => '0;33',
		'blue' => '0;34',
		'purple' => '0;35',
		'cyan' => '0;36',
		'light_grey' => '0;37',
		'dark_grey' => '1;30',
		'light_red' => '1;31',
		'light_green' => '1;32',
		'yellow' => '1;33',
		'light_blue' => '1;34',
		'light_purple' => '1;35',
		'light_cyan' => '1;36',
		'white' => '1;37',
	);


	public static function help( $error='' )
	{
		$help = self::fromReadme( 'help' );
		echo $help."\n";
		
		if( $error ) {
			echo "\nError: ".$error."!\n";
		}

		exit();
	}
	
	
	public static function fromReadme( $tag )
	{
		$readme = APP_PATH.'/README.md';
		$content = file_get_contents( $readme );
		$open_tag = '<!-- '.$tag.' -->';
		$close_tag = '<!-- /'.$tag.' -->';
		$r = '#'.$open_tag.'(.*)'.$close_tag.'#s';
		$m = preg_match_all( $r, $content, $match );

		if( $m ) {
			return trim($match[1][0]);
		} else {
			return "Nothing found!\n";
		}
	}


	public static function isIp( $str ) {
		return filter_var( $str, FILTER_VALIDATE_IP );
	}


	public static function isEmail( $str )
	{
		return filter_var( $str, FILTER_VALIDATE_EMAIL );
	}


	public static function _print( $str, $color )
	{
		echo "\033[".self::T_SHELL_COLORS[$color]."m".$str."\033[0m";
	}
	public static function _println( $str, $color )
	{
		self::_print( $str, $color );
		echo "\n";
	}


	public static function _array_search( $array, $search, $ignore_case=true )
	{
		if( $ignore_case ) {
			$f = 'stristr';
		} else {
			$f = 'strstr';
		}

		if( !is_array($search) ) {
			$search = array( $search );
		}

		foreach( $array as $k=>$v ) {
			foreach( $search as $str ) {
				if( $f($v, $str) ) {
					return $k;
				}
			}
		}

		return false;
	}
	
	
	public static function isDomain( $str )
	{
		$str = strtolower( $str );

		if( preg_match('/[^0-9a-z_\-\.]/',$str) || preg_match('/[^0-9a-z]/',$str[0]) || preg_match('/[^a-z]/',$str[strlen($str)-1]) || substr_count($str,'.')>2 || substr_count($str,'.')<=0 ) {
			return false;
		} else {
			return true;
		}
	}


	public static function isSubdomain( $str )
	{
		$str = strtolower( $str );

		if( preg_match('/[^0-9a-z_\-\.]/',$str) || preg_match('/[^0-9a-z]/',$str[0]) || preg_match('/[^a-z]/',$str[strlen($str)-1]) || substr_count($str,'.')<2 ) {
			return false;
		} else {
			return true;
		}
	}

	
	public static function extractDomain( $host )
	{
		$tmp = explode( '.', $host );
		$cnt = count( $tmp );

		$domain = $tmp[$cnt-1];

		for( $i=$cnt-2 ; $i>=0 ; $i-- ) {
			$domain = $tmp[$i].'.'.$domain;
			if( strlen($tmp[$i]) > 3 ) {
				break;
			}
		}

		return $domain;
	}
	
	
	public static function cleanOutput( $str )
	{
		$str = preg_replace( '#\[[0-9;]{1,4}m#', '', $str );

		return $str;
	}
	

	public static function _exec( $cmd )
	{
		$output = '';
	
		while( @ob_end_flush() );
		
		$proc = popen( $cmd, 'r' );
		while( !feof($proc) ) {
			$line = fread( $proc, 4096 );
			echo $line;
			$output .= $line;
			@flush();
		}
		
		return $output;
	}
	
	
	public function printDebug( $txt ) {
		self::_println( '[*] '.$txt, 'white' );
	}
	public function printInfo( $txt ) {
		self::_println( '[*] '.$txt, 'white' );
	}
	public function printSuccess( $txt ) {
		self::_println( '[+] '.$txt, 'green' );
	}
	public function printError( $txt ) {
		self::_println( '[-] '.$txt, 'red' );
	}
	
	
	public static function array2object( $array, $class )
	{
		$object = new $class();
		
		foreach( $array as $k=>$v ) {
			$k = self::camelize( $k );
			$method = 'set'.$k;
			if( is_callable([$object,$method]) ) {
				$object->$method( $v );
			}
		}
		
		return $object;
	}
	
	
	public static function camelize( $str )
	{
		$str = strtolower( $str );
		$tmp = explode( '_', $str );
		$tmp = array_map( 'ucfirst', $tmp );
		$str = implode( '', $tmp );
		
		return $str;
	}
	
	
	public static function demonize( $t_reports )
	{
		$t_random_domain = [ 'test.com', 'example.com', '', 'salesforce', 'uber', 'pornhub', 'yelp', 'imgur', 'github' ];
		$n_random_domain = count($t_random_domain) - 1;
		$t_random_program = [ 'google', 'facebook', 'yahoo', 'salesforce', 'uber', 'pornhub', 'yelp', 'imgur', 'github' ];
		$n_random_program = count($t_random_program) - 1;
		
		foreach( $t_reports as $r )
		{
			if( rand(0,100) <= 70 ) {
				$r->setPlatform( 'hackerone' );
			} else {
				$r->setPlatform( 'bugcrowd' );
			}
			
			$program = $r->getProgram();
			$r->setProgram( $t_random_program[rand(0,$n_random_program)] );

			$title = $r->getTitle();
			$title = preg_replace( '#'.$program.'#i', $r->getProgram(), $title );
			$title = preg_replace( '#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#', '__IP__', $title );
			//$title = preg_replace( '##' );
			$title = preg_replace( '#__IP__#', '192.168.'.rand(0,10).'.'.rand(1,253), $title );
			$r->setTitle( $title );
			
			$t_bounties = $r->getBounties();
			foreach( $t_bounties as $b ) {
				$rating = $r->getRating() ? $r->getRating() : 6;
				$b->amount = rand( (6-$rating)*50, (6-$rating)*700 );
			}
			$r->setBounties( $t_bounties );
		}
		
		return $t_reports;
	}
}
