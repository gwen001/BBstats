<?php

/**
 * I don't believe in license
 * You can do want you want with this program
 * - gwen -
 */

class Statistics
{
	const TOP_LIMIT = 10;

	public static function top_program_html( $db )
	{
		$limit = self::TOP_LIMIT;
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
			if( $report->getIgnore() ) {
				continue;
			}

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
		$limit = self::TOP_LIMIT;
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
			if( $report->getIgnore() ) {
				continue;
			}

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
			if( $report->getIgnore() ) {
				continue;
			}

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
			if( $report->getIgnore() ) {
				continue;
			}

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
			if( $report->getIgnore() ) {
				continue;
			}

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
			if( $report->getIgnore() ) {
				continue;
			}

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
			if( $report->getIgnore() ) {
				continue;
			}

			$var = 'p'.$report->getRating();
			$$var++;
		}

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
	

	public static function reports_severity_pie( $db )
	{
		$none = $low = $medium = $high = $critical = 0;
		
		foreach( $db->getReports() as $report )
		{
			if( $report->getIgnore() ) {
				continue;
			}

			$var = $report->getSeverity();
			$$var++;
		}

		$t_return = [
			'total' => $none+$low+$medium+$high+$critical,
			'none' => $none,
			'low' => $low,
			'medium' => $medium,
			'high' => $high,
			'critical' => $critical,
		];
		
		return json_encode( $t_return );
	}
	
	
	public static function reports_state_pie( $db )
	{
		$t_state = [ 'new'=>0,'triaged'=>0,'duplicate'=>0,'informative'=>0,'not-applicable'=>0,'resolved'=>0,'spam'=>0 ];
		
		foreach( $db->getReports() as $report )
		{
			if( $report->getIgnore() ) {
				continue;
			}
			
			$s = strtolower( $report->getState() );
			$t_state[$s]++;
		}

		$t_return = [
			'total' => $t_state['new']+$t_state['triaged']+$t_state['duplicate']+$t_state['informative']+$t_state['not-applicable']+$t_state['resolved']+$t_state['spam'],
			's_new' => $t_state['new'],
			's_triaged' => $t_state['triaged'],
			's_duplicate' => $t_state['duplicate'],
			's_informative' => $t_state['informative'],
			's_not_applicable' => $t_state['not-applicable'],
			's_resolved' => $t_state['resolved'],
			's_spam' => $t_state['spam'],
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
			if( $report->getIgnore() ) {
				continue;
			}

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
			if( $report->getIgnore() ) {
				continue;
			}

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
	
	
	public static function program_evolution( $db )
	{
		$t_p0 = [];
		$t_p1 = [];
		$t_p2 = [];
		$t_p3 = [];
		$t_p4 = [];
		$t_p5 = [];
		$t_none      = [];
		$t_low      = [];
		$t_medium   = [];
		$t_high     = [];
		$t_critical = [];
		$t_total    = [];
		$t_bounties = [];
		$t_average_bounties  = [];
		$t_cnt = [ 'none'=>0, 'low'=>0, 'medium'=>0, 'high'=>0, 'critical'=>0 ];
		$first_report_date = null;
		
		foreach( $db->getReports() as $report )
		{
			/*if( $report->getIgnore() ) {
				continue;
			}*/

			$d = date( 'm/y', $report->getCreatedAt() );

			if( is_null($first_report_date) || $report->getCreatedAt() < $first_report_date ) {
				$first_report_date = $report->getCreatedAt();
			}

			if( !isset($t_total[$d]) ) {
				$t_total[ $d ] = 0;
			}
			$t_total[ $d ]++;
			
			if( !isset($t_bounties[$d]) ) {
				$t_bounties[ $d ] = 0;
			}
			$t_bounties[ $d ] += $report->getTotalBounty();

			$tab = 't_'.$report->getSeverity();
			if( !isset($$tab[$d]) ) {
				$$tab[ $d ] = 0;
			}
			$$tab[ $d ]++;

			$tab = 't_p'.(int)$report->getRating();
			if( !isset($$tab[$d]) ) {
				$$tab[ $d ] = 0;
			}
			$$tab[ $d ]++;

			$t_cnt[ $report->getSeverity() ]++;
		}

		$t_total = self::createTimeline( $t_total, $first_report_date );
		$t_bounties = self::createTimeline( $t_bounties, $first_report_date );
		$t_none = self::createTimeline( $t_none, $first_report_date );
		$t_low = self::createTimeline( $t_low, $first_report_date );
		$t_medium = self::createTimeline( $t_medium, $first_report_date );
		$t_high = self::createTimeline( $t_high, $first_report_date );
		$t_critical = self::createTimeline( $t_critical, $first_report_date );
		$t_p0 = self::createTimeline( $t_p0, $db->getFirstReportDate() );
		$t_p1 = self::createTimeline( $t_p1, $db->getFirstReportDate() );
		$t_p2 = self::createTimeline( $t_p2, $db->getFirstReportDate() );
		$t_p3 = self::createTimeline( $t_p3, $db->getFirstReportDate() );
		$t_p4 = self::createTimeline( $t_p4, $db->getFirstReportDate() );
		$t_p5 = self::createTimeline( $t_p5, $db->getFirstReportDate() );
			
		foreach( $t_total as $d=>$n ) {
			if( $t_total[$d] == 0 || $t_bounties[$d] == 0 ) {
				$t_average_bounties[$d] = 0;
			} else {
				$t_average_bounties[$d] = (int)($t_bounties[$d]/$t_total[$d]);
			}
			if( $t_p0[$d] || $t_p1[$d] || $t_p2[$d] || $t_p3[$d] || $t_p4[$d] || $t_p5[$d] ) {
				$t_total[$d] = 0;
			}
		}
		
		$t_return = [];
		$t_return['p0'] = array_values( $t_p0 );
		$t_return['p1'] = array_values( $t_p1 );
		$t_return['p2'] = array_values( $t_p2 );
		$t_return['p3'] = array_values( $t_p3 );
		$t_return['p4'] = array_values( $t_p4 );
		$t_return['p5'] = array_values( $t_p5 );
		$t_return['none'] = array_values( $t_none );
		$t_return['low'] = array_values( $t_low );
		$t_return['medium'] = array_values( $t_medium );
		$t_return['high'] = array_values( $t_high );
		$t_return['critical'] = array_values( $t_critical );
		$t_return['categories'] = array_keys( $t_total );
		$t_return['total'] = array_values( $t_total );
		$t_return['bounties'] = array_values( $t_bounties );
		$t_return['average_bounties'] = array_values( $t_average_bounties );
		$t_return['cnt'] = $t_cnt;
		
		return json_encode( $t_return );
	}


	public static function program_bounty( $db )
	{
		$t_p0 = [];
		$t_p1 = [];
		$t_p2 = [];
		$t_p3 = [];
		$t_p4 = [];
		$t_p5 = [];
		$t_none      = [];
		$t_low      = [];
		$t_medium   = [];
		$t_high     = [];
		$t_critical = [];
		$t_total    = [];
		$t_bounties = [];
		$t_average_bounties  = [];
		$t_cnt = [ 'none'=>0, 'low'=>0, 'medium'=>0, 'high'=>0, 'critical'=>0 ];
		$first_report_date = null;
		
		foreach( $db->getReports() as $report )
		{
			/*if( $report->getIgnore() ) {
				continue;
			}*/

			$d = date( 'm/y', $report->getCreatedAt() );

			if( is_null($first_report_date) || $report->getCreatedAt() < $first_report_date ) {
				$first_report_date = $report->getCreatedAt();
			}

			if( !isset($t_total[$d]) ) {
				$t_total[ $d ] = 0;
			}
			$t_total[ $d ]++;
			
			if( !isset($t_bounties[$d]) ) {
				$t_bounties[ $d ] = 0;
			}
			$t_bounties[ $d ] += $report->getTotalBounty();

			$tab = 't_'.$report->getSeverity();
			if( !isset($$tab[$d]) ) {
				$$tab[ $d ] = 0;
			}
			$$tab[ $d ]++;

			$tab = 't_p'.(int)$report->getRating();
			if( !isset($$tab[$d]) ) {
				$$tab[ $d ] = 0;
			}
			$$tab[ $d ]++;

			$t_cnt[ $report->getSeverity() ]++;
		}

		$t_total = self::createTimeline( $t_total, $first_report_date );
		$t_bounties = self::createTimeline( $t_bounties, $first_report_date );
		$t_none = self::createTimeline( $t_none, $first_report_date );
		$t_low = self::createTimeline( $t_low, $first_report_date );
		$t_medium = self::createTimeline( $t_medium, $first_report_date );
		$t_high = self::createTimeline( $t_high, $first_report_date );
		$t_critical = self::createTimeline( $t_critical, $first_report_date );
		$t_p0 = self::createTimeline( $t_p0, $db->getFirstReportDate() );
		$t_p1 = self::createTimeline( $t_p1, $db->getFirstReportDate() );
		$t_p2 = self::createTimeline( $t_p2, $db->getFirstReportDate() );
		$t_p3 = self::createTimeline( $t_p3, $db->getFirstReportDate() );
		$t_p4 = self::createTimeline( $t_p4, $db->getFirstReportDate() );
		$t_p5 = self::createTimeline( $t_p5, $db->getFirstReportDate() );
			
		foreach( $t_total as $d=>$n ) {
			if( $t_total[$d] == 0 || $t_bounties[$d] == 0 ) {
				$t_average_bounties[$d] = 0;
			} else {
				$t_average_bounties[$d] = (int)($t_bounties[$d]/$t_total[$d]);
			}
			if( $t_p0[$d] || $t_p1[$d] || $t_p2[$d] || $t_p3[$d] || $t_p4[$d] || $t_p5[$d] ) {
				$t_total[$d] = 0;
			}
		}
		
		$t_return = [];
		$t_return['p0'] = array_values( $t_p0 );
		$t_return['p1'] = array_values( $t_p1 );
		$t_return['p2'] = array_values( $t_p2 );
		$t_return['p3'] = array_values( $t_p3 );
		$t_return['p4'] = array_values( $t_p4 );
		$t_return['p5'] = array_values( $t_p5 );
		$t_return['none'] = array_values( $t_none );
		$t_return['low'] = array_values( $t_low );
		$t_return['medium'] = array_values( $t_medium );
		$t_return['high'] = array_values( $t_high );
		$t_return['critical'] = array_values( $t_critical );
		$t_return['categories'] = array_keys( $t_total );
		$t_return['total'] = array_values( $t_total );
		$t_return['bounties'] = array_values( $t_bounties );
		$t_return['average_bounties'] = array_values( $t_average_bounties );
		$t_return['cnt'] = $t_cnt;
		
		return json_encode( $t_return );
	}


	public static function program_times( $db )
	{
		$t_first_response = [];
		$t_first_bounty = [];
		$t_triage = [];
		$t_resolution = [];
		$t_created_at = [ 'fr'=>[], 'fb'=>[], 'r'=>[] ];
		$first_report_date = null;

		foreach( $db->getReports() as $r )
		{
			/*if( $r->getIgnore() ) {
				continue;
			}*/

			$d = date( 'm/y', $r->getCreatedAt() );
		
			if( is_null($first_report_date) || $r->getCreatedAt() < $first_report_date ) {
				$first_report_date = $r->getCreatedAt();
			}

			if( !isset($t_first_response[$d]) ) {
				$t_first_response[ $d ] = [];
			}
			if( $r->getFirstResponseDate() ) {
				//$t_created_at['fr'][] = $r->getCreatedAt();
				$t_first_response[$d][] = $r->getFirstResponseTime();
			}

			if( !isset($t_first_bounty[$d]) ) {
				$t_first_bounty[ $d ] = [];
			}
			if( $r->getFirstBountyDate() ) {
				//$t_created_at['fr'][] = $r->getCreatedAt();
				$t_first_bounty[$d][] = $r->getFirstBountyTime();
			}

			if( !isset($t_triage[$d]) ) {
				$t_triage[ $d ] = [];
			}
			if( $r->getTriageDate() ) {
				//$t_created_at['fr'][] = $r->getCreatedAt();
				$t_triage[$d][] = $r->getTriageTime();
			}

			if( !isset($t_resolution[$d]) ) {
				$t_resolution[ $d ] = [];
			}
			if( $r->getResolutionDate() ) {
				//$t_created_at['fr'][] = $r->getCreatedAt();
				$t_resolution[$d][] = $r->getResolutionTime();
			}
			/*
			if( $r->getFirstResponseDate() ) {
				$t_created_at['fb'][] = $r->getCreatedAt();
				$t_first_response[] = [ 
					(int)($r->getCreatedAt().'000'), 
					$r->getFirstResponseTime(), 
				];
			}

			if( $r->getFirstBountyDate() ) {
				$t_created_at['fb'][] = $r->getCreatedAt();
				$t_first_bounty[] = [ 
					(int)($r->getCreatedAt().'000'), 
					$r->getFirstBountyTime(), 
				];
			}

			if( $r->getResolutionDate() ) {
				$t_created_at['r'][] = $r->getCreatedAt();
				$t_resolution[] = [ 
					(int)($r->getCreatedAt().'000'), 
					$r->getResolutionTime(), 
				];
			}
			*/
		}

		//var_dump($t_first_response);
		foreach( $t_first_response as $d=>$v ) {
			if( count($v)==0 || array_sum($v)==0 ) {
				$t_first_response[$d] = 0;
			} else {
				$t_first_response[$d] = (float)sprintf( "%.02f", @(array_sum($v) / count($v)) );
			}
		}
		//var_dump($t_first_response);
		foreach( $t_first_bounty as $d=>$v ) {
			if( count($v)==0 || array_sum($v)==0 ) {
				$t_first_bounty[$d] = 0;
			} else {
				$t_first_bounty[$d] = (float)sprintf( "%.02f", @(array_sum($v) / count($v)) );
			}
		}
		foreach( $t_triage as $d=>$v ) {
			if( count($v)==0 || array_sum($v)==0 ) {
				$t_triage[$d] = 0;
			} else {
				$t_triage[$d] = (float)sprintf( "%.02f", @(array_sum($v) / count($v)) );
			}
		}
		foreach( $t_resolution as $d=>$v ) {
			if( count($v)==0 || array_sum($v)==0 ) {
				$t_resolution[$d] = 0;
			} else {
				$t_resolution[$d] = (float)sprintf( "%.02f", @(array_sum($v) / count($v)) );
			}
		}
		
		ksort( $t_first_response );
		ksort( $t_first_bounty );
		ksort( $t_triage );
		ksort( $t_resolution );

		$t_first_response = self::createTimeline( $t_first_response, $first_report_date );
		$t_first_bounty = self::createTimeline( $t_first_bounty, $first_report_date );
		$t_triage = self::createTimeline( $t_triage, $first_report_date );
		$t_resolution = self::createTimeline( $t_resolution, $first_report_date );

		//exit();

		//var_dump($t_return);
		//array_multisort( $t_created_at['fr'], SORT_ASC, $t_first_response );
		//array_multisort( $t_created_at['fb'], SORT_ASC, $t_first_bounty );
		//array_multisort( $t_created_at['r'], SORT_ASC, $t_resolution );
		//var_dump($t_return);

		$t_return = [];
		$t_return['categories'] = array_keys( $t_first_response );
		$t_return['first_response'] = array_values( $t_first_response );
		$t_return['first_bounty'] = array_values( $t_first_bounty );
		$t_return['triage'] = array_values( $t_triage );
		$t_return['resolution'] = array_values( $t_resolution );
		//var_dump( $t_return );

		return json_encode( $t_return );
	}


	public static function program_times2( $db, $start_date=null, $end_date=null )
	{
		if( is_null($start_date) ) {
			$start_date = $db->getFirstReportDate();
		}
		if( is_null($end_date) ) {
			$end_date = time();
		}

		$t_bigone = [
			't_first_response' => [],
			't_first_bounty' => [],
			't_triage' => [],
			't_resolution' => [],
		];
		$t_severity = [ 'all', 'none', 'low', 'medium', 'high', 'critical' ];
		$first_report_date = $start_date;

		foreach( $t_bigone as &$t ) {
			$t = [];
			foreach( $t_severity as $s ) {
				$t[$s] = [];
			}
		}

		foreach( $db->getReports() as $r )
		{
			//$t = $tt = null;
			$d = date( 'm/y', $r->getCreatedAt() );
		
			foreach( $t_bigone as &$t ) {
				foreach( $t as &$tt ) {
					if( !isset($tt[$d]) ) {
						$tt[ $d ] = [];
					}
				}
			}

			if( $r->getFirstResponseDate() ) {
				$time = $r->getFirstResponseTime();
				$t_bigone['t_first_response']['all'][$d][] = $time;
				$t_bigone['t_first_response'][$r->getSeverity()][$d][] = $time;
			}	

			if( $r->getFirstBountyDate() ) {
				$time = $r->getFirstBountyTime();
				$t_bigone['t_first_bounty']['all'][$d][] = $time;
				$t_bigone['t_first_bounty'][$r->getSeverity()][$d][] = $time;
			}

			if( $r->getTriageDate() ) {
				$time = $r->getTriageTime();
				$t_bigone['t_triage']['all'][$d][] = $time;
				$t_bigone['t_triage'][$r->getSeverity()][$d][] = $time;
			}

			if( $r->getResolutionDate() ) {
				$time = $r->getResolutionTime();
				$t_bigone['t_resolution']['all'][$d][] = $time;
				$t_bigone['t_resolution'][$r->getSeverity()][$d][] = $time;
			}
		}

		$t_return = [];

		foreach( $t_bigone as $metric=>$data1 )
		{
			foreach( $data1 as $severity=>$data2 )
			{
				foreach( $data2 as $d=>$v ) {
					if( count($v)==0 || array_sum($v)==0 ) {
						$t_bigone[$metric][$severity][$d] = 0;
					} else {
						$t_bigone[$metric][$severity][$d] = (float)sprintf( "%.02f", @(array_sum($v) / count($v)) );
					}		
				}
			
				ksort( $t_bigone[$metric][$severity] );
				$r = self::createTimeline( $t_bigone[$metric][$severity], $first_report_date );
				$t_bigone[$metric][$severity] = array_values( $r );
			
				$t_return['categories'] = array_keys( $r );
			}
		}

		$t_return['datas'] = $t_bigone;

		return json_encode( $t_return );
	}


	public static function tags_evolution( $db )
	{
		$limit = self::TOP_LIMIT;
		$t_top = self::top_tags( $db );

		$t_tags = [];
		
		foreach( $db->getReports() as $report )
		{
			if( $report->getIgnore() ) {
				continue;
			}

			$d = date( 'm/y', $report->getCreatedAt() );

			foreach( $report->getTags() as $tag )
			{
				if( !isset($t_tags[$tag]) ) {
					$t_tags[$tag] = [];
				}

				if( !isset($t_tags[$tag][$d]) ) {
					$t_tags[$tag][ $d ] = 0;
				}
				$t_tags[$tag][ $d ]++;
			}
		}
		
		foreach( $t_tags as $k=>$t ) {
			$t_tags[$k] = self::createTimeline( $t, $db->getFirstReportDate() );
		}

		$t_top_datas = [];

		foreach( $t_top['t_n_report'] as $tag=>$datas ) {
			$t_top_datas[] = array_values( $t_tags[$tag] );
		}

		$t_return = [];
		$t_return['categories'] = array_keys( $t_tags[key($t_tags)] );
		$t_return['tags'] = array_keys( $t_top['t_n_report'] );
		$t_return['top_datas'] = $t_top_datas;
		//var_dump( $t_return );
		
		return json_encode( $t_return );
	}


	public static function top_program_best_hackers_html( $db )
	{
		$limit = self::TOP_LIMIT;
		$t_top = self::top_program_best_hackers( $db );

		ob_start();
		echo '<table class="table">
			<thead>
				<tr><th colspan="100">last 6 months</th></tr>
			</thead>
			<tbody>';
				for( $i=1; $i<=$limit && list($hacker,$data)=each($t_top['m6']['datas']) ; $i++ ) {
					echo '<tr class="top_'.$i.'">
						<td>'.$i.'</td>
						<td><span class="search-term">'.ucwords($hacker).'</span></td>
						<td class="text-right">'.$data['n_report'].'</td>
						<td class="text-right">'.$data['bounty'].' $</td>
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
					<th colspan="100">last year</th>
				</tr>
			</thead>
			<tbody>';
				for( $i=1; $i<=$limit && list($hacker,$data)=each($t_top['y1']['datas']) ; $i++ ) {
					echo '<tr class="top_'.$i.'">
						<td>'.$i.'</td>
						<td><span class="search-term">'.ucwords($hacker).'</span></td>
						<td class="text-right">'.$data['n_report'].'</td>
						<td class="text-right">'.$data['bounty'].' $</td>
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
					<th colspan="100">all time</th>
				</tr>
			</thead>
			<tbody>';
				for( $i=1; $i<=$limit && list($hacker,$data)=each($t_top['overall']['datas']) ; $i++ ) {
					echo '<tr class="top_'.$i.'">
						<td>'.$i.'</td>
						<td><span class="search-term">'.ucwords($hacker).'</span></td>
						<td class="text-right">'.$data['n_report'].'</td>
						<td class="text-right">'.$data['bounty'].' $</td>
					</tr>';
				}
		echo '</tbody>
		</table>';
		$reputation = ob_get_contents();
		ob_end_clean();
		
		return ['n_report'=>$n_report, 'bounty'=>$bounty, 'reputation'=>$reputation];
	}
	
	
	public static function top_program_best_hackers( $db )
	{
		$m6_start = mktime( 0, 0, 0, date('m')-6, date('d'), date('Y') );
		$m6_end = time();
		$y1_start = mktime( 0, 0, 0, 1, 1, date('Y')-1 );
		$y1_end = mktime( 0, 0, 0, 1, 1, date('Y') );
		$o_start = $db->getFirstReportDate();
		$o_end = time();

		$data = [
			'm6' => [
				'start_date' => mktime( 0, 0, 0, date('m')-6, date('d'), date('Y') ),
				'end_date' => time(),
				'datas' => [],
			],
			'y1' => [
				'start_date' => mktime( 0, 0, 0, 1, 1, date('Y')-1 ),
				'end_date' => mktime( 0, 0, 0, 1, 1, date('Y') ),
				'datas' => [],
			],
			'overall' => [
				'start_date' => $db->getFirstReportDate(),
				'end_date' => time(),
				'datas' => [],
			],
		];

		foreach( $db->getReports() as $report )
		{
			foreach( $data as $p=>$period )
			{
				if( $report->getCreatedAt() >= $period['start_date'] && $report->getCreatedAt() < $period['end_date'] )
				{
					$hacker = $report->getReporter();
					if( !isset($data[$p]['datas'][$hacker]) ) {
						$data[$p]['datas'][$hacker] = [ 'hacker'=>$hacker, 'n_report'=>0, 'bounty'=>0, 'average'=>0 ];
					}

					$data[$p]['datas'][$hacker]['n_report']++;
					$data[$p]['datas'][$hacker]['bounty'] += $report->getTotalBounty();
				}
			}
		}

		foreach( $data as $p=>$period )
		{
			$tmp = [ 'hacker'=>[], 'n_report'=>[], 'bounty'=>[], 'average'=>[] ];

			foreach( $period['datas'] as $h=>$hacker )
			{
				$data[$p]['datas'][$h]['average'] = @(int)($data[$p]['datas'][$h]['bounty'] / $data[$p]['datas'][$h]['n_report']);

				$tmp['hacker'][] = $data[$p]['datas'][$h]['hacker'];
				$tmp['n_report'][] = $data[$p]['datas'][$h]['n_report'];
				$tmp['bounty'][] = $data[$p]['datas'][$h]['bounty'];
				$tmp['average'][] = $data[$p]['datas'][$h]['average'];
			}

			array_multisort( $tmp['bounty'], SORT_DESC, $tmp['n_report'], SORT_ASC, SORT_NUMERIC, $data[$p]['datas'] );		
		
		}

		return $data;
	}

	public static function top_program_best_spammers_html( $db )
	{
		$limit = self::TOP_LIMIT;
		$t_top = self::top_program_best_spammers( $db );

		ob_start();
		echo '<table class="table">
			<thead>
				<tr><th colspan="100">last 6 months</th></tr>
			</thead>
			<tbody>';
				for( $i=1; $i<=$limit && list($hacker,$data)=each($t_top['m6']['datas']) ; $i++ ) {
					echo '<tr class="top_'.$i.'">
						<td>'.$i.'</td>
						<td><span class="search-term">'.ucwords($hacker).'</span></td>
						<td class="text-right">'.$data['n_report'].'</td>
						<td class="text-right">'.$data['bounty'].' $</td>
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
					<th colspan="100">last year</th>
				</tr>
			</thead>
			<tbody>';
				for( $i=1; $i<=$limit && list($hacker,$data)=each($t_top['y1']['datas']) ; $i++ ) {
					echo '<tr class="top_'.$i.'">
						<td>'.$i.'</td>
						<td><span class="search-term">'.ucwords($hacker).'</span></td>
						<td class="text-right">'.$data['n_report'].'</td>
						<td class="text-right">'.$data['bounty'].' $</td>
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
					<th colspan="100">all time</th>
				</tr>
			</thead>
			<tbody>';
				for( $i=1; $i<=$limit && list($hacker,$data)=each($t_top['overall']['datas']) ; $i++ ) {
					echo '<tr class="top_'.$i.'">
						<td>'.$i.'</td>
						<td><span class="search-term">'.ucwords($hacker).'</span></td>
						<td class="text-right">'.$data['n_report'].'</td>
						<td class="text-right">'.$data['bounty'].'('.$data['average'].') $</td>
					</tr>';
				}
		echo '</tbody>
		</table>';
		$reputation = ob_get_contents();
		ob_end_clean();
		
		return ['n_report'=>$n_report, 'bounty'=>$bounty, 'reputation'=>$reputation];
	}
	
	
	public static function top_program_best_spammers( $db )
	{
		$m6_start = mktime( 0, 0, 0, date('m')-6, date('d'), date('Y') );
		$m6_end = time();
		$y1_start = mktime( 0, 0, 0, 1, 1, date('Y')-1 );
		$y1_end = mktime( 0, 0, 0, 1, 1, date('Y') );
		$o_start = $db->getFirstReportDate();
		$o_end = time();

		$data = [
			'm6' => [
				'start_date' => mktime( 0, 0, 0, date('m')-6, date('d'), date('Y') ),
				'end_date' => time(),
				'datas' => [],
			],
			'y1' => [
				'start_date' => mktime( 0, 0, 0, 1, 1, date('Y')-1 ),
				'end_date' => mktime( 0, 0, 0, 1, 1, date('Y') ),
				'datas' => [],
			],
			'overall' => [
				'start_date' => $db->getFirstReportDate(),
				'end_date' => time(),
				'datas' => [],
			],
		];

		foreach( $db->getReports() as $report )
		{
			foreach( $data as $p=>$period )
			{
				if( $report->getCreatedAt() >= $period['start_date'] && $report->getCreatedAt() < $period['end_date'] )
				{
					$hacker = $report->getReporter();
					if( !isset($data[$p]['datas'][$hacker]) ) {
						$data[$p]['datas'][$hacker] = [ 'hacker'=>$hacker, 'n_report'=>0, 'bounty'=>0, 'average'=>0 ];
					}

					$data[$p]['datas'][$hacker]['n_report']++;
					$data[$p]['datas'][$hacker]['bounty'] += $report->getTotalBounty();
				}
			}
		}

		foreach( $data as $p=>$period )
		{
			$tmp = [ 'hacker'=>[], 'n_report'=>[], 'bounty'=>[], 'average'=>[] ];

			foreach( $period['datas'] as $h=>$hacker )
			{
				$data[$p]['datas'][$h]['average'] = @(int)($data[$p]['datas'][$h]['bounty'] / $data[$p]['datas'][$h]['n_report']);

				$tmp['hacker'][] = $data[$p]['datas'][$h]['hacker'];
				$tmp['n_report'][] = $data[$p]['datas'][$h]['n_report'];
				$tmp['bounty'][] = $data[$p]['datas'][$h]['bounty'];
				$tmp['average'][] = $data[$p]['datas'][$h]['average'];
			}

			array_multisort( $tmp['bounty'], SORT_ASC, $tmp['n_report'], SORT_DESC, SORT_NUMERIC, $data[$p]['datas'] );		
		
		}

		return $data;
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
