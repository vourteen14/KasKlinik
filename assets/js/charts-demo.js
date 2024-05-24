'use strict';

window.chartColors = {
	green: '#75c181',
	blue: '#5b99ea',
	gray: '#a9b5c9',
	text: '#252930',
	border: '#e7e9ed'
};

var randomDataPoint = function(){ return Math.round(Math.random()*100)};
const dates = [];
const currentDate = new Date();

function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

dates.push(formatDate(currentDate));

for (let i = 1; i <= 6; i++) {
    const pastDate = new Date();
    pastDate.setDate(currentDate.getDate() - i);
    dates.push(formatDate(pastDate));
}

dates.forEach(date => console.log(date));

var barChartConfig = {
	type: 'bar',

	data: {
		labels: dates,
		datasets: [{
			label: 'Pengeluaran',
			backgroundColor: "rgba(117,193,129,0.8)", 
			hoverBackgroundColor: "rgba(117,193,129,1)",
			
			
			data: [
				randomDataPoint(),
				randomDataPoint(),
				randomDataPoint(),
				randomDataPoint(),
				randomDataPoint(),
				randomDataPoint(),
				randomDataPoint()
			]
		}, 
		{
			label: 'Penerimaan',
			backgroundColor: "rgba(91,153,234,0.8)", 
			hoverBackgroundColor: "rgba(91,153,234,1)",
			
			
			data: [
				randomDataPoint(),
				randomDataPoint(),
				randomDataPoint(),
				randomDataPoint(),
				randomDataPoint(),
				randomDataPoint(),
				randomDataPoint()
			]
		}
		]
	},
	options: {
		responsive: true,
		legend: {
			position: 'bottom',
			align: 'end',
		},

		tooltips: {
			mode: 'index',
			intersect: false,
			titleMarginBottom: 10,
			bodySpacing: 10,
			xPadding: 16,
			yPadding: 16,
			borderColor: window.chartColors.border,
			borderWidth: 1,
			backgroundColor: '#fff',
			bodyFontColor: window.chartColors.text,
			titleFontColor: window.chartColors.text,
			callbacks: {
                label: function(tooltipItem, data) {	                 
	                return tooltipItem.value + '%';   
                }
            },
			

		},
		scales: {
			xAxes: [{
				display: true,
				gridLines: {
					drawBorder: false,
					color: window.chartColors.border,
				},

			}],
			yAxes: [{
				display: true,
				gridLines: {
					drawBorder: false,
					color: window.chartColors.borders,
				},
				ticks: {
		            beginAtZero: true,
		            userCallback: function(value, index, values) {
		                return value + '%';  
		            }
		        },

				
			}]
		}
		
	}
}

window.addEventListener('load', function(){
	
	var barChart = document.getElementById('chart-bar').getContext('2d');
	window.myBar = new Chart(barChart, barChartConfig);

});	
	
