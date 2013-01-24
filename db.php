<?php
function connect()
{
	$verbindung = mysql_connect ("localhost","DB_USER", "DB_PASSWORD") or die("no connection available. Username or password wrong!");
		mysql_select_db("map_test") or die("no connection available.");
	mysql_query("CREATE TABLE IF NOT EXISTS `poi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Titel` varchar(200) NOT NULL,
  `Beschreibung` text NOT NULL,
  `Latitude` double NOT NULL,
  `Longitude` double NOT NULL,
  `Typ` varchar(80) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=492;");
	mysql_query("set names 'utf8';");
}




?>

