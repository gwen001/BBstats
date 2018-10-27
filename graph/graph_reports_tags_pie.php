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

<div id="reports-tag-pie"></div>

<script>
	var reports_tag_pie_data = <?php echo Statistics::reports_tags_pie( $db ) ?>;
	var reports_tag_pie = Highcharts.chart('reports-tag-pie', {
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
	        text: 'Tags repartition'
	    },
	    subtitle: {
	        text: 'For a total of '+reports_tag_pie_data.total+' reports'
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
	        	visible: (reports_tag_pie_data.tags.length>=3) ? true : false,
	            name: reports_tag_pie_data.tags[2],
	            y: reports_tag_pie_data.values[2]
	        }, {
	        	visible: (reports_tag_pie_data.values[0] > 0) ? true : false, // other
	            name: reports_tag_pie_data.tags[0],
	            y: reports_tag_pie_data.values[0]
	        }, {
	        	visible: (reports_tag_pie_data.tags.length>=4) ? true : false,
	            name: reports_tag_pie_data.tags[3],
	            y: reports_tag_pie_data.values[3]
	        }, {
	        	visible: (reports_tag_pie_data.tags.length>=5) ? true : false,
	            name: reports_tag_pie_data.tags[4],
	            y: reports_tag_pie_data.values[4]
	        }, {
	        	visible: (reports_tag_pie_data.tags.length>=6) ? true : false,
	            name: reports_tag_pie_data.tags[5],
	            y: reports_tag_pie_data.values[5]
	        }, {
	        	visible: (reports_tag_pie_data.tags.length>=7) ? true : false,
	            name: reports_tag_pie_data.tags[6],
	            y: reports_tag_pie_data.values[6]
	        }, {
		        color: '#434348', // untaged
	        	visible: (reports_tag_pie_data.values[1] > 0) ? true : false,
	            name: reports_tag_pie_data.tags[1],
	            y: reports_tag_pie_data.values[1]
	        }]
	    }],
	    mine: [{
	    	reload: function(){
		    	$.post( 'ajax.php', {'_a':'graph-reload','graph':'reports-tag-pie'}, function(data) {
			        data = jQuery.parseJSON( data );
			        reports_tag_pie.series[0].setData([
						[ data.values[2] ],
						[ data.values[0] ],
						[ data.values[3] ],
						[ data.values[4] ],
						[ data.values[5] ],
						[ data.values[6] ],
						[ data.values[1] ]
					]);
        		});
		    }
	    }]
	});
</script>
