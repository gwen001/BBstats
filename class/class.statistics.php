<?php

/**
 * I don't believe in license
 * You can do want you want with this program
 * - gwen -
 */

class Statistics
{
	public static function top_program_html( $db )
	{
		$limit = BBstats::TOP_LIMIT;
		$t_top = self::top_program( $db );

		ob_start();
		echo '<table class="table">
			<thead>
				<tr><th colspan="100">by report ('.$t_top['t_total']['n_report'].')</th></tr>
			</thead>
			<tbody>';
				for( $i=1; $i<=$limit && list($program,$data)=each($t_top['t_n_report']) ; $i++ ) {
					echo '<tr class="top_'.$i.'">
						<td>'.$i.'</td>
						<td><span class="search-term">'.ucwords($program).'</span></td>
						<td class="text-right">'.$data['n_report'].'</td>
						<td class="text-right">'.$data['n_report_p'].' %</td>
					</tr>';
				}
		echo '</tbody>
		</table>';
		$n_report = ob_get_contents();
		ob_end_clean();
		
		ob_start();
		echo '<table class="table">
			<thead>
				<tr>
					<th colspan="100">by bounty ('.$t_top['t_total']['bounty'].' $)</th>
				</tr>
			</thead>
			<tbody>';
				for( $i=1; $i<=$limit && list($program,$data)=each($t_top['t_bounty']) ; $i++ ) {
					echo '<tr class="top_'.$i.'">
						<td>'.$i.'</td>
						<td><span class="search-term">'.ucwords($program).'</span></td>
						<td class="text-right">'.$data['bounty'].' $</td>
						<td class="text-right">'.$data['bounty_p'].' %</td>
					</tr>';
				}
		echo '</tbody>
		</table>';
		$bounty = ob_get_contents();
		ob_end_clean();
		
		ob_start();
		echo '<table class="table">
			<thead>
				<tr>
					<th colspan="100">by reputation ('.$t_top['t_total']['reputation'].')</th>
				</tr>
			</thead>
			<tbody>';
				for( $i=1; $i<=$limit && list($program,$data)=each($t_top['t_reputation']) ; $i++ ) {
					echo '<tr class="top_'.$i.'">
						<td>'.$i.'</td>
						<td><span class="search-term">'.ucwords($program).'</span></td>
						<td class="text-right">'.$data['reputation'].'</td>
						<td class="text-right">'.$data['reputation_p'].' %</td>
					</tr>';
				}
		echo '</tbody>
		</table>';
		$reputation = ob_get_contents();
		ob_end_clean();
		
		return ['n_report'=>$n_report, 'bounty'=>$bounty, 'reputation'=>$reputation];
	}
	
	
	public static function top_program( $db )
	{
		$t_programs = [];
		
		foreach( $db->getReports() as $key=>$report )
		{
			$program = $report->getProgram();
			
			if( !isset($t_programs[$program]) ) {
				$t_programs[$program] = [ 'n_report'=>0, 'bounty'=>0, 'reputation'=>0 ];
			}
			
			$t_programs[$program]['n_report']++;
			$t_programs[$program]['bounty'] += $report->getTotalBounty();
			$t_programs[$program]['reputation'] += $report->getTotalReputation();
		}
		
		$t_total = [];
		$t_total['n_report'] = $db->getTotalReport();
		$t_total['bounty'] = $db->getTotalBounty();
		$t_total['reputation'] = $db->getTotalReputation();
		
		foreach( $t_programs as $program=>&$data ) {
			$data['n_report_p'] = sprintf( '%.01f', $data['n_report']*100 / $t_total['n_report'] );
			$data['bounty_p'] = sprintf( '%.01f', $data['bounty']*100 / $t_total['bounty'] );
			$data['reputation_p'] = @sprintf( '%.01f', $data['reputation']*100 / $t_total['reputation'] );
		}
		
		array_multisort( array_column($t_programs,'n_report'), SORT_DESC, SORT_NUMERIC, $t_programs );
		$t_n_report = $t_programs;
		
		array_multisort( array_column($t_programs,'bounty'), SORT_DESC, SORT_NUMERIC, $t_programs );
		$t_bounty = $t_programs;
		
		array_multisort( array_column($t_programs,'reputation'), SORT_DESC, SORT_NUMERIC, $t_programs );
		$t_reputation = $t_programs;
		
		$t_top = [
			't_total' => $t_total,
			't_n_report' => $t_n_report,
			't_bounty' => $t_bounty,
			't_reputation' => $t_reputation,
		];
		
		return $t_top;
	}
	
	
	public static function top_tags_html( $db )
	{
		$limit = BBstats::TOP_LIMIT;
		$t_top = self::top_tags( $db );

		ob_start();
		echo '<table class="table">
			<thead>
				<tr>
					<th colspan="100">by report ('.$t_top['t_total']['n_report'].')</th>
				</tr>
			</thead>
			<tbody>';
				for( $i=1; $i<=$limit && list($tag,$data)=each($t_top['t_n_report']) ; $i++ ) {
					echo '<tr class="top_'.$i.'">
						<td>'.$i.'</td>
						<td><span class="search-term">'.ucwords($tag).'</span></td>
						<td class="text-right">'.$data['n_report'].'</td>
						<td class="text-right">'.$data['n_report_p'].' %</td>
					</tr>';
				}
		echo '</tbody>
		</table>';
		$n_report = ob_get_contents();
		ob_end_clean();
		
		ob_start();
		echo '<table class="table">
			<thead>
				<tr>
					<th colspan="100">by bounty ('.$t_top['t_total']['bounty'].' $)</th>
				</tr>
			</thead>
			<tbody>';
				for( $i=1; $i<=$limit && list($tag,$data)=each($t_top['t_bounty']) ; $i++ ) {
					echo '<tr class="top_'.$i.'">
						<td>'.$i.'</td>
						<td><span class="search-term">'.ucwords($tag).'</span></td>
						<td class="text-right">'.$data['bounty'].' $</td>
						<td class="text-right">'.$data['bounty_p'].' %</td>
					</tr>';
				}
		echo '</tbody>
		</table>';
		$bounty = ob_get_contents();
		ob_end_clean();
		
		ob_start();
		echo '<table class="table">
			<thead>
				<tr>
					<th colspan="100">by reputation ('.$t_top['t_total']['reputation'].')</th>
				</tr>
			</thead>
			<tbody>';
				for( $i=1; $i<=$limit && list($tag,$data)=each($t_top['t_reputation']) ; $i++ ) {
					echo '<tr class="top_'.$i.'">
						<td>'.$i.'</td>
						<td><span class="search-term">'.ucwords($tag).'</span></td>
						<td class="text-right">'.$data['reputation'].'</td>
						<td class="text-right">'.$data['reputation_p'].' %</td>
					</tr>';
				}
		echo '</tbody>
		</table>';
		$reputation = ob_get_contents();
		ob_end_clean();
		
		return ['n_report'=>$n_report, 'bounty'=>$bounty, 'reputation'=>$reputation];
	}
	
	
	public static function top_tags( $db )
	{
		$t_tags = [];
		
		foreach( $db->getReports() as $key=>$report )
		{
			$r_tags = $report->getTags();
			
			foreach( $r_tags as $tag ) {
				if( !isset($t_tags[$tag]) ) {
					$t_tags[$tag] = [ 'n_report'=>0, 'bounty'=>0, 'reputation'=>0 ];
				}
				
				$t_tags[$tag]['n_report']++;
				$t_tags[$tag]['bounty'] += $report->getTotalBounty();
				$t_tags[$tag]['reputation'] += $report->getTotalReputation();
			}
		}
		
		$t_total = [];
		$t_total['n_report'] = $db->getTotalReport();
		$t_total['bounty'] = $db->getTotalBounty();
		$t_total['reputation'] = $db->getTotalReputation();
		
		foreach( $t_tags as $tag=>&$data ) {
			$data['n_report_p'] = sprintf( '%.01f', $data['n_report']*100 / $t_total['n_report'] );
			$data['bounty_p'] = sprintf( '%.01f', $data['bounty']*100 / $t_total['bounty'] );
			$data['reputation_p'] = @sprintf( '%.01f', $data['reputation']*100 / $t_total['reputation'] );
		}
		
		array_multisort( array_column($t_tags,'n_report'), SORT_DESC, SORT_NUMERIC, $t_tags );
		$t_n_report = $t_tags;
		
		array_multisort( array_column($t_tags,'bounty'), SORT_DESC, SORT_NUMERIC, $t_tags );
		$t_bounty = $t_tags;
		
		array_multisort( array_column($t_tags,'reputation'), SORT_DESC, SORT_NUMERIC, $t_tags );
		$t_reputation = $t_tags;
		
		$t_top = [
			't_total' => $t_total,
			't_n_report' => $t_n_report,
			't_bounty' => $t_bounty,
			't_reputation' => $t_reputation,
		];
		
		return $t_top;
	}
	
	
	public static function reports_platform_pie( $db )
	{
		$t_program = [];
		$t_average = [];
		
		foreach( $db->getReports() as $report )
		{
			if( !isset($t_platform[$report->getPlatform()]) ) {
				$t_platform[ $report->getPlatform() ] = 0;
			}
			$t_platform[ $report->getPlatform() ]++;
		}
		
		arsort( $t_platform );
		$total = $db->getTotalReport();
		
		$t_platforms = array_slice( array_keys($t_platform), 0, 5 );
		$t_platforms = array_merge( ['other'], $t_platforms );
		$t_platforms = array_map( 'ucfirst', $t_platforms );
		$t_values = array_slice( array_values($t_platform), 0, 5 );
		$others = $total - array_sum($t_values);
		$t_values = array_merge( [$others], $t_values );
		//var_dump( $t_platforms );
		//var_dump( $t_values );
		
		$t_return = [
			'total' => $total,
			'platforms' => $t_platforms,
			'values' => $t_values,
		];
		
		return json_encode( $t_return );
	}
	
	
	public static function reports_program_pie( $db )
	{
		$t_program = [];
		$t_total = [];
		$total = 0;
		
		foreach( $db->getReports() as $report )
		{
			if( !isset($t_program[$report->getProgram()]) ) {
				$t_program[ $report->getProgram() ] = 0;
			}
			$t_program[ $report->getProgram() ]++;
			$total++;
		}
		
		arsort( $t_program );
		//var_dump( $total );
		
		$t_programs = array_slice( array_keys($t_program), 0, 5 );
		$t_programs = array_merge( ['other'], $t_programs );
		$t_programs = array_map( 'ucfirst', $t_programs );
		$t_values = array_slice( array_values($t_program), 0, 5 );
		$others = $total - array_sum($t_values);
		$t_values = array_merge( [$others], $t_values );
		//var_dump( $t_programs );
		//var_dump( $t_values );
		
		$t_return = [
			'total' => $total,
			'programs' => $t_programs,
			'values' => $t_values,
		];
		
		return json_encode( $t_return );
	}
	
	
	public static function reports_tags_pie( $db )
	{
		$t_tag = [];
		$t_total = [];
		$total = 0;
		$none = 0;
		
		foreach( $db->getReports() as $report )
		{
			$r_tags = $report->getTags();
			
			if( $r_tags && is_array($r_tags) && count($r_tags) )
			{
				foreach( $r_tags as $tag )
				{
					if( !isset($t_tag[$tag]) ) {
						$t_tag[ $tag ] = 0;
					}
					$t_tag[ $tag ]++;
				}
			}
			else
			{
				$none++;
			}
			
			$total++;
		}
		
		arsort( $t_tag );
		//var_dump( $total );
		
		$t_tags = array_slice( array_keys($t_tag), 0, 5 );
		$t_tags = array_merge( ['untaged'], $t_tags );
		$t_tags = array_merge( ['other'], $t_tags );
		$t_tags = array_map( 'ucfirst', $t_tags );
		$t_values = array_slice( array_values($t_tag), 0, 5 );
		$others = $total - $none - array_sum($t_values);
		$t_values = array_merge( [$none], $t_values );
		$t_values = array_merge( [$others], $t_values );
		//var_dump( $t_tags );
		//var_dump( $t_values );
		
		$t_return = [
			'total' => $total,
			'tags' => $t_tags,
			'values' => $t_values,
		];
		
		return json_encode( $t_return );
	}
	
	
	public static function reports_rating( $db, $jencode=true )
	{
		$t_p0 = [];
		$t_p1 = [];
		$t_p2 = [];
		$t_p3 = [];
		$t_p4 = [];
		$t_p5 = [];
		$t_total = [];
		$t_average = [];
		$t_reputation = [];
		$n = 0;
		
		foreach( $db->getReports() as $report )
		{
			$d = date( 'm/y', $report->getCreatedAt() );
			if( !isset($t_total[$d]) ) {
				$t_total[ $d ] = 0;
			}
			$t_total[ $d ]++;
			
			if( !isset($t_reputation[$d]) ) {
				$t_reputation[ $d ] = 0;
			}
			$t_reputation[$d] += $report->getTotalReputation();

			$tab = 't_p'.(int)$report->getRating();
			if( !isset($$tab[$d]) ) {
				$$tab[ $d ] = 0;
			}
			$$tab[ $d ]++;
		}
		
		$t_total = self::createTimeline( $t_total, $db->getFirstReportDate() );
		$average_report = (float)sprintf( '%.02f', array_sum($t_total) / count($t_total) );
		$average_rate = array_sum($t_p1)*5 + array_sum($t_p2)*4 + array_sum($t_p3)*3 + array_sum($t_p4)*2 + array_sum($t_p5)*1;
		$average_rate = (float)sprintf( '%.02f', $average_rate / (array_sum($t_total)-array_sum($t_p0)) );
		
		$t_reputation = self::createTimeline( $t_reputation, $db->getFirstReportDate() );
		$t_p0 = self::createTimeline( $t_p0, $db->getFirstReportDate() );
		$t_p1 = self::createTimeline( $t_p1, $db->getFirstReportDate() );
		$t_p2 = self::createTimeline( $t_p2, $db->getFirstReportDate() );
		$t_p3 = self::createTimeline( $t_p3, $db->getFirstReportDate() );
		$t_p4 = self::createTimeline( $t_p4, $db->getFirstReportDate() );
		$t_p5 = self::createTimeline( $t_p5, $db->getFirstReportDate() );
		foreach( $t_total as $d=>$n ) {
			if( $t_p0[$d] || $t_p1[$d] || $t_p2[$d] || $t_p3[$d] || $t_p4[$d] || $t_p5[$d] ) {
				$t_total[$d] = 0;
			}
			$t_average_report[ $d ] = $average_report;
			$t_average_rate[ $d ] = $average_rate;
		}
		
		$t_return = [];
		
		$t_return['p0'] = array_values( $t_p0 );
		$t_return['p1'] = array_values( $t_p1 );
		$t_return['p2'] = array_values( $t_p2 );
		$t_return['p3'] = array_values( $t_p3 );
		$t_return['p4'] = array_values( $t_p4 );
		$t_return['p5'] = array_values( $t_p5 );
		$t_return['total'] = array_values( $t_total );
		$t_return['average_report'] = array_values( $t_average_report );
		$t_return['average_rate'] = array_values( $t_average_rate );
		$t_return['reputation'] = array_values( $t_reputation );
		$t_return['categories'] = array_keys( $t_total );
		
		return json_encode( $t_return );
	}
	
	
	public static function reports_rating_pie( $db )
	{
		$p0 = $p1 = $p2 = $p3 = $p4 = $p5 = 0;
		
		foreach( $db->getReports() as $report )
		{
			$var = 'p'.$report->getRating();
			$$var++;
		}

		//if( !$p0 && !$p1 && !$p2 && !$p3 && !$p4 && !$p5 ) {
		//	return false;
		//}
		
		$t_return = [
			'total' => $p0+$p1+$p2+$p3+$p4+$p5,
			'p0' => $p0,
			'p1' => $p1,
			'p2' => $p2,
			'p3' => $p3,
			'p4' => $p4,
			'p5' => $p5,
		];
		
		return json_encode( $t_return );
	}
	
	
	public static function bounties( $db )
	{
		$t_datas_rcd = [];
		$t_datas_pd = [];
		$t_average_rcd = [];
		$t_average_pd = [];
		$t_reputation = [];
		
		foreach( $db->getReports() as $report )
		{
			$dr = date( 'm/y', $report->getCreatedAt() );
			
			if( !isset($t_reputation[$dr]) ) {
				$t_reputation[ $dr ] = 0;
			}
			$t_reputation[$dr] += $report->getTotalReputation();

			foreach( $report->getBounties() as $bounty )
			{
				if( !isset($t_datas_rcd[$dr]) ) {
					$t_datas_rcd[ $dr ] = $bounty->amount;
				} else {
					$t_datas_rcd[ $dr ] += $bounty->amount;
				}

				$d = date( 'm/y', $bounty->created_at );
				if( !isset($t_datas_pd[$d]) ) {
					$t_datas_pd[ $d ] = $bounty->amount;
				} else {
					$t_datas_pd[ $d ] += $bounty->amount;
				}
			}
		}
		
		$t_reputation = self::createTimeline( $t_reputation, $db->getFirstReportDate() );
		$t_datas_rcd = self::createTimeline( $t_datas_rcd, $db->getFirstReportDate() );
		$t_datas_pd = self::createTimeline( $t_datas_pd, $db->getFirstReportDate() );
		$average_rcd = (float)sprintf( '%.02f', array_sum( $t_datas_rcd ) / count($t_datas_rcd) );
		$average_pd = (float)sprintf( '%.02f', array_sum( $t_datas_pd ) / count($t_datas_pd) );

		foreach( $t_datas_rcd as $d=>$v ) {
			$t_average_rcd[$d] = $average_rcd;
			$t_average_pd[$d] = $average_pd;
		}
		
		$t_return = [];
		$t_return['reputation'] = array_values( $t_reputation );
		$t_return['categories'] = array_keys( $t_datas_rcd );
		$t_return['report_creation_date'] = array_values( $t_datas_rcd );
		$t_return['payday'] = array_values( $t_datas_pd );
		$t_return['report_creation_date_average'] = array_values( $t_average_rcd );
		$t_return['payday_average'] = array_values( $t_average_pd );
		
		return json_encode( $t_return );
	}
	
	
	public static function bounties_reports_evolution( $db )
	{
		$t_reputation = [];
		$t_bounties = [];
		$t_reports = [];
		
		foreach( $db->getReports() as $report )
		{
			$d = date( 'm/y', $report->getCreatedAt() );

			if( !isset($t_reports[$d]) ) {
				$t_reports[ $d ] = 0;
			}
			$t_reports[ $d ]++;
			
			foreach( $report->getBounties() as $bounty ) {
				if( !isset($t_bounties[$d]) ) {
					$t_bounties[ $d ] = 0;
				}
				$t_bounties[ $d ] += $bounty->amount;
			}
			
			foreach( $report->getReputations() as $reput ) {
				if( !isset($t_reputation[$d]) ) {
					$t_reputation[ $d ] = 0;
				}
				$t_reputation[ $d ] += $reput->points;
			}
		}
		
		
		$t_reports = self::createTimeline( $t_reports, $db->getFirstReportDate() );
		$t_bounties = self::createTimeline( $t_bounties, $db->getFirstReportDate() );
		$t_reputation = self::createTimeline( $t_reputation, $db->getFirstReportDate() );
		
		$total = 0;
		foreach( $t_reports as $d=>$n ) {
			$total += $n;
			$t_reports[$d] = $total;
		}

		$total = 0;
		foreach( $t_bounties as $d=>$n ) {
			$total += $n;
			$t_bounties[$d] = $total;
		}

		$total = 0;
		foreach( $t_reputation as $d=>$n ) {
			$total += $n;
			$t_reputation[$d] = $total;
		}

		$t_return = [];
		$t_return['categories'] = array_keys( $t_reports );
		$t_return['n_reports'] = array_values( $t_reports );
		$t_return['n_bounties'] = array_values( $t_bounties );
		$t_return['n_reputation'] = array_values( $t_reputation );
		
		return json_encode( $t_return );
	}
	
	
	public static function createTimeline( $t_datas, $start_date, $end_date=null )
	{
		if( is_null($end_date) ) {
			$end_date = time();
		}
		
		$t_date = [];
		$start = date( 'Ym', $start_date );
		$end = date( 'Ym', $end_date );
		$start_month = date( 'n', $start_date );
		$start_year = date( 'Y', $start_date );
		
		for( $i=$start,$j=0 ; $i<$end ; $j++ ) {
			$ts = mktime( 0, 0, 0, $start_month+$j, 1, $start_year );
			$i = date( 'Ym', $ts );
			$t_date[] = date('m/y', $ts );
		}

		$t_final = [];
		
		foreach( $t_date as $d ) {
			if( isset($t_datas[$d]) ) {
				$t_final[$d] = $t_datas[$d];
			} else {
				$t_final[$d] = 0;
			}
		}
		
		return $t_final;
	}
}
