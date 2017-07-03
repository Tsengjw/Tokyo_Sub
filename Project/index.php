<!DOCTYPE html>
<html>
<head>
<title>東京地下鐵路線查詢系統</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta charset="utf-8">

	<style type="text/css">
	.banner {
		text-align: center;
		clear: both;
		font-family: "標楷體";
		font-size: 24px;
	}
	.content {
		height: auto;
		width: auto;
	}
	.footer {
		text-align: right;
	}
	.h3 {
		font-family: 標楷體;
	}
	</style>

	<style>
	/* Always set the map height explicitly to define the size of the div
	* element that contains the map. */
	#map {
		height: 100%;
	}
	/* Optional: Makes the sample page fill the window. */
	html, body {
		height: 100%;
		margin: 0;
		padding: 0;
	}
	</style>
</head>

<body>
<div class="banner">東京地下鐵路線查詢系統</div>

	<select id="line">
		<option value="Asakusa Line">淺草線</option>
		<option value="Mita Line">三田線</option>
		<option value="Shinjuku Line">新宿線</option>
		<option value="Oedo Line">大江戶線</option>
		<option value="Ginza Line">銀座線</option>
		<option value="Marunouchi Line">丸之內線</option>
		<option value="Marunouchi Line Branch Line">丸之內支線</option>
		<option value="Hibiya Line">日比谷線</option>
		<option value="Tozia Line">東西線</option>
		<option value="Chiyoda Line">千代田線</option>
		<option value="Yurakucho Line">有樂町線</option>
		<option value="Hanzomon Line">半藏門線</option>
		<option value="Namboku Line">南北線</option>
		<option value="Fukutoshin Line">副都心線</option>
		<option value="Yamanote Line">山手線</option>
		<option value="Rinkai">臨海線</option>
		<option value="Yurikamome">臨海線(百合海鷗號)</option>
	</select>

	<div>
		<label for="query"></label>
		<textarea name="query" cols="100" id="query"></textarea>

		<button id="query_btn">查詢</button>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

	<div id="map" style="height: 650px"></div>
	<script>
		var map;
		function initMap(){
			map = new google.maps.Map(document.getElementById('map'), 
			{
				center: {lat: 35.7092029, lng: 139.7316575}, 
				zoom: 10
			});
		}

</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5hBnebqh2UkLiZXt4CulxbS-R1pNemrg&callback=initMap"></script>
	<script type="text/javascript">
		var iconBase = 'icon/';
		var marker= [];
		var markers = [];

		var icons = {
			// 淺草線
			'Asakusa Line': iconBase + 'Subway_TokyoAsakusa.png',
			// 三田線
			'Mita Line': iconBase + 'Subway_TokyoMita.png',
			// 新宿線
			'Shinjuku Line': iconBase + 'Subway_TokyoShinjuku.png',
			// 大江戶線
			'Oedo Line': iconBase + 'Subway_TokyoOedo.png',
			// 銀座線
			'Ginza Line': iconBase + 'Subway_TokyoGinza.png',
			// 丸之內線
			'Marunouchi Line': iconBase + 'Subway_TokyoMarunouchi.png',
			// 丸之內支線
			'Marunouchi Line Branch Line': iconBase + 'Subway_TokyoMarunouchi_b.png',
			// 日比谷線
			'Hibiya Line': iconBase + 'Subway_TokyoHibiya.png',
			// 東西線
			'Tozia Line': iconBase + 'Subway_TokyoTozai.png',
			// 千代田線
			'Chiyoda Line': iconBase + 'Subway_TokyoChiyoda.png',
			// 有樂町線
			'Yurakucho Line': iconBase + 'Subway_TokyoYurakucho.png',
			// 半藏門線
			'Hanzomon Line': iconBase + 'Subway_TokyoHanzomon.png',
			// 南北線
			'Namboku Line': iconBase + 'Subway_TokyoNamboku.png',
			// 副都心線
			'Fukutoshin Line': iconBase + 'Subway_TokyoFukutoshin.png',
			// 山手線
			'Yamanote Line': iconBase + 'JR_JY_line_symbol.png',
			// 臨海線
			'Rinkai': iconBase + 'Rinkai_Line_symbol.png',
			// 臨海線(百合海鷗號)
			'Yurikamome': iconBase + 'Yurikamome_line_symbol.png'
		}

		$(function() {	
			var line = 'Asakusa Line';
			get_line(line);

			$('#line').change(function(){
				deleteMarkers();
				get_line($(this).val());
			});

			$('#query_btn').click(function(){
				deleteMarkers();
				get_query($('#query').val());
			});
		});

		function get_line(line){
			
			$.ajax({
				url: "getData.php?LineName=" + line,
				type: "GET",
				dataType: "json",
				success: function(Jdata) 
				{	
					$.each(Jdata, function(index, value) {
						var position = new google.maps.LatLng(parseFloat(value['n.latitude']), parseFloat(value['n.longitade']));
						

						var content ='<h3>' + value['n.line_Name'] + ' | ' + '站名：' + value['n.station_Name'] + '</h3>' + 
									  '經度:' + value['n.longitade'] + ' | ' + 
									  '緯度:' + value['n.latitude'] + '<br/>';
						var infowindow = new google.maps.InfoWindow({
							content:content
						});

						var marker = new google.maps.Marker({
							position: position,
							icon: icons[line],
							map: map
						});

						marker.addListener('click', function() {
							infowindow.open(map, marker);
						});

						markers.push(marker);
					});
				},

				error: function() {
					console.warn("ERROR!!!");
				}

			});
		}

		function get_query(query){
			$.ajax({
				url: "QueryData.php?query="+query,
				type: "GET",
				dataType: "json",
				success: function(Jdata) 
				{	
					$.each(Jdata, function(index, value) {

						var position = new google.maps.LatLng(parseFloat(value['lat']), parseFloat(value['lon']));
						var content ='<h3>'+ value['L_Name'] + ' | ' + '站名：' + value['S_Name'] + '</h3>' + 
									  '經度:' + value['lon'] + ' | ' + 
									  '緯度:' + value['lat'] + '<br/>';
						var infowindow = new google.maps.InfoWindow({
							content:content
						});

						var marker = new google.maps.Marker({
							position: position,
							icon: icons[value['L_En']],
							title: value['S_Name'],
							map: map
						});

						marker.addListener('click', function() {
							infowindow.open(map, marker);
						});
						markers.push(marker);
					});
				},

				error: function() {
					alert("ERROR!!!");
				}
			});
		}

		// Sets the map on all markers in the array.
		function setMapOnAll(map) {
			for (var i = 0; i < markers.length; i++) {
				markers[i].setMap(map);
			}
		}

		// Removes the markers from the map, but keeps them in the array.
		function clearMarkers() {
			setMapOnAll(null);		
		}

		
		// Deletes all markers in the array by removing references to them.
		function deleteMarkers() {
			clearMarkers();
			markers = [];
		}

</script>

<div class="footer"> Copyright © 2017 Tsengjw X AhWei All rights reserved.</div>
</body>
</html>