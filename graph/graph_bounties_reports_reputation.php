<?php

require_once( 'config.php' );

$db = Database::getInstance();
if( !$db->load(DATABASE_FILE) ) {
	exit( 'Cannot load database, you should run the grabber first!' );
}

?>

<div class="col-md-12">
	<div id="bounties_reports"></div>
</div>

<script>
	var bounties_reports_data = <?php echo Statistics::bounties_reports_evolution( $db ); ?>;
	var bounties_reports = Highcharts.chart('bounties_reports', {
	    credits: {
	        enabled: false
	    },
	    chart: {
	        type: 'line'
	    },
	    title: {
	        text: 'Bounties/Reports/Reputation evolution'
	    },
	    xAxis: {
	        categories: bounties_reports_data.categories
	    },
	    yAxis: [{
	        min: 0,
	        title: {
	            text: '$$$'
	        }
	    }, {
	        title: {
	            text: 'n report'
	        },
	        opposite: true
	    }, {
	        title: {
	            text: 'reputation'
	        },
	        opposite: true
	    }],
	    tooltip: {
	        shared: true
	    },
	    plotOptions: {
			series: {
				marker: {
					//enabled: false,
                    radius: 2
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
		    }
	    },
	    series: [{
	        name: 'Bounties',
	        data: bounties_reports_data.n_bounties,
	        tooltip: {
            	valueSuffix: ' $'
        	}
	    }, {
            name: 'Reports',
	        data: bounties_reports_data.n_reports,
	        yAxis: 1
	    }, {
	        name: 'Reputation',
	        data: bounties_reports_data.n_reputation,
	        yAxis: 2
	    }],
	    mine: [{
	    	reload: function(){
		    	$.post( 'ajax.php', {'_a':'graph-reload','graph':'bounties-reports'}, function(data) {
			        data = jQuery.parseJSON( data );
        			bounties_reports.series[0].setData( data.n_bounties );
        			bounties_reports.series[1].setData( data.n_reports );
        			bounties_reports.series[2].setData( data.n_reputation );
        		});
		    }
	    }]
	});
</script>
