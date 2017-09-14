<?php

require_once( 'config.php' );

$db = Database::getInstance();
if( !$db->load(DATABASE_FILE) ) {
	exit( 'Cannot load database, you should run the grabber first!' );
}

?>

<div class="col-md-12">
	<div id="bounties"></div>
</div>

<script>
	var bounties_data = <?php echo $s_bounties = Statistics::bounties( $db ); ?>;
	var bounties = Highcharts.chart('bounties', {
	    credits: {
	        enabled: false
	    },
	    chart: {
	        type: 'areaspline'
	    },
	    title: {
	        text: 'Bounties per month'
	    },
	    xAxis: {
	        categories: bounties_data.categories
	    },
	    yAxis: {
	        title: {
	            text: '$$$'
	        }
	    },
	    tooltip: {
	        shared: true,
	        valueSuffix: ' $'
	    },
	    plotOptions: {
	        areaspline: {
	            fillOpacity: 0.5
	        },
			series: {
				marker: {
					//enabled: false,
                    radius: 2
                },
				cursor: 'pointer',
				point: {
					events: {
						click: function () {
							c = this.category.split('/');
							d = '20' + c[1] + '/' + c[0];
							setFilterTerm( d );
						}
					}
				}
		    }
	    },
	    series: [{
	        name: 'Generated',
	        data: bounties_data.report_creation_date
	    }, {
	        name: 'Received',
	        data: bounties_data.payday
	    }, {
	        type: 'spline',
	        name: 'Average',
	        data: bounties_data.report_creation_date_average,
	        color: '#c124bf',
	        marker: {
	            enabled: false
	            //lineColor: Highcharts.getOptions().colors[2]
	            //lineWidth: 2,
	            //fillColor: 'white'
	        }
	    }],
	    mine: [{
	    	reload: function(){
		    	$.post( 'ajax.php', {'_a':'graph-reload','graph':'bounties'}, function(data) {
			        data = jQuery.parseJSON( data );
        			bounties.series[0].setData( data.report_creation_date );
        			bounties.series[1].setData( data.payday );
        			bounties.series[2].setData( data.report_creation_date_average );
        		});
		    }
	    }]
	});
</script>
