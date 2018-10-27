<?php

require_once( 'config.php' );

$db = Database::getInstance();
if( !$db->load(DATABASE_FILE) ) {
	exit( 'Cannot load database, you should run the grabber first!' );
}

$t_top = Statistics::top_program_html( $db );
//var_dump( $t_top );

?>

<div class="row">
	<div class="col-md-12 text-center">
		<h4>Top <?php echo Statistics::TOP_LIMIT; ?> Programs</h4>
	</div>
</div>
<div id="top-programs" class="row">
	<div class="col-md-4 datop n_report">
		<?php echo $t_top['n_report']; ?>
	</div>
	<div class="col-md-4 datop bounty">
		<?php echo $t_top['bounty']; ?>
	</div>
	<div class="col-md-4 datop reputation">
		<?php echo $t_top['reputation']; ?>
	</div>
</div>

<div class="spacer"></div>
