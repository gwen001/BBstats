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

<div id="reports-severity-pie"></div>

<script>
	var reports_severity_pie_data = <?php echo Statistics::reports_severity_pie( $db ) ?>;
	var reports_severity_pie = Highcharts.chart('reports-severity-pie', {
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
	        text: 'Severity repartition'
	    },
	    subtitle: {
	        text: 'For a total of '+reports_severity_pie_data.total+' reports'
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
							setFilterTerm( 'r:'+term );
						}
					}
				}
		    }
	    },
	    series: [{
	        colorByPoint: true,
	        data: [{
	            name: 'None',
		        color: '#0278b8',
	            y: reports_severity_pie_data.none
	        },{
	            name: 'Low',
		        color: '#5eae00',
	            y: reports_severity_pie_data.low
	        }, {
	            name: 'Medium',
		        color: '#f0ad4e',
	            y: reports_severity_pie_data.medium
	        }, {
	            name: 'High',
		        color: '#ff6900',
	            y: reports_severity_pie_data.high
	        }, {
	            name: 'Critical',
		        color: '#d13535',
	            y: reports_severity_pie_data.critical
	        }]
	    }],
	    mine: [{
	    	reload: function(){
		    	$.post( 'ajax.php', {'_a':'graph-reload','graph':'reports-severity-pie'}, function(data) {
			        data = jQuery.parseJSON( data );
			        reports_severity_pie.series[0].setData([
						[ data.none ],
						[ data.low ],
						[ data.medium ],
						[ data.high ],
						[ data.critical ]
					]);
        		});
		    }
	    }]
	});
</script>
