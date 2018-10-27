<?php

require_once( 'config.php' );

$db = Database::getInstance();
if( !$db->load(DATABASE_FILE) ) {
	exit( 'Cannot load database, you should run the grabber first!' );
}

?>

<div class="col-md-12">
	<div id="tags-evolution"></div>
</div>

<script>
	var tags_evolution_data = <?php echo Statistics::tags_evolution( $db ); ?>;
	var tags_evolution = Highcharts.chart('tags-evolution', {
	    credits: {
	        enabled: false
	    },
	    chart: {
	        type: 'line'
	    },
	    title: {
	        text: 'Top 5 tags evolution'
	    },
	    xAxis: {
	        categories: tags_evolution_data.categories
	    },
	    yAxis: [{
	        title: {
	            text: 'n report'
	        }
	    }],
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
	    tooltip: {
	        shared: true
	    },
	    series: [{
	        name: tags_evolution_data.tags[0],
	        data: tags_evolution_data.top_datas[0],
			color: '#d13535',
	    }, {
	        name: tags_evolution_data.tags[1],
	        data: tags_evolution_data.top_datas[1],
			color: '#ff6900',
	    }, {
	        name: tags_evolution_data.tags[2],
	        data: tags_evolution_data.top_datas[2],
			color: '#f0ad4e',
	    }, {
	        name: tags_evolution_data.tags[3],
	        data: tags_evolution_data.top_datas[3],
			color: '#5eae00',
	    }, {
	        name: tags_evolution_data.tags[4],
	        data: tags_evolution_data.top_datas[4],
			color: '#0278b8',
	    }]
	});
</script>
