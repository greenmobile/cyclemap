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
    $titel = mysql_real_escape_string($_GET['titel']);
    $beschreibung = mysql_real_escape_string($_GET['beschreibung']);
	$latitude= mysql_real_escape_string($_GET['latitude']);
    $longitude = mysql_real_escape_string($_GET['longitude']);
	$id= mysql_real_escape_string($_GET['id']);
	$typ= mysql_real_escape_string($_GET['typ']);
 	
    //do some data saving stuff
    $allGood = saveMethod($titel,$beschreibung,$latitude,$longitude, $id, $typ);
 
    if($allGood){
        echo 1;
    }else{
        echo 2;
    }
 
function saveMethod($titel,$beschreibung,$latitude,$longitude, $id, $typ){
  $abfrage = "UPDATE poi SET Titel='$titel',Beschreibung='$beschreibung',Latitude='$latitude',Longitude='$longitude',Typ='$typ' WHERE id='$id'";
  	
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
