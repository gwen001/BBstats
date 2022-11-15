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

<div class="col-md-12">
	<div id="program-evolution"></div>
	<div id="severity-legend">
		<table>
			<!-- <thead>
				<tr><th>Ranges</th></tr>
			</thead> -->
			<tbody>
			<tr>
					<td class="severity-none">None:</td>
					<td>0$</td>
				</tr>
				<tr>
					<td class="severity-low">Low:</td>
					<td>1$ - <?php echo $db->getBountyRange()['medium']-1; ?>$</td>
				</tr>
				<tr>
					<td class="severity-medium">Medium:</td>
					<td><?php echo $db->getBountyRange()['medium']; ?>$ - <?php echo $db->getBountyRange()['high']-1; ?>$</td>
				</tr>
				<tr>
					<td class="severity-high">High:</td>
					<td><?php echo $db->getBountyRange()['high']; ?>$ - <?php echo $db->getBountyRange()['critical']-1; ?>$</td>
				</tr>
				<tr>
					<td class="severity-critical">Critical:</td>
					<td><?php echo $db->getBountyRange()['critical']; ?>$ - &infin;</td>
				</tr>
			<tbody>
		</table>
	</div>
</div>


<script>
	var program_evolution_data = <?php echo Statistics::program_evolution( $db ) ?>;
	var program_evolution = Highcharts.chart('program-evolution', {
	    credits: {
	        enabled: false
	    },
	    chart: {
	        type: 'column'
	    },
	    title: {
	        text: 'Reports evolution'
	    },
	    xAxis: {
			categories: program_evolution_data.categories
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
		    },
			pie: {
				cursor: 'pointer',
				dataLabels: {
					enabled: true,
					format: '<b>{point.name}</b>: {point.percentage:.1f}% ({point.y})',
					style: {
						color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
					}
				}
			}
		},
	    series: [{
	        name: 'Critical',
	        data: program_evolution_data.critical,
	        color: '#d13535',
	    },{
	        name: 'High',
	        data: program_evolution_data.high,
	        color: '#ff6900'
	    },{
	        name: 'Medium',
	        data: program_evolution_data.medium,
	        color: '#f0ad4e'
	    },{
	        name: 'Low',
	        data: program_evolution_data.low,
	        color: '#5eae00'
	    },{
	        name: 'None',
	        data: program_evolution_data.none,
	        color: '#0278b8'
	    }, {
	        type: 'spline',
	        name: 'Average report',
	        data: program_evolution_data.average_report,
	        color: '#c124bf',
	        marker: {
	            enabled: false
	        }
		}/*, {
			type: 'pie',
			name: 'Repartition',
			showInLegend: false,
			states: {
                hover: {
                    enabled: false
                }
            },
			data: [{
				name: 'Critical',
				y: program_evolution_data.cnt.critical,
				color: '#d13535'
			}, {
				name: 'High',
				y: program_evolution_data.cnt.high,
				color: '#ff6900'
			}, {
				name: 'Medium',
				y: program_evolution_data.cnt.medium,
				color: '#f0ad4e'
			}, {
				name: 'Low',
				y: program_evolution_data.cnt.low,
				color: '#5eae00'
			}, {
				name: 'None',
				y: program_evolution_data.cnt.none,
				color: '#0278b8'
			}],
			center: ['180px', '50px'],
			size: 100,
			showInLegend: false
		}*/]
	});
</script>
