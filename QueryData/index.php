<html>
<head>
<?php include("../navbar.php"); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>雲端地理資訊系統（Cloud-based GIS) :查詢圖資</title>
<link rel="shortcut icon" href="../css/favicon.ico">
<link rel="stylesheet" href="../css/blueprint/screen.css" type="text/css" media="screen, projection" />
<link rel="stylesheet" href="../css/blueprint/print.css" type="text/css" media="print" />
<link rel="stylesheet" href="../css/geoserver.css" type="text/css" media="screen, projection" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<h1>查詢圖資</h1>
<form action="index.php" method="post">
	經度:<input type="text" id="lat" name="lat" value="0" />
	緯度:<input type="text" id="lng" name="lng" value="0"/>
	<input type="submit" name="query"  value="查詢"/>
</form>

<?php

$ip="140.130.35.73";
$port="8080";
$geoservername="Cloud-based GIS";
$even_odd=0;
if(isset($_POST['query']))
{
    
    $lat= floatval($_POST['lat']);
    $lng= floatval($_POST['lng']);
    if($lat>180 ||$lat<-180||$lng>90||$lng<-90)
    {
        echo"<h1> <font color=\"red\"輸入的經緯度範圍有誤，請重新輸入</font></h1>";
        header("Location:index.php");
    }
    else 
        echo"<h1>包含經緯度:(".$lat."  ,  ".$lng.")的圖資</h1>";
       
    $file ="data.xml";
    if (file_exists($file)) {
        $xml = simplexml_load_file($file);
       // print_r($xml);
        foreach ($xml->feature as $data)
        {
            $minlat=floatval($data->Min_lat);
            $minlng=floatval($data->Min_lng);
            $maxlat=floatval($data->Max_lat);
            $maxlng=floatval($data->Max_lng);
            if($lat<=$maxlat && $lng<=$maxlng && $lat>=$minlat && $lng>=$minlng)
            {
                $dif=abs($maxlat-$minlat)/abs($maxlng-$minlng);
                $width=768;
                $height=round($width/$dif);
                if($height>768)
                {
                    $height=768;
                    $width=round($width*$dif);
                }
                
                if($even_odd==0)
                echo'<table class="clearBoth" style="empty-cells: show" cellspacing="0" id="id325"><thead><tr><th><div><a href="javascript:;" id="id36f">圖層</a></div></th>
                	<th><div><a href="javascript:;" id="id370">工作區</a></div></th><th><div>View</div></th><th><div>其他格式</div></th></tr></thead>';           
                if($even_odd++%2!=0)
                    echo"<tbody><tr  class=\"odd\">";
                else 
                    echo"<tbody><tr  class=\"even\">";
                echo "<td>".$data->name."</td>";
                echo "<td>".$data->WorkspaceName."</td>";
                
                echo "<td><a href='http://".$ip.":".$port."/".$geoservername."/".$data->WorkspaceName."/wms?service=WMS&version=1.1.0&request=GetMap&layers=".$data->WorkspaceName.":".$data->name."&bbox=".$data->Min_x.",".$data->Min_y.",".$data->Max_x.",".$data->Max_y."&width=".$width."&height=".$height."&srs=".$data->SRS."&format=application/openlayers'  target='_blank'>Openlayers</a></td>";
                if(preg_match("/Coverage/i", $data->id))
                {
                    echo "<td>";
                    echo"<select onchange=\"window.open((this.options[this.selectedIndex].parentNode.label == &#039;WMS&#039;) ? &#039;"."http://".$ip.":".$port."/".$geoservername."/".$data->WorkspaceName."/wms?service=WMS&amp;version=1.1.0&amp;request=GetMap&amp;layers=".$data->WorkspaceName.":".$data->name."&amp;bbox=".$data->Min_x.",".$data->Min_y.",".$data->Max_x.",".$data->Max_y."&amp;width=".$width."&amp;height=".$height."&amp;srs=".$data->SRS."&amp;format=&#039; + this.options[this.selectedIndex].value : &#039;http://localhost:8080/geoserver/".$data->WorkspaceName."/ows?service=WFS&amp;version=1.0.0&amp;request=GetFeature&amp;typeName=".$data->WorkspaceName.":".$data->name."&amp;maxFeatures=50&amp;outputFormat=&#039; + this.options[this.selectedIndex].value);this.selectedIndex=0\">";
                    echo' <option>Select one</option>
                            <optgroup label="WMS">
                                <option value="application%2Fatom%20xml">AtomPub</option><option value="image%2Fgif">GIF</option><option value="application%2Frss%2Bxml">GeoRSS</option><option value="image%2Fgeotiff">GeoTiff</option><option value="image%2Fgeotiff8">GeoTiff 8-bits</option><option value="image%2Fjpeg">JPEG</option><option value="image%2Fvnd.jpeg-png">JPEG-PNG</option><option value="application%2Fvnd.google-earth.kmz%20xml">KML (compressed)</option><option value="application%2Fvnd.google-earth.kml%2Bxml%3Bmode%3Dnetworklink">KML (network link)</option><option value="application%2Fvnd.google-earth.kml%2Bxml">KML (plain)</option><option value="text%2Fhtml%3B%20subtype%3Dopenlayers">OpenLayers</option><option value="application%2Fpdf">PDF</option><option value="image%2Fpng">PNG</option><option value="image%2Fpng%3B%20mode%3D8bit">PNG 8bit</option><option value="image%2Fsvg%20xml">SVG</option><option value="image%2Ftiff">Tiff</option><option value="image%2Ftiff8">Tiff 8-bits</option><option value="application%2Fjson%3Btype%3Dutfgrid">UTFGrid</option>
                             </optgroup>
                            <optgroup label="WFS">
                              </optgroup>
                        </select>';
                    echo"</td>";
                }
             
                else
                {
                    echo "<td>";
                    echo"<select onchange=\"window.open((this.options[this.selectedIndex].parentNode.label == &#039;WMS&#039;) ? &#039;"."http://".$ip.":".$port."/".$geoservername."/".$data->WorkspaceName."/wms?service=WMS&amp;version=1.1.0&amp;request=GetMap&amp;layers=".$data->WorkspaceName.":".$data->name."&amp;bbox=".$data->Min_x.",".$data->Min_y.",".$data->Max_x.",".$data->Max_y."&amp;width=".$width."&amp;height=".$height."&amp;srs=".$data->SRS."&amp;format=&#039; + this.options[this.selectedIndex].value : &#039;http://localhost:8080/geoserver/".$data->WorkspaceName."/ows?service=WFS&amp;version=1.0.0&amp;request=GetFeature&amp;typeName=".$data->WorkspaceName.":".$data->name."&amp;maxFeatures=50&amp;outputFormat=&#039; + this.options[this.selectedIndex].value);this.selectedIndex=0\">";
                    echo' <option>Select one</option>
                            <optgroup label="WMS">
                                <option value="application%2Fatom%20xml">AtomPub</option><option value="image%2Fgif">GIF</option><option value="application%2Frss%2Bxml">GeoRSS</option><option value="image%2Fgeotiff">GeoTiff</option><option value="image%2Fgeotiff8">GeoTiff 8-bits</option><option value="image%2Fjpeg">JPEG</option><option value="image%2Fvnd.jpeg-png">JPEG-PNG</option><option value="application%2Fvnd.google-earth.kmz%20xml">KML (compressed)</option><option value="application%2Fvnd.google-earth.kml%2Bxml%3Bmode%3Dnetworklink">KML (network link)</option><option value="application%2Fvnd.google-earth.kml%2Bxml">KML (plain)</option><option value="text%2Fhtml%3B%20subtype%3Dopenlayers">OpenLayers</option><option value="application%2Fpdf">PDF</option><option value="image%2Fpng">PNG</option><option value="image%2Fpng%3B%20mode%3D8bit">PNG 8bit</option><option value="image%2Fsvg%20xml">SVG</option><option value="image%2Ftiff">Tiff</option><option value="image%2Ftiff8">Tiff 8-bits</option><option value="application%2Fjson%3Btype%3Dutfgrid">UTFGrid</option>
                             </optgroup>
                            <optgroup label="WFS">
                               <option value="csv">CSV</option><option value="text%2Fxml%3B%20subtype%3Dgml%2F2.1.2">GML2</option><option value="gml3">GML3.1</option><option value="application%2Fgml%2Bxml%3B%20version%3D3.2">GML3.2</option><option value="application%2Fjson">GeoJSON</option><option value="application%2Fvnd.google-earth.kml%2Bxml">KML</option><option value="SHAPE-ZIP">Shapefile</option>
                           </optgroup>
                        </select>';
                    echo"</td>";
                }
                
                       
                        echo "</tr>";
               
              
            }
             
        }
    }
}
?>
</tbody>
</table>
</body>
</html>
