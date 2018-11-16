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
	<div id="program-times2"></div>
</div>


<script>
	var program_times_data2 = <?php echo Statistics::program_times2( $db ) ?>;
	var program_times2 = Highcharts.chart('program-times2', {
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
			categories: program_times_data2.categories
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
                fillColor: {
                    linearGradient: [0, 0, 0, 0],
                    stops: [
                        [0, Highcharts.getOptions().colors[0]],
                        [1, 'rgba(255,255,255,0)']
                    ]
                },
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
	        type: 'area',
            name: 'Average time to 1st response',
            data: program_times_data2.datas.t_first_response.all,
		},{
	        type: 'area',
            name: 'Average time to 1st bounty',
            data: program_times_data2.datas.t_first_bounty.all,
		},{
	        type: 'area',
            name: 'Average resolution time',
            data: program_times_data2.datas.t_resolution.all,
		},{
	        type: 'area',
            name: 'Average triage time',
            data: program_times_data2.datas.t_triage.all,
		}]
	});

    $(document).ready(function() {
        var chart = $('#program-times2').highcharts();
        /*chart.xAxis[0].setExtremes(
            '01/18',
            '05/18'
        );*/
    });
</script>
