<?php

require_once( 'config.php' );

$db = Database::getInstance();
if( !$db->load(DATABASE_FILE) ) {
	exit( 'Cannot load database, you should run the grabber first!' );
}

?>

<div id="reports-platform-pie"></div>

<script>
	var reports_platform_pie_data = <?php echo Statistics::reports_platform_pie( $db ) ?>;
	var reports_platform_pie = Highcharts.chart('reports-platform-pie', {
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
	        text: 'Platform repartition'
	    },
	    subtitle: {
	        text: 'For a total of '+reports_platform_pie_data.total+' reports'
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
							if( this.name != 'Other' ) {
								setFilterTerm( this.name );
							}
						}
					}
				}
		    }
	    },
	    series: [{
	        colorByPoint: true,
	        data: [{
	        	visible: (reports_platform_pie_data.values[0] > 0) ? true : false, // other
	            name: reports_platform_pie_data.platforms[0],
	            y: reports_platform_pie_data.values[0]
	        }, {
	        	visible: (reports_platform_pie_data.platforms.length>=2) ? true : false,
	            name: reports_platform_pie_data.platforms[1],
	            y: reports_platform_pie_data.values[1]
	        }, {
	        	visible: (reports_platform_pie_data.platforms.length>=3) ? true : false,
	            name: reports_platform_pie_data.platforms[2],
	            y: reports_platform_pie_data.values[2]
	        }, {
	        	visible: (reports_platform_pie_data.platforms.length>=4) ? true : false,
	            name: reports_platform_pie_data.platforms[3],
	            y: reports_platform_pie_data.values[3]
	        }, {
	        	visible: (reports_platform_pie_data.platforms.length>=5) ? true : false,
	            name: reports_platform_pie_data.platforms[4],
	            y: reports_platform_pie_data.values[4]
	        }, {
	        	visible: (reports_platform_pie_data.platforms.length>=6) ? true : false,
	            name: reports_platform_pie_data.platforms[5],
	            y: reports_platform_pie_data.values[5]
	        }]
	    }],
	    mine: [{
	    	reload: function(){
		    	$.post( 'ajax.php', {'_a':'graph-reload','graph':'reports-platform-pie'}, function(data) {
			        data = jQuery.parseJSON( data );
			        reports_platform_pie.series[0].setData([
						[ data.values[0] ],
						[ data.values[1] ],
						[ data.values[2] ],
						[ data.values[3] ],
						[ data.values[4] ],
						[ data.values[5] ]
					]);
        		});
		    }
	    }]
	});
</script>
