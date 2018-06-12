var dataFromServer,chartType;
var ctx = $("#zoneTrend");
				var utils = Samples.utils;
				var myChart;
				
				var optionsGraphic = {
					maintainAspectRatio: true,
					spanGaps: true,
					elements: {
						line: {
							tension: 0.000001
						}
					},
					plugins: {
						filler: {
							propagate: false
						}
					},
					scales: {
						xAxes: [{
							ticks: {
								autoSkip: false,
								maxRotation: 0
							}
						}]
					}
				};
				
				function addData(chart, label, data) {
				    chart.data.labels.push(label);
				    chart.data.datasets.forEach((dataset) => {
				        dataset.data.push(data);
				    });
				    chart.update();
				}
var dataFromServer;			
var googleMapsInclude = null,googleMapsId,googleMapsObj;
var gomaCoordinates = {lat: -1.681142,lng:29.226776};
var differentCoordinates = [
	{
		lat: -1.669177,
		lng: 29.191017,
		txt:"Governor office, Goma<br/>CO2: 450 ppm<br/>To: 22&deg;C",
	},
	{
		lat:-1.527251,
		lng:29.247183,
		txt:"Nyiragongo Volcano<br/>CO2: 800 ppm<br/>To: 5&deg;C",
	}/*,
	{
		lat:-1.664323,
		lng:29.184115,
		txt:"ULPGL, Universit&eacute; Libre des Pays des Grand-Lacs<br/>CO2: 490 ppm<br/>To: 24&deg;C",
	}*/
];

function loadGoogleMaps(googleMapsDomId,centerCoord,callback){
	if(googleMapsInclude){
		centerCoord.lat = centerCoord.lat ? centerCoord.lat : 0.0;
		centerCoord.lng = centerCoord.lng ? centerCoord.lng : 0.0;
			
		googleMapsObj  = new google.maps.Map(document.getElementById(googleMapsDomId),{
			zoom:14,
			center:new google.maps.LatLng(centerCoord.lat,centerCoord.lng),
			mapTypeId: google.maps.MapTypeId.ROADMAP,
	            	disableDefaultUI: true,
	            	gestureHandling: 'greedy'
		});
		if(typeof callback == "function") callback(googleMapsObj);
	}else{
		$.getScript("http://maps.google.com/maps/api/js?key=AIzaSyBukURmSP_A3FRx_x9-HphIxeGOJn5KsWo", function () {
			centerCoord.lat = centerCoord.lat ? centerCoord.lat : 0.0;
			centerCoord.lng = centerCoord.lng ? centerCoord.lng : 0.0;
			
			googleMapsObj  = new google.maps.Map(document.getElementById(googleMapsDomId),{
				zoom:14,
				center:new google.maps.LatLng(centerCoord.lat,centerCoord.lng),
				mapTypeId: google.maps.MapTypeId.ROADMAP,
	            		disableDefaultUI: true,
	            		gestureHandling: 'greedy'
			});
			callback(googleMapsObj);
			
			googleMapsInclude = 1;
		},function(){
			alert("Was unable to contact maps");
		});
	}
	
}

function presentCoordinatesOnMaps(coordinates,mapsObj,callback){
	var coordinatesList,markerObj,infowindow;
	var bounds = new google.maps.LatLngBounds();
	var idElement;
	for(var i = 0;i<coordinates.length;i++){
		coordinatesList = new google.maps.LatLng(coordinates[i].lat,coordinates[i].lng);		
		markerObj = new google.maps.Marker({
			 position: coordinatesList,
			 map: mapsObj,
			 id:i
		});
		
		markerObj.addListener('click', function() {
			idElement = this.id;
         	if(coordinates[idElement].txt){
				infowindow = new google.maps.InfoWindow({
		          	content: coordinates[idElement].txt
		    	});
		    	infowindow.open(mapsObj, this);
			}
       });
		
		   
	    bounds.extend(coordinatesList);
	}
	if(typeof callback == "function") callback();
}

function initDifferentCO2Popups(){
	

	
	
	$("#co2TrendsPopup").on("popup:open",function(){
		
		
		
	});

}

function getData(dataObj,callback){
	if(dataObj){
		$.post("http://safegoma.nfinic.net/data.getter.php",dataObj,function(d){
			d = eval(d);
			if(typeof callback == "function") callback(d);
		});
	}
}

function displayNewDataOnChart(datasets,labels,type){
	myChart = new Chart(ctx, {
						type: type,
						data: {
							labels: labels,
							datasets: datasets
						},
						options: Chart.helpers.merge(optionsGraphic, {
							title: {
								text: 'Temperature / CO2 / Humidity',
								display: true
							}
						})
					});
					
					myChart.update();
}

function displayData(type){
	var d = dataFromServer;
	var co2Data=[],humidityData=[],temperatureData=[],labels=[];
			
			for(var i=0;i<d.length;i++){
				co2Data.push(d[i].co2 / 100);
				humidityData.push(d[i].humidity);
				temperatureData.push(d[i].temperature);
				labels.push(d[i].timest);
			}
			
			var datasets = [
								{
				 					label: 'ppm CO2 concentration',
									data: co2Data,
									backgroundColor: 'rgba(75, 192, 192, 0.2)',
									borderColor: 'rgba(75, 192, 192, 0.6)',
									fill: "start",
									borderWidth: 1
								},
								{
				 					label: 'humidity',
									data: humidityData,
									backgroundColor: 'rgba(19, 206, 36, 0.2)',
									borderColor: 'rgba(19, 206, 36, 0.6)',
									fill: "start",
									borderWidth: 1
								},
								{
				 					label: 'temperature',
									data: temperatureData,
									backgroundColor: 'rgba(214, 0, 0, 0.4)',
									borderColor: 'rgba(214, 0, 0, 0.6)',
									fill: "start",
									borderWidth: 1
								}
							];
			displayNewDataOnChart(datasets,labels,type);
}

$(function(){
	initDifferentCO2Popups();
	
	getData({ac:1,lastid:0,groupby:"hour"},function(d){
		console.log(d);
			dataFromServer = d;
			displayData('line');
		},function(){
			gdrAlert("Cannot access the internet","red");
		});
		
	$("#ligneDisplay").unbind('click').promise().done(function(){
			$(this).click(function(){
				displayData('line');
			});
		});
		
		$("#histogrammeDisplay").unbind('click').promise().done(function(){
			$(this).click(function(){
				displayData('bar');
			});
		});
		
		$("#sectorDisplay").unbind('click').promise().done(function(){
			$(this).click(function(){
				//otherChart();
				displayData('pie');
			});
		});
		
		$("#intervalPeriod").unbind("change").promise().done(function(){
			$(this).change(function(){
				//console.log($("#intervalPeriod").val());
				getData({ac:1,lastid:0,groupby:$("#intervalPeriod").val()},function(d){
					dataFromServer = d;
					displayData('line');
				},function(){
					gdrAlert("Cannot access the internet","red");
				});
			});
		});
		
		$("#refreshTrends").unbind("click").promise().done(function(){
			$(this).click(function(){
				getData({ac:1,lastid:0,groupby:$("#intervalPeriod").val()},function(d){
					dataFromServer = d;
					displayData('line');
				},function(){
					gdrAlert("Cannot access the internet","red");
				});
			});
		});
});
