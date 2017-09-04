<!DOCTYPE html>
<html>
  <head>
  <?php include("../navbar.php"); ?>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>雲端地理資訊系統（Cloud-based GIS) :Google Map截圖</title>
		<link rel="shortcut icon" href="../css/favicon.ico">
		<script type="text/javascript" src="./Script/utmconv.js"></script>
		<script>
		var stralarm = new Array("|","\\","/","?","*","\"",":","<",">","!");
		var height= 640;
		var width= 640;
		var maptype= "roadmap";
		var x = document.createElement("STYLE");
		var t = document.createTextNode("#map {height: "+height+"px;width:"+width+"px;}");
		x.appendChild(t);
		document.head.appendChild(x);
		</script>
  </head>
  <body>
<form>
<tr>
<td>影像高度: <input type="number" style="width:50px" id="height" min="50" max="640"value="640"></td>
  <td>影像寬度: <input type="number" style="width:50px" id="width" min="50" max="640" value="640"></td>
 </tr>

<tr>
  <td>
	地圖類型:
	<select id="maptype">		
		<option value='1'>ROADMAP (normal, default 2D map)</option>
		<option value='2'>SATELLITE (photographic map)</option>
		<option value='3'>HYBRID (photographic map + roads and city names)</option>
		<option value='4'>TERRAIN</option>
	</select>
  </td>
</tr>

<tr><td><input type="button" onclick="getOption()" value="確認提交"></td>
	<br>
	
	<table width="800px">
	<td><h3>設定中心位置</h3></td>	
	<tr>	
			<td><input type="radio" checked name="myRadios" onclick='chang_model(this)' value="1" /> 
			<a id="address_temp">地址:<input type="text"   style="width:300px" id="adress" value="雲林縣虎尾鎮"></a></td>		
			<td><input type="radio" name="myRadios" onclick='chang_model(this)'  value="2" />
			<td> 緯度:  <a id="lat_temp"><input type="text" disabled style="width:50px" id="latitude" value="23.5"></a></td>
			<td> 經度: <a id="lon_temp"><input type="text" disabled style="width:50px" id="longitude" value="121"></a></td>	
			<td><input type="button" onclick="change_Center()" value="更改中心點"></td>  		
			    
	</tr>
	<td><h1>Google Map demo</h1></td>
	</table>
	<td>左下(西南方)緯度:<input type="text" style="width:150px" id="left_lat" value=""></td><br>
	<td>左下(西南方)經度:<input type="text" style="width:150px" id="left_lon" value=""></td><br>
	<td>右上(東北方)緯度:<input type="text" style="width:150px" id="right_lat" value=""></td><br>
	<td>右上(東北方)經度:<input type="text" style="width:150px" id="right_lon" value=""></td><br>
	<td>標示目前地圖左上及右下的經緯度<input type="button" onclick="fitbounds()" value="fitbounds"></td>
	<td><input type="button" onclick="deleteMarkers1()" value="刪除"></td>
	<br>
	</tr>
<table width="900px">
<tr>
	<td style="width:400px">中心點經緯度:<a id="center_latlon" value="123">123</a><font color="red">(緯度,經度)</font></td><tr>
	<td style="width:400px">左下標記點經緯度:<a id="left_latlon" value="123">(0,0)</a><font color="red">(緯度,經度)</font></td><tr>
	<td style="width:400px">右上標記點經緯度:<a id="right_latlon" value="123">(0,0)</a><font color="red">(緯度,經度)</font></td><tr>
	<td><a id="zoom_content">zoom(Range:1~22)</a>: <input onchange="zoom_change()" type="number" min="1" max="22"   id="zoom" value="13"></td>	
</tr>
</table>


</form>
<table>
<tr>
<td><div id="map"></div></td>
<td><input onclick="screenshot()" type="button" style="height:100px;width:100px"   id="zoom" value="截圖"></td>
</tr>
</table>
    
	
    <script>
	var temp=1;
	var longitude =120.90051459035644;
	var latitude = 23.554465021169932;
	var zoom = 7;
	var map ;
	var markers = [];
	var center_mark=[];
	function fitbounds()
	{
		var left_latlon = new google.maps.LatLng(document.getElementById('left_lat').value, document.getElementById('left_lon').value);
		var right_latlon = new google.maps.LatLng(document.getElementById('right_lat').value, document.getElementById('right_lon').value);
		var bounds = new google.maps.LatLngBounds();
		document.getElementById("left_latlon").innerHTML=left_latlon;
		document.getElementById("right_latlon").innerHTML=right_latlon;
		deleteMarkers();
		var marker = new google.maps.Marker({
        position: left_latlon,
        map: map
		});
		markers.push(marker);
		var marker = new google.maps.Marker({
			position: right_latlon,
			map: map
		});
		markers.push(marker);
		bounds.extend(right_latlon);
		bounds.extend(left_latlon);
		map.fitBounds(bounds);
		getMapinfo();
		
	}
	function zoom_change(){
		zoom=document.getElementById("zoom").value;
		zoom= Number(zoom);
		map.setZoom(zoom);
	}
	function setMapOnAll(map) {
        for (var i = 0; i < markers.length; i++) {
          markers[i].setMap(map);
        }
      }
	function clearMarkers() {
        setMapOnAll(null);
      }
      // Deletes all markers in the array by removing references to them.
      function deleteMarkers() {
        clearMarkers();
        markers = [];
      }
	  function deleteMarkers1() {
        clearMarkers();
        markers = [];
		document.getElementById("left_latlon").innerHTML="(0,0)";
		document.getElementById("right_latlon").innerHTML="(0,0)";
      }
	function chang_model(myRadio)
	{    
    temp=myRadio.value;
	if(temp==1)
	{
		document.getElementById("address_temp").innerHTML='地址:<input type="text"  style="width:300px" id="adress" value="雲林縣虎尾鎮">';
	    document.getElementById("lon_temp").innerHTML='<input type="text" disabled style="width:50px" id="longitude" value="121" >';
		document.getElementById("lat_temp").innerHTML='<input type="text" disabled style="width:50px" id="latitude"  value="23.5">';
	}
		
	else if(temp==2)
	{
		document.getElementById("address_temp").innerHTML='地址:<input type="text" disabled style="width:300px" id="adress" value="雲林縣虎尾鎮" >';
	    document.getElementById("lon_temp").innerHTML='<input type="text"  style="width:50px" id="longitude" value="121" >';
		document.getElementById("lat_temp").innerHTML='<input type="text"  style="width:50px" id="latitude" value="23.5" >';
	}
	}
	function change_Center()
	{
		 if(temp==1)
		 {
			var address = document.getElementById('adress').value;
			var geocoder = new google.maps.Geocoder();
			geocoder.geocode( { 'address': address}, function(results, status) {
			if (status == 'OK') {
			LatLng = results[0].geometry.location;
		    latitude=LatLng.lat();
			longitude=LatLng.lng();
			var latlon = {lat: latitude, lng: longitude};
			map.setCenter(latlon);
			} else {
			alert('Geocode was not successful for the following reason: ' + status);
		  }
		});
		}		 
		 else if(temp==2)
		 {
			longitude = document.getElementById('longitude').value; 
			latitude = document.getElementById('latitude').value;
			longitude=Number(longitude);
			latitude=Number(latitude);
			var latlon = {lat: latitude, lng: longitude};
			map.setCenter(latlon);
		 }
	}
	function getOption()
	{
		 height= document.getElementById('height').value;
		 width = document.getElementById('width').value;
		 
		 var t=document.getElementById("maptype").value;
		 switch(t)
		 {
			case "1":
			{
				maptype= 'roadmap';
				break;
			}
			case "2":
			{
				maptype= 'satellite';
				break;
			}
			case "3":
			{
				maptype= 'hybrid';
				break;
			}
			case "4":
			{
				maptype= 'terrain';
				break;
			}
				
		 }
		 height= Number(height);
		 width = Number(width);
		 
		 x = document.createElement("STYLE");
		 t = document.createTextNode("#map {height: "+height+"px;width:"+width+"px;}");
		 x.appendChild(t);
		 document.head.appendChild(x);
		 initMap();
    }
	  
      function initMap() {
        var uluru = {lat: latitude, lng: longitude};
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: zoom,
          center: uluru,
		  mapTypeId: maptype
        });
		 map.addListener('bounds_changed', function() {		  
          document.getElementById( 'zoom' ).value = map.getZoom();
		  
		  document.getElementById( 'center_latlon' ).innerHTML = map.getCenter();
		  for (var i = 0; i < center_mark.length; i++) {
          center_mark[i].setMap(null);
        }
		var infowindow = new google.maps.InfoWindow({
          content: "中心點"
        });
		center_mark=[];
		var marker = new google.maps.Marker({
			position: map.getCenter(),
			map: map,
			draggable: true
		});
		
		marker.addListener('mouseup',function(){
			
			map.setCenter(marker.getPosition());
			
		});
		center_mark.push(marker);
		  document.getElementById( 'left_lat' ).value = map.getBounds().getSouthWest().lat();
		  document.getElementById( 'left_lon' ).value = map.getBounds().getSouthWest().lng();
		  document.getElementById( 'right_lat' ).value = map.getBounds().getNorthEast().lat();
		  document.getElementById( 'right_lon' ).value = map.getBounds().getNorthEast().lng();
        });
		
		
       		
      }
	   function getMapinfo()
	   {
		document.getElementById( 'zoom' ).value = map.getZoom();
		document.getElementById( 'left_lat' ).value = map.getBounds().getSouthWest().lat();
		document.getElementById( 'left_lon' ).value = map.getBounds().getSouthWest().lng();
		document.getElementById( 'right_lat' ).value = map.getBounds().getNorthEast().lat();
		document.getElementById( 'right_lon' ).value = map.getBounds().getNorthEast().lng();
	   }
	   function screenshot()
	   {
			var url_lat=map.getCenter().lat();
			var url_lng=map.getCenter().lng();
			var url_zoom=map.getZoom();
			var url=url_lat+" "+url_lng+" "+ url_zoom+" "+height+"x"+width+" "+maptype;
			//alert(url);
			var left_upper_lat=map.getBounds().getNorthEast().lat();
			var left_upper_lon=map.getBounds().getSouthWest().lng();
			var left_down_lat= map.getBounds().getSouthWest().lat();
			var left_down_lon= map.getBounds().getSouthWest().lng();
			var right_upper_lat=map.getBounds().getNorthEast().lat();
			var right_upper_lon=map.getBounds().getNorthEast().lng();
			var x_distance=distance(Number(left_upper_lat),Number(left_upper_lon),Number(right_upper_lat),Number(right_upper_lon));
			var y_distance=distance(Number(left_upper_lat),Number(left_upper_lon),Number(left_down_lat),Number(left_down_lon));
			
			var per_x = x_distance /width;
			var per_y = y_distance /height;
			var result = convertDecimal(Number(left_upper_lat),Number(left_upper_lon));
			var easting = result['easting'];
			var northing = result['northing'];
			var zone = result['zone'];
			var southern = result['southern']?"S":"N";
			var file_name=prompt("請輸入圖檔檔名(不需加附檔名)","test_jpg");
			var toalarm =false;
			file_name=chk(file_name,toalarm);
			if(file_name!=null)
				location.href="Download_Zip.php?url="+url+"&filename="+file_name+"&value="+per_x+" "+per_y+" "+easting+" "+northing+" "+zone+" "+southern;
	  }
	   function distance(lat1,lon1,lat2,lon2) {
			 var R = 6371000; // km (change this constant to get miles)
			 var dLat = (lat2-lat1) * Math.PI / 180;
			 var dLon = (lon2-lon1) * Math.PI / 180;
			 var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
			  Math.cos(lat1 * Math.PI / 180 ) * Math.cos(lat2 * Math.PI / 180 ) *
			  Math.sin(dLon/2) * Math.sin(dLon/2);
			 var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
			 var d = R * c;
			 if (d>1) return Math.round(d);
			 return d;
		}
		function convertDecimal(lat,lon) { 
			
            var mapDatum = 0;

            if (isNaN(lat) || lat > 90 || lat < -90) {
                alert("Latitude must be between -90 and 90");
                return;
            }

            if (isNaN(lon) || lon > 180 || lon < -180) {
                alert("Longitude must be between -180 and 180");
                return;
            }    
            utmconv.setDatum(mapDatum);
            var coords = utmconv.latLngToUtm(lat, lon);
			
			return {easting:coords.global.easting, northing:coords.global.northing, zone:coords.global.zone, southern:coords.global.southern};
            //setStandardUtm(coords.global.easting, coords.global.northing, coords.global.zone, coords.global.southern);       
        }
		function chk(str,toalarm)
		{
			for (var i=0;i<stralarm.length;i++){ //依序載入使用者輸入的每個字元
				for (var j=0;j<str.length;j++)
				{
					ch=str.substr(j,1);
					if (ch==stralarm[i]) //如果包含禁止字元
					{
						toalarm = true; //設置此變數為true
					}
				} 
			}
			if (toalarm){
				alert("包含特殊字元,請修正!"); 
				return null;
			}
			else
				return str;
		}	 
	   function updateMap()
	   {	
	   
			
			/*latitude = map.getCenter().lat();
			longitude = map.getCenter().lng();
			zoom = map.getZoom();
			initMap();
			window.alert(map.getBounds());
			window.alert(map.getBounds().getSouthWest().lat());
			window.alert(map.getBounds().getSouthWest().lng());
			window.alert(map.getBounds().getNorthEast().lat());
			window.alert(map.getBounds().getNorthEast().lng());
			
			window.alert(map.getZoom());			
			*/
	   }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB43BSD7jNrs-K91siTJBV12MRAMsUTxHM&callback=initMap">
    </script>
  </body>
</html>