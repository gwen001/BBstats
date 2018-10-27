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
	        text: 'Program evolution'
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
	    }, {
	        title: {
	            text: '$$$'
	        },
	        opposite: true
	    }],
	    tooltip: {
	        headerFormat: '<table><tr><td><span style="font-size:12px;color:#000;"><b>{point.key}</b></span></td><td align="right"></td></tr>',
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
	        name: 'Unrated',
	        data: program_evolution_data.p0,
	        color: '#5c5c61'
	    },{
	        name: 'P1',
	        data: program_evolution_data.p1,
	        color: '#d13535'
	    }, {
	        name: 'P2',
	        data: program_evolution_data.p2,
	        color: '#ff6900'
	    }, {
	        name: 'P3',
	        data: program_evolution_data.p3,
	        color: '#f0ad4e'
	    }, {
	        name: 'P4',
	        data: program_evolution_data.p4,
	        color: '#5eae00'
	    }, {
	        name: 'P5',
	        data: program_evolution_data.p5,
	        color: '#0278b8'
	    },{
	        yAxis: 1,
	        type: 'spline',
	        name: 'Bounties',
	        data: program_evolution_data.bounties,
	        color: '#c124bf',
	        tooltip: {
            	valueSuffix: ' $'
        	},
	        marker: {
	            enabled: false
	        }
	    }, {
	        yAxis: 1,
	        type: 'spline',
	        name: 'Average bounties',
	        data: program_evolution_data.average_bounties,
	        color: '#f659ee',
	        tooltip: {
            	valueSuffix: ' $'
        	},
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
