<?php

require_once( 'config.php' );

$db = Database::getInstance();
if( !$db->load(DATABASE_FILE) ) {
	exit( 'Cannot load database, you should run the grabber first!' );
}

?>

<div id="reports-program-pie"></div>

<script>
	var reports_program_pie_data = <?php echo Statistics::reports_program_pie( $db ) ?>;
	var reports_program_pie = Highcharts.chart('reports-program-pie', {
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
	        text: 'Program repartition'
	    },
	    subtitle: {
	        text: 'For a total of '+reports_program_pie_data.total+' reports'
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
	        	visible: (reports_program_pie_data.programs.length>=2) ? true : false,
	            name: reports_program_pie_data.programs[1],
	            y: reports_program_pie_data.values[1]
	        }, {
		        color: '#434348',
	            name: reports_program_pie_data.programs[0],
	            y: reports_program_pie_data.values[0]
	        }, {
	        	visible: (reports_program_pie_data.programs.length>=3) ? true : false,
	            name: reports_program_pie_data.programs[2],
	            y: reports_program_pie_data.values[2]
	        }, {
	        	visible: (reports_program_pie_data.programs.length>=4) ? true : false,
	            name: reports_program_pie_data.programs[3],
	            y: reports_program_pie_data.values[3]
	        }, {
	        	visible: (reports_program_pie_data.programs.length>=5) ? true : false,
	            name: reports_program_pie_data.programs[4],
	            y: reports_program_pie_data.values[4]
	        }, {
	        	visible: (reports_program_pie_data.programs.length>=6) ? true : false,
	            name: reports_program_pie_data.programs[5],
	            y: reports_program_pie_data.values[5]
	        }]
	    }],
	    mine: [{
	    	reload: function(){
		    	$.post( 'ajax.php', {'_a':'graph-reload','graph':'reports-program-pie'}, function(data) {
			        data = jQuery.parseJSON( data );
			        reports_rating_pie.series[0].setData([
						[data.p0],
						[data.p1],
						[data.p2],
						[data.p3],
						[data.p4],
						[data.p5]
					]);
        		});
		    }
	    }]
	});
</script>
