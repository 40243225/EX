<?php
define('IMAGE_DIR_PATH', './Zip/');
$describe="定義SRS的EPSG為:";
$filename=$_GET['filename']; //匯入ZIP後的檔案名稱
$url=explode(" ", $_GET['url']);
$lat=$url[0];
$lng=$url[1];
$zoom=$url[2];
$size=$url[3];
$maptype=$url[4];

$url ="https://maps.googleapis.com/maps/api/staticmap?center=".$lat.",".$lng."&zoom=".$zoom."&size=".$size."&scale=2&maptype=".$maptype."&format=jpg&key=AIzaSyBLUpK1AgeMf3ieB4KkmorgyRxW0Gbllvc";	
$output = explode(" ", $_GET['value']);
$per_x=$output[0];
$per_y="-".$output[1];
$easting=$output[2];
$northing=$output[3];
$EPSG=zone_N_S($output[4],$output[5]);
$zone=$describe.$EPSG;
$file_zip=$output[2]."_".$output[3]."_".$output[4]."_".$output[5]; //匯入ZIP前的檔案名稱
checkName($file_zip.".zip");
$jpgw_tfw=$per_x."\r\n0\r\n0\r\n".$per_y."\r\n".$easting."\r\n".$northing;
//$jpgw_tfw=chr(239) . chr(187) . chr(191) .$jpgw_tfw;
create_file($jpgw_tfw,$file_zip,".jpgw");
create_file($jpgw_tfw,$file_zip,".tfw");
create_img_tif($file_zip,$url);
create_img_jpg($file_zip,$url);
create_zip($filename,$file_zip,$EPSG);
function zone_N_S($number,$S_N)
{
	$EPSG=32650;
	if($S_N=='S')
		$EPSG=32500+(int)$number;
	else
		$EPSG=32600+(int)$number;
	return $EPSG;
		
}
function create_img_jpg($imageName,$url)
{
	$hostfile = fopen($url, 'r');
	$fh = fopen(IMAGE_DIR_PATH.$imageName.".jpg", 'w');
	while (!feof($hostfile)) {
		$output = fread($hostfile, 8192);
		fwrite($fh, $output);
	}
	fclose($hostfile);
	fclose($fh);
}
function create_img_tif($imageName,$url)
{
	$hostfile = fopen($url, 'r');
	$fh = fopen(IMAGE_DIR_PATH.$imageName.".tif", 'w');
	while (!feof($hostfile)) {
		$output = fread($hostfile, 8192);
		fwrite($fh, $output);
	}
	fclose($hostfile);
	fclose($fh);
}
function create_file($str,$file_name,$format)
{
	$file =IMAGE_DIR_PATH.$file_name.$format;
	$res_file = file_put_contents($file, $str);
}

function create_zip($file_name,$file_zip,$EPSG)
{
	$file_tif =IMAGE_DIR_PATH.$file_zip.".tif";
	$file_tfw=IMAGE_DIR_PATH.$file_zip.".tfw";
	$file_img =IMAGE_DIR_PATH.$file_zip.".jpg";
	$file_jpgw=IMAGE_DIR_PATH.$file_zip.".jpgw";
	$file_zip =IMAGE_DIR_PATH.$file_zip.".zip";
	$zip = new ZipArchive;
	$res_zip = $zip->open($file_zip, ZipArchive::CREATE);
	if (TRUE === $res_zip) 
	{
		$zip->addFile($file_img, "jpg/".$file_name.".jpg");
		$zip->addFile($file_jpgw, "jpg/".$file_name.".jpgw");
		$zip->addFile($file_tif, "tif/".$file_name.".tif");
		$zip->addFile($file_tfw, "tif/".$file_name.".tfw");
		$zip->close();
		unlink($file_img);
		unlink($file_jpgw);
		unlink($file_tif);
		unlink($file_tfw);
	}
	else
	{
		unlink($file_img);
		unlink($file_jpgw);
		unlink($file_tif);
		unlink($file_tfw);
		die("產生壓縮檔失敗");
	}

	if (file_exists($file_zip)) {
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.basename($file_zip));
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file_zip));
		readfile($file_zip);
		exit;
	}
}
function checkName($Name){
	$Name = urlencode($Name);
   if(file_exists(IMAGE_DIR_PATH.$Name))
    unlink(IMAGE_DIR_PATH.$Name);     
}
?>