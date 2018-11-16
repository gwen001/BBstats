<?php

require_once( 'config.php' );

if( isset($_GET['program']) ) {
    if( !$db=Program::load($_GET['program']) ) {
        exit( 'Cannot load program, you should run the grabber first!' );
    }
} else {
    exit( 'Cannot load program, you should run the grabber first!' );
}

$dm = 12;

?>

<div class="col-md-12">
	<div id="program-times-resolution"></div>
</div>

<script>
	var program_times_resolution_datas = <?php echo Statistics::program_times2( $db, mktime(0,0,0,date('m')-$dm,date('d'),date('Y')) ) ?>;
	var program_times_resolution = Highcharts.chart('program-times-resolution', {
	    credits: {
	        enabled: false
	    },
	    chart: {
            type: 'column',
	    },
	    title: {
	        text: 'SLA: resolution last <?php echo $dm; ?> months'
	    },
	    xAxis: {
			categories: program_times_resolution_datas.categories
	    },
	    yAxis: [{
            min: 0,
	        title: {
	            text: 'days'
	        }
	    }],
        tooltip: {
            shared: true
        },
	    series: [{
            name: 'None',
            data: program_times_resolution_datas.datas.t_resolution.none,
	        color: '#0278b8'
		},{
            name: 'Low',
            data: program_times_resolution_datas.datas.t_resolution.low,
	        color: '#5eae00'
		},{
            name: 'Medium',
            data: program_times_resolution_datas.datas.t_resolution.medium,
	        color: '#f0ad4e'
		},{
            name: 'High',
            data: program_times_resolution_datas.datas.t_resolution.high,
	        color: '#ff6900'
		},{
            name: 'Critical',
            data: program_times_resolution_datas.datas.t_resolution.critical,
	        color: '#d13535',
		}]
	});

    $(document).ready(function() {
        var chart = $('#program-times-resolution').highcharts();
        /*chart.xAxis[0].setExtremes(
            '01/18',
            '05/18'
        );*/
    });
</script>
