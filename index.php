<?php

/**
 * I don't believe in license
 * You can do want you want with this program
 * - gwen -
 */

require_once( 'config.php' );

$db = Database::getInstance();
if( !$db->load(DATABASE_FILE) ) {
	exit( 'Cannot load database, you should run the grabber first!' );
}

$t_reports = $db->getReports();
//$t_reports = array_reverse( $t_reports );
//var_dump( $t_reports );

$start_date = date( 'd/m/Y', $db->getFirstReportDate() );
$end_date = date( 'd/m/Y' );

?>

<!doctype html>
	<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
	<!--[if (IE 7)&!(IEMobile)]><html class="no-js lt-ie9 lt-ie8" lang="en"><![endif]-->
	<!--[if (IE 8)&!(IEMobile)]><html class="no-js lt-ie9" lang="en"><![endif]-->
	<!--[if gt IE 8]><!--> <html class="no-js" lang="en"><!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<title>Your bug bounty stats</title>
		<link rel="stylesheet" href="css/bootstrap3.3.min.css">
		<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
        <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
        <link rel="stylesheet" href="css/custom.css">
        <script src="js/jquery.min.js"></script>
        <script src="js/jquery.dataTables.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/dataTables.bootstrap.min.js"></script>
   		<script src="js/highcharts.src.min.js"></script>
	</head>
	
	<body>
		<?php include( 'include/popup_about.php' ); ?>
		<?php include( 'include/popup_more.php' ); ?>
		<?php include( 'include/popup_report_add.php' ); ?>
		<?php include( 'include/popup_report_edit.php' ); ?>
		<?php include( 'include/popup_tag_add.php' ); ?>
		
		<div id="menubar">
			<button id="add-bounty-btn" type="button" class="btn btn-warning" title="Manually add a report">+</button>
			<button id="about-btn" type="button" class="btn btn-success" title="About">?</button>
		</div>
		
		<span id="filter-reset"><a href="javascript:setFilterTerm('');"><img src="img/stop.png" width="20" /></a></span>
		
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-6" style="/*margin-top:30px;*/">
					<div class="row">
						<?php //include( 'include/filters_form.php' ); ?>
					</div>
					<div class="row">
						<?php include( 'include/listing.php' ); ?>
					</div>
				</div>
				<?php if( $t_reports ) { ?>
				<div class="col-md-6">
					<?php if( defined('GRAPH_BOUNTIES') && GRAPH_BOUNTIES ) { ?>
					<div class="row">
						<?php include( 'graph/graph_bounties.php' ); ?>
					</div>
					<?php } ?>
					<?php if( defined('GRAPH_BOUNTIES_REPORTS_REPUTATION') && GRAPH_BOUNTIES_REPORTS_REPUTATION ) { ?>
					<div class="row">
						<?php include( 'graph/graph_bounties_reports_reputation.php' ); ?>
					</div>
					<?php } ?>
					<?php if( defined('GRAPH_REPORTS_RATINGS') && GRAPH_REPORTS_RATINGS ) { ?>
					<div class="row">
						<?php include( 'graph/graph_reports_ratings.php' ); ?>
					</div>
					<?php } ?>
					<div class="row">
						<?php if( defined('GRAPH_RATINGS_PIE') && GRAPH_RATINGS_PIE ) { ?>
						<div class="col-md-6">
							<?php include( 'graph/graph_reports_ratings_pie.php' ); ?>
						</div>
						<?php } ?>
						<?php if( defined('GRAPH_PROGRAMS_PIE') && GRAPH_PROGRAMS_PIE ) { ?>
						<div class="col-md-6">
							<?php include( 'graph/graph_reports_programs_pie.php' ); ?>
						</div>
						<?php } ?>
						<?php if( defined('GRAPH_PLATFORMS_PIE') && GRAPH_PLATFORMS_PIE ) { ?>
						<div class="col-md-6">
							 <?php include( 'graph/graph_reports_platforms_pie.php' ); ?>
						</div>
						<?php } ?>
						<?php if( defined('GRAPH_STATE_PIE') && GRAPH_STATE_PIE ) { ?>
						<div class="col-md-6">
							<?php include( 'graph/graph_reports_state_pie.php' ); ?>
						</div>
						<?php } ?>
						<?php if( defined('GRAPH_TAGS_PIE') && GRAPH_TAGS_PIE ) { ?>
						<div class="col-md-6">
							<?php include( 'graph/graph_reports_tags_pie.php' ); ?>
						</div>
						<?php } ?>
					</div>
					<?php if( defined('GRAPH_TOP_PROGRAMS') && GRAPH_TOP_PROGRAMS ) { include( 'graph/top_programs.php' ); } ?>
					<?php if( defined('GRAPH_TOP_TAGS') && GRAPH_TOP_TAGS ) { include( 'graph/top_tags.php' ); } ?>
				</div>
				<?php } ?>
			</div>
		</div>
		
        <script type="text/javascript">
        	function reloadGraph()
        	{
        		for( var i=0 ; i<Highcharts.charts.length ; i++ ) {
        			Highcharts.charts[i].userOptions.mine[0].reload();
        		}
        		
            	if( $('#top-programs').length ) {
			    	$.post( 'ajax.php', {'_a':'graph-reload','graph':'top-programs-html'}, function(data) {
				        var data = jQuery.parseJSON( data );
		            	$.each(data,function(k,v){
		            		var tab = $('#top-programs').find('.'+k);
		            		if( tab.length ) {
		            			tab.html( v );
		            		}
		            	})
		            });
            	}
            	
            	if( $('#top-tags').length ) {
			    	$.post( 'ajax.php', {'_a':'graph-reload','graph':'top-tags-html'}, function(data) {
				        var data = jQuery.parseJSON( data );
		            	$.each(data,function(k,v){
		            		var tab = $('#top-tags').find('.'+k);
		            		if( tab.length ) {
		            			tab.html( v );
		            		}
		            	})
		            });
            	}
        	}
        	
        	function reloadReportLine( report_key )
			{
	            var tr = $('tr[data-key="'+report_key+'"]');
	            
	            $.post( 'ajax.php', {'_a':'report-get','key':report_key}, function(data) {
			        var report = jQuery.parseJSON( data );
					
			        var td_state = tr.find('.report-state');
		            td_state.attr( 'class', 'report-state state_'+report.state );

			        var input_title = tr.find('.report-title');
		            input_title.html( report.title );

		            var input_program = tr.find('.report-program');
			        input_program.html( report.program );

			        var input_bounty = tr.find('.report-bounty');
		            input_bounty.html( report.total_bounty );
		            
			        var input_created_at = tr.find('.report-created_at');
		            input_created_at.html( report.created_at );
			        
	            	var a = input_title.parent();
	            	a.removeClass( 'rating_0 rating_1 rating_2 rating_3 rating_4 rating_5' );
	            	a.addClass( 'rating_'+report.rating );
	            	
		            var input_tags = tr.find('.report-tags');
		            input_tags.html( '' );
	            	jQuery.each( report.tags, function(k,v){
		            	input_tags.append( '<span class="report-tag">'+v+'</span> ' );
	            	});
	            	
	            	reloadGraph();
	            });
			}
			
            function setFilterTerm( term )
            {
				var input_search = $('input[type="search"]');
				input_search.val( term );
				$("html, body").animate({scrollTop: 0}, 100);
				input_search.keyup();
            }
            
            $(document).ready(function() {
				$('.search-term').click(function(){
					var term = $(this).html();
		            setFilterTerm( term );
	            });
	            
            	$('.datatable').DataTable({
            		'paging': true,
				    'pageLength': 75,
				    'order': [[ 5, 'desc' ]],
				    'dom': '<"row top"<"col-md-3"i><"col-md-3"f><"col-md-6"p>>rt<"row bottom"<"col-md-3"i><"col-md-3"f><"col-md-6"p>>',
				     'oLanguage': {
						"sInfo": "Showing _START_ to _END_ of _MAX_",
						//"sInfo": "Got a total of _TOTAL_ entries to show (_START_ to _END_)",
						'sInfoFiltered': '',
						'sSearch': ''
				     }
            	});
            	
            	var reset_btn = $('#filter-reset');
				var input_search = $('input[type="search"]');
            	input_search.attr( 'placeholder', 'Search...' );
            	input_search.parent().append( reset_btn );
	        });
	    </script>
	</body>
</html>
