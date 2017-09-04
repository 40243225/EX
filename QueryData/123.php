<?php
$min_lat=-74.0118315772888;
$min_lng=40.70754683896324;
$max_lat=-74.00153046439813;
$max_lng=40.719885123828675;
$dif=abs($max_lat-$min_lat)/abs($max_lng-$min_lng);

$width=768;
$height=round($width/$dif);
if($height>768)
{
	$height=768;
	$width=round($width*$dif);
}
echo $height."<br>";
echo $width;
?>