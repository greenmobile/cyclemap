<?php
header("Content-Type: text/html; charset=utf-8");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") ." GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Cache-Control: post-check=0, pre-check=0", FALSE);
include "db.php";
connect();
mysql_query("set names 'utf-8';");
if(isset($_GET['tourId']))
	$tourId =  mysql_real_escape_string($_GET['tourId']);
else
	$tourId = -1;
$abfrage = "SELECT id, Titel as name, Beschreibung as `desc`, Latitude as lat, Longitude as lon, Typ as type FROM poi;";
$ergebnis = mysql_query($abfrage);
$output = array();
while($row = mysql_fetch_array($ergebnis, MYSQL_ASSOC))
{
	$output[]=$row;
/*	echo "\nROW: ";
	print_r($row);
	echo "\n";*/
}
print(json_encode($output));
mysql_close();
?>

