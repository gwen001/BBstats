<?php

require_once( 'config.php' );

$db = Database::getInstance();
if( !$db->load(DATABASE_FILE) ) {
	exit( 'Cannot load database, you should run the grabber first!' );
}

?>

<div id="reports-rating-pie"></div>

<script>
	var reports_rating_pie_data = <?php echo Statistics::reports_rating_pie( $db ) ?>;
	var reports_rating_pie = Highcharts.chart('reports-rating-pie', {
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
	        text: 'Ratings repartition'
	    },
	    subtitle: {
	        text: 'For a total of '+reports_rating_pie_data.total+' reports'
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
							setFilterTerm( term );
						}
					}
				}
		    }
	    },
	    series: [{
	        colorByPoint: true,
	        data: [{
	            name: 'Unrated',
		        color: '#434348',
	            y: reports_rating_pie_data.p0
	        }, {
	            name: 'P1',
		        color: '#d13535',
	            y: reports_rating_pie_data.p1
	        }, {
	            name: 'P2',
		        color: '#ff6900',
	            y: reports_rating_pie_data.p2
	        }, {
	            name: 'P3',
		        color: '#f0ad4e',
	            y: reports_rating_pie_data.p3
	        }, {
	            name: 'P4',
		        color: '#5eae00',
	            y: reports_rating_pie_data.p4
	        }, {
	            name: 'P5',
		        color: '#0278b8',
	            y: reports_rating_pie_data.p5
	        }]
	    }],
	    mine: [{
	    	reload: function(){
		    	$.post( 'ajax.php', {'_a':'graph-reload','graph':'reports-rating-pie'}, function(data) {
			        data = jQuery.parseJSON( data );
			        reports_rating_pie.series[0].setData([
						[ data.p0 ],
						[ data.p1 ],
						[ data.p2 ],
						[ data.p3 ],
						[ data.p4 ],
						[ data.p5 ]
					]);
        		});
		    }
	    }]
	});
</script>
