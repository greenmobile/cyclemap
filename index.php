<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<?php
include "config.php";
?>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo($title);?></title>
	<meta name="title" content="<?php echo($title); ?>" />
	<meta name="description" content="<?php echo($description); ?>" />
	<meta name="keywords" content="" />
	<meta name="author" content="" />

	<link rel="stylesheet" type="text/css" media="all" href="style.css" />
	<link rel="image_src" href="http://gmino.de/static/img/cycleproblemsmini2.png" />
	<link rel="Stylesheet" type="text/css" href="jquery-ui-1.8.13.custom.css"/>

	<script type="text/javascript" src="http://www.openlayers.org/api/OpenLayers.js"></script>
	<script type="text/javascript" src="script/jquery-1.5.1.min.js"></script>
	<script type="text/javascript" src="script/jquery-ui-1.8.13.custom.min.js"></script>
	<script type="text/javascript" src="script/jquery.watermarkinput.js"></script>
	<script type="text/javascript" src="orte_eintragen.php"></script>
</head>
<body>
	<div id="bar">
    <div style="float: left;">
	  	<span><b> <?php echo($header_text);?> (<a href="letter.html" onclick="showpopup(); return false;">Mehr Infos</a>). <span id="counter"></span></b></span>
		<span>Doppelklick fügt einen Marker hinzu.</span> 
        </div>
		<div>
		<span><nobr><b>Straßensuche:</b> <form action="#" onsubmit="search(); return false;"><input name="street" id="street"/><input type="submit" value="suchen" onclick="search();"/></form></nobr></span>
        </div>
	</div>
	<div id="maptest">
	  	<div id="mapdiv">Javascript muss aktiviert sein.</div> 
	</div>
	<div id="letterdiv"></div> 

	<script type="text/javascript">
		// aus Gründen, die absolut unklar sind, muss dies hier im Body stehen und nicht in einer gesonderten Datei.
		initMap();		

		function showpopup()
		{
			$("#letterdiv").load("<?php echo($popup_file);?>").dialog({minWidth: 550, title: "<?php echo($popup_title);?>", position: [200,60] }); 
		}
	</script>
</body>
</html>
