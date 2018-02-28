<?php

require_once( 'config.php' );

$db = Database::getInstance();
if( !$db->load(DATABASE_FILE) ) {
	exit( 'Cannot load database, you should run the grabber first!' );
}

?>

<div class="col-md-12">
	<div id="reports-rating"></div>
</div>

<script>
	var reports_rating_data = <?php echo Statistics::reports_rating( $db ) ?>;
	var reports_rating = Highcharts.chart('reports-rating', {
	    credits: {
	        enabled: false
	    },
	    chart: {
	        type: 'column'
	    },
	    title: {
	        text: 'Reports per month per rating'
	    },
	    xAxis: {
	        categories: reports_rating_data.categories
	    },
	    yAxis: [{
	        min: 0,
	        title: {
	            text: 'n report'
	        },
	        stackLabels: {
	            enabled: true,
	            style: {
	                fontWeight: 'bold',
	                cursor: 'pointer',
	                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
	            }
	        }
	    }, {
	        title: {
	            text: 'reputation'
	        },
	        opposite: true
	    }],
	    tooltip: {
	        headerFormat: '<table><tr><td><span style="font-size:12px;color:#000;"><b>{point.key}</b></span></td><td align="right"></td></tr><tr><td><span style="font-size:12px;color:#000;">Total:</span></td><td align="right"><span style="font-size:12px;color:#000;"><b>{point.total}</b></span></td></tr>',
	        pointFormat: '<tr><td style="font-size:12px;color:{series.color};padding:0;">{series.name}: </td>' + '<td style="font-size:12px;padding:0;" align="right"><b>{point.y}</b></td></tr>',
	        footerFormat: '</table>',
	        shared: true,
	        useHTML: true
	    },
	    plotOptions: {
	        column: {
	            stacking: 'normal',
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
	        name: 'Unrated',
	        data: reports_rating_data.p0,
	        color: '#5c5c61'
	    },{
	        name: 'P1',
	        data: reports_rating_data.p1,
	        color: '#d13535'
	    }, {
	        name: 'P2',
	        data: reports_rating_data.p2,
	        color: '#ff6900'
	    }, {
	        name: 'P3',
	        data: reports_rating_data.p3,
	        color: '#f0ad4e'
	    }, {
	        name: 'P4',
	        data: reports_rating_data.p4,
	        color: '#5eae00'
	    }, {
	        name: 'P5',
	        data: reports_rating_data.p5,
	        color: '#0278b8'
	    }, {
	        type: 'spline',
	        name: 'Average report',
	        data: reports_rating_data.average_report,
	        color: '#c124bf',
	        marker: {
	            enabled: false/*,
	            lineWidth: 2,
	            lineColor: Highcharts.getOptions().colors[3],
	            fillColor: 'white'*/
	        }
	    }, {
	        type: 'spline',
	        name: 'Average rate',
	        data: reports_rating_data.average_rate,
	        color: '#f659ee',
	        marker: {
	            enabled: false/*,
	            lineWidth: 2,
	            lineColor: Highcharts.getOptions().colors[3],
	            fillColor: 'white'*/
	        }
	    }, {
	        yAxis: 1,
	        type: 'spline',
	        name: 'Reputation',
	        data: reports_rating_data.reputation,
	        color: Highcharts.getOptions().colors[2],
	        marker: {
	            enabled: false/*,
	            lineWidth: 2,
	            lineColor: Highcharts.getOptions().colors[3],
	            fillColor: 'white'*/
	        }
	    }],
	    mine: [{
	    	reload: function(){
		    	$.post( 'ajax.php', {'_a':'graph-reload','graph':'reports-rating'}, function(data) {
			        data = jQuery.parseJSON( data );
        			reports_rating.series[0].setData( data.p0 );
        			reports_rating.series[1].setData( data.p1 );
        			reports_rating.series[2].setData( data.p2 );
        			reports_rating.series[3].setData( data.p3 );
        			reports_rating.series[4].setData( data.p4 );
        			reports_rating.series[5].setData( data.p5 );
        			reports_rating.series[6].setData( data.average_report );
        			reports_rating.series[7].setData( data.average_rate );
        			reports_rating.series[8].setData( data.reputation );
        		});
		    }
	    }]
	});
</script>
