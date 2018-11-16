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
	<div id="program-times-triage"></div>
</div>

<script>
	var program_times_triage_datas = <?php echo Statistics::program_times2( $db, mktime(0,0,0,date('m')-$dm,date('d'),date('Y')) ) ?>;
	var program_times_triage = Highcharts.chart('program-times-triage', {
	    credits: {
	        enabled: false
	    },
	    chart: {
            type: 'column',
	    },
	    title: {
	        text: 'SLA: triage last <?php echo $dm; ?> months'
	    },
	    xAxis: {
			categories: program_times_triage_datas.categories
	    },
	    yAxis: [{
            min: 0,
	        title: {
	            text: 'days'
	        }
	    }],
        plotOptions: {
            column: {
                pointPadding: 0,
                borderWidth: 0
            }
        },
        tooltip: {
            shared: true
        },
	    series: [{
            name: 'None',
            data: program_times_triage_datas.datas.t_triage.none,
	        color: '#0278b8'
		},{
            name: 'Low',
            data: program_times_triage_datas.datas.t_triage.low,
	        color: '#5eae00'
		},{
            name: 'Medium',
            data: program_times_triage_datas.datas.t_triage.medium,
	        color: '#f0ad4e'
		},{
            name: 'High',
            data: program_times_triage_datas.datas.t_triage.high,
	        color: '#ff6900'
		},{
            name: 'Critical',
            data: program_times_triage_datas.datas.t_triage.critical,
	        color: '#d13535',
		}]
	});

    $(document).ready(function() {
        var chart = $('#program-times-triage').highcharts();
        /*chart.xAxis[0].setExtremes(
            '01/18',
            '05/18'
        );*/
    });
</script>
