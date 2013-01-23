<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") ." GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Cache-Control: post-check=0, pre-check=0", FALSE);
include "db.php";
connect();

mysql_query("set names 'utf-8';");
	$titel = mysql_real_escape_string($_GET['titel']);
    $beschreibung = mysql_real_escape_string($_GET['beschreibung']);
	$latitude= mysql_real_escape_string($_GET['latitude']);
    $longitude = mysql_real_escape_string($_GET['longitude']);
	$typ = mysql_real_escape_string($_GET['typ']);

	//do some data saving stuff
    $allGood = saveMethod($titel,$beschreibung,$latitude,$longitude,$typ);
 
    if($allGood){
        echo 1;
    }else{
        echo 2;
    }
 
function saveMethod($titel,$beschreibung,$latitude,$longitude,$typ){
  $abfrage = "INSERT INTO poi (Titel,Beschreibung,Latitude,Longitude,Typ) VALUES('$titel','$beschreibung','$latitude','$longitude','$typ')";
//echo $abfrage;  
$result = mysql_query($abfrage);
  if(!$result){
  mysql_close();
  return False;
  } 
  else{
  mysql_close();
  return True;
  } 
  
}
?>
