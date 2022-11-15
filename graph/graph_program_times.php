<?php

require_once( 'config.php' );

if( isset($_GET['program']) ) {
    if( !$db=Program::load($_GET['program']) ) {
        exit( 'Cannot load program, you should run the grabber first!' );
    }
} else {
    exit( 'Cannot load program, you should run the grabber first!' );
}

?>

<div class="col-md-12">
	<div id="program-times"></div>
</div>


<script>
	var program_times_data = <?php echo Statistics::program_times( $db ) ?>;
	var program_times = Highcharts.chart('program-times', {
	    credits: {
	        enabled: false
	    },
	    chart: {
	        zoomType: 'x'
	    },
	    title: {
	        text: 'Reactivity'
	    },
	    xAxis: {
			categories: program_times_data.categories
	    },
	    yAxis: [{
            min: 0,
            //max: 15,
	        title: {
	            text: 'days'
	        }
	    }],
	    plotOptions: {
            series: {
                lineWidth: 1,
                marker: {
                    //enabled: false
                    radius: 3
                },
                shadow: false,
                states: {
                    hover: {
                        lineWidth: 1
                    }
                },
                cursor: 'pointer',
                point: {
					events: {
						click: function () {
							var c = this.category.split('/');
							var d = '20' + c[1] + '/' + c[0];
							setFilterTerm( d );
						}
					}
				}
                //enableMouseTracking: false
            }
		},
        tooltip: {
            shared: true
        },
	    series: [{
            name: 'Average time to 1st response',
            data: program_times_data.first_response,
		},{
            name: 'Average time to 1st bounty',
            data: program_times_data.first_bounty,
		},{
            name: 'Average resolution time',
            data: program_times_data.resolution,
		},{
            name: 'Average triage time',
            data: program_times_data.triage,
		}]
	});

    $(document).ready(function() {
        var chart = $('#program-times').highcharts();
        /*chart.xAxis[0].setExtremes(
            '01/18',
            '05/18'
        );*/
    });
</script>
