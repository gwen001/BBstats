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
	<div id="program-bounty"></div>
</div>


<script>
	var program_bounty_data = <?php echo Statistics::program_bounty( $db ) ?>;
	var program_bounty = Highcharts.chart('program-bounty', {
	    credits: {
	        enabled: false
	    },
	    chart: {
	        type: 'column'
	    },
	    title: {
	        text: 'Bounties evolution'
	    },
	    xAxis: {
			categories: program_bounty_data.categories
	    },
	    yAxis: [{
	        min: 0,
	        title: {
	            text: '$$$'
	        },
	        stackLabels: {
	            enabled: true,
	            style: {
	                fontWeight: 'bold',
	                cursor: 'pointer',
	                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
	            }
	        }
	    }],
	    tooltip: {
	        shared: true
	    },
	    plotOptions: {
	        column: {
	            dataLabels: {
	                enabled: false,
	                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
	            }
	        },
			series: {
				cursor: 'pointer',
				marker: {
					//enabled: false,
                    radius: 2
                },
                point: {
					events: {
						click: function () {
							var c = this.category.split('/');
							var d = '20' + c[1] + '/' + c[0];
							setFilterTerm( d );
						}
					}
				}
		    }
		},
	    series: [{
	        name: 'Bounties',
	        data: program_bounty_data.bounties,
	        color: '#c124bf',
	        tooltip: {
            	valueSuffix: ' $'
        	},
	        marker: {
	            enabled: false
	        }
	    }, {
			type: 'spline',
	        name: 'Average bounties',
	        data: program_bounty_data.average_bounties,
	        color: '#f659ee',
	        tooltip: {
            	valueSuffix: ' $'
        	},
	        marker: {
	            enabled: false
	        }
		}]
	});
</script>
