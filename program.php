<?php

require_once( 'config.php' );

if( isset($_GET['program']) ) {
    if( !$db=Program::load($_GET['program']) ) {
        exit( 'Cannot load program, you should run the grabber first!' );
    }
} else {
    exit( 'Cannot load program, you should run the grabber first!' );
}

$t_reports = $db->getReports();

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
	    <span id="filter-reset"><a href="javascript:setFilterTerm('');"><img src="img/stop.png" width="20" /></a></span>

        <div id="program-container" class="container-fluid">
            <div class="row">
                <div class="col-md-12" id="program-infos">
                    <?php include( 'include/program_infos.php' ); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
					<?php include( 'graph/graph_program_evolution_severity.php' ); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
					<?php include( 'graph/graph_program_bounty.php' ); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
					<?php include( 'graph/graph_program_times2.php' ); ?>
                </div>
            </div>
            <div class="row">
            <div class="col-md-6">
                    <?php include( 'graph/graph_program_times_triage.php' ); ?>
                </div>
                <div class="col-md-6">
                    <?php include( 'graph/graph_program_times_resolution.php' ); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
					<?php include( 'include/program_listing.php' ); ?>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <?php //include( 'graph/graph_reports_ratings_pie.php' ); ?>
                            <?php include( 'graph/graph_reports_severity_pie.php' ); ?>
                        </div>
                        <div class="col-md-6">
                            <?php include( 'graph/graph_reports_tags_pie.php' ); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?php include( 'graph/graph_reports_state_pie.php' ); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php include( 'graph/top_program_best_hackers.php' ); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php include( 'graph/top_program_best_spammers.php' ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script type="text/javascript">
            function setFilterTerm( term )
            {
				var input_search = $('input[type="search"]');
				input_search.val( term );
				$("html, body").animate({scrollTop: 2000}, 100);
				input_search.keyup();
            }

            $(document).ready(function(){
				$('.search-term').click(function(){
					var term = $(this).html();
		            setFilterTerm( term );
	            });

            	$('.datatable').DataTable({
            		'paging': true,
				    'pageLength': 75,
				    'order': [[ 4, 'desc' ]],
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
