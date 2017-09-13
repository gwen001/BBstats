<?php

require_once( 'config.php' );

$db = Database::getInstance();
if( !$db->load(DATABASE_FILE) ) {
	exit( 'Cannot load database, you should run the grabber first!' );
}

$limit = 10;
$t_top = Statistics::top_tags( $db );
//var_dump( $t_top );

?>

<div class="row">
	<div class="col-md-12 text-center">
		<h4>Top <?php echo $limit; ?> Tags</h4>
	</div>
</div>
<div class="row">
	<div class="col-md-4 datop">
		<table class="table">
			<thead>
				<tr>
					<th colspan="100">by report (<?php echo $t_top['t_total']['n_report']; ?>)</th>
				</tr>
			</thead>
			<tbody>
				<?php for( $i=1; $i<=$limit && list($tag,$data)=each($t_top['t_n_report']) ; $i++ ) { ?>
					<tr class="top_<?php echo $i; ?>">
						<td><?php echo $i; ?></td>
						<td><span class="search-term"><?php echo ucwords($tag); ?></span></td>
						<td class="text-right"><?php echo $data['n_report']; ?></td>
						<td class="text-right"><?php echo $data['n_report_p']; ?> %</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<div class="col-md-4 datop">
		<table class="table">
			<thead>
				<tr>
					<th colspan="100">by bounty (<?php echo $t_top['t_total']['bounty']; ?> $)</th>
				</tr>
			</thead>
			<tbody>
				<?php for( $i=1; $i<=$limit && list($tag,$data)=each($t_top['t_bounty']) ; $i++ ) { ?>
					<tr class="top_<?php echo $i; ?>">
						<td><?php echo $i; ?></td>
						<td><span class="search-term"><?php echo ucwords($tag); ?></span></td>
						<td class="text-right"><?php echo $data['bounty']; ?> $</td>
						<td class="text-right"><?php echo $data['bounty_p']; ?> %</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<div class="col-md-4 datop">
		<table class="table">
			<thead>
				<tr>
					<th colspan="100">by reputation (<?php echo $t_top['t_total']['reputation']; ?>)</th>
				</tr>
			</thead>
			<tbody>
				<?php for( $i=1; $i<=$limit && list($tag,$data)=each($t_top['t_reputation']) ; $i++ ) { ?>
					<tr class="top_<?php echo $i; ?>">
						<td><?php echo $i; ?></td>
						<td><span class="search-term"><?php echo ucwords($tag); ?></span></td>
						<td class="text-right"><?php echo $data['reputation']; ?></td>
						<td class="text-right"><?php echo $data['reputation_p']; ?> %</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>

<div class="spacer"></div>
