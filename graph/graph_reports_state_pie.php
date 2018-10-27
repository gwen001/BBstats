<?php

require_once( 'config.php' );

if( isset($_GET['program']) ) {
    if( !$db=Program::load($_GET['program']) ) {
        exit( 'Cannot load program, you should run the grabber first!' );
    }
} else {
	$db = Database::getInstance();
	if( !$db->load(DATABASE_FILE) ) {
		exit( 'Cannot load database, you should run the grabber first!' );
	}
}

?>

<div id="reports-state-pie"></div>

<script>
	var reports_state_pie_data = <?php echo Statistics::reports_state_pie( $db ) ?>;
	var reports_state_pie = Highcharts.chart('reports-state-pie', {
	    credits: {
	        enabled: false
	    },
	    chart: {
	        plotBackgroundColor: null,
	        plotBorderWidth: null,
	        plotShadow: false,
	        type: 'pie'
	    },
	    title: {
	        text: 'Status repartition'
	    },
	    subtitle: {
	        text: 'For a total of '+reports_state_pie_data.total+' reports'
	    },
	    tooltip: {
	        pointFormat: '<b>{point.percentage:.1f}%</b> ({point.y})'
	    },
	    plotOptions: {
	        pie: {
	            allowPointSelect: true,
	            cursor: 'pointer',
	            dataLabels: {
	                enabled: true,
	                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
	                style: {
	                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
	                }
	            }
	        },
	        series: {
				cursor: 'pointer',
				point: {
					events: {
						click: function () {
							var term = (this.name == 'Unrated') ? 'P0' : this.name;
							setFilterTerm( 's:'+term );
						}
					}
				}
		    }
	    },
	    series: [{
	        colorByPoint: true,
	        data: [{
	            name: 'New',
		        color: '#8e44ad',
	            y: reports_state_pie_data.s_new
	        }, {
	            name: 'Triaged',
		        color: '#e67e22',
	            y: reports_state_pie_data.s_triaged
	        }, {
	            name: 'Duplicate',
		        color: '#a78260',
	            y: reports_state_pie_data.s_duplicate
	        }, {
	            name: 'Informative',
		        color: '#cccccc',
	            y: reports_state_pie_data.s_informative
	        }, {
	            name: 'Not applicable',
		        color: '#ce3f4b',
	            y: reports_state_pie_data.s_not_applicable
	        }, {
	            name: 'Resolved',
		        color: '#609828',
	            y: reports_state_pie_data.s_resolved
	        }, {
	            name: 'Spam',
		        color: '#555555',
	            y: reports_state_pie_data.s_spam
	        }]
	    }],
	    mine: [{
	    	reload: function(){
		    	$.post( 'ajax.php', {'_a':'graph-reload','graph':'reports-state-pie'}, function(data) {
			        data = jQuery.parseJSON( data );
			        reports_state_pie.series[0].setData([
						[ data.s_new ],
						[ data.s_triaged ],
						[ data.s_duplicate ],
						[ data.s_informative ],
						[ data.s_not_applicable ],
						[ data.s_resolved ],
						[ data.s_spam ]
					]);
        		});
		    }
	    }]
	});
</script>
