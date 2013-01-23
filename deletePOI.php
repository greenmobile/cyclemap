<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") ." GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Cache-Control: post-check=0, pre-check=0", FALSE);
include "db.php";
connect();

    $id = $_GET['id'];

 
 
    //do some data saving stuff
    $allGood = saveMethod($id);
 
    if($allGood){
        echo 1;
    }else{
        echo 2;
    }
 
function saveMethod($id){
  $abfrage = "DELETE FROM poi where id=$id";
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
