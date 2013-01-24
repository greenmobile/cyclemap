<?php header ("Content-Type:application/javascript");
include "config.php"; ?>

	var popup;
    var popup2;
    var feature;
    var namen = new Array;
    var beschreibung = new Array;
    var typ = new Array;
    var tourId = new Array;
    var id = new Array;
    var resOb = null;
    var map;
    var markers;
    var osm;

	function getPoiTypes()
	{
		return 	'<option value="pin-export">Allgemein</option>'+
				'<option value="smiley_happy">Positivbeispiel</option>'+
				'<option value="trafficlight">Ampel</option>'+
				'<option value="caution">Vorsicht</option>'+
				'<option value="stop">Stop</option>'+
				'<option value="leftright">Radfahrer beachten</option>'+
				'<option value="mainroad">Vorfahrt</option>'+
				'<option value="accesdenied">Verbot</option>' + 
				'<option value="barrier">Absperrung</option>' + 
				'<option value="park">Parken</option>' +
				'<option value="busstop">Haltestelle</option>' +
				'<option value="bike_rising">Steigung</option>' + 
				'<option value="bulldozer">Baustelle</option>' + 
				'<option value="caraccident">Unfall</option>'+
				'<option value="icy_road">Glätte</option>'+
				'<option value="cycling">Fahrrad</option>'+
				'<option value="pedestrian">Fußgänger</option>'+
				'<option value="guiding">Wegführung</option>'+
				'<option value="left">Linksabbiegen</option>'+
				'<option value="stripe">Schutzstreifen</option>'+
				'<option value="atv">Motorad</option>' + 
				'<option value="car">Auto</option>'+
				'<option value="highway">Kraftfahrstraße</option>'+
				'';
	}

	function getAuth()
	{
		return "";
	}

    function initMap() {
		$("#mapdiv").empty();
        map = new OpenLayers.Map("mapdiv");
        var osm = new OpenLayers.Layer.OSM();
        map.addLayer(osm);
        markerarray = new Array();
        var center_lonlat = (new OpenLayers.LonLat(<?php echo($longitude);?>,<?php echo($latitude);?>)).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());
        var zoom = 13;
        markers = new OpenLayers.Layer.Markers("Markers");
        map.addLayer(markers);
        sndReq();
        map.setCenter(center_lonlat, zoom);

		// disable zoom-on-doubleclick and add a location instead
		var Navigation = new OpenLayers.Control.Navigation({
			defaultDblClick: function(event) {
				var lonlat = map.getLonLatFromViewPortPx(event.xy);
				create_new_place(lonlat); 
				return; 
			}
		});
		map.addControl(Navigation);
		console.log("Map initialized.");
    	console.log("Poi types: " + getPoiTypes());
    }

    function create_edit_popup(popup2, POI_id, lat, lon) {
        popup2.setContentHTML("<div>" + "<form id=\"formEdit\" action=script//&quot;input_text.htm/&quot;>" + "<p>Titel:<br>" + "<input name=\"titel_update\" type=\"text\" size=\"26\" maxlength=\"80\" value=\"" + namen[POI_id] + "\">" + "<br/>Beschreibung:<br/>" + "<textarea name=\"beschreibung_update\" id=\"beschreibung_update\" cols=\"35\" rows=\"6\">" + beschreibung[POI_id] + "</textarea>" + "<br/><select name=\"select_typ\" id=\"select_typ_update\" style=\"vertical-align: middle\">"+getPoiTypes()+"</select><br/><input type=\"button\" name=\"Text 2\" value=\"Eintragen\" onclick=\"update_poi(" + lat + "," + lon + "," + id[POI_id] + ");\">" + "</p></form></div>");
        popup2.setBackgroundColor("#FFF");
        popup2.setOpacity(0.9);
        popup2.autoSize = true;
		markers.map.addPopup(popup2, true);
        document.getElementById("select_typ_update").value = typ[POI_id];
	}

    function create_new_place(lonlat) {
        var popFeature = new OpenLayers.Feature(osm, lonlat);
        popup = popFeature.createPopup(true);
        popup.setContentHTML("<div>" 
+ "<form id =\"formCreate\" action=script//&quot;input_text.htm/&quot;><p>Titel:<br><input name=\"titel\" type=\"text\" size=\"26\" maxlength=\"80\">" + "<br/>Beschreibung:<br/><textarea name=\"beschreibung\" id=\"beschreibung\" cols=\"35\" rows=\"6\"></textarea><br/><select name=\"select_typ\" id=\"select_typ\" style=\"vertical-align: middle\">"+getPoiTypes()+"</select><br/><input type=\"button\" name=\"Text 2\" value=\"Eintragen\" onclick=\"poi_senden(" + lonlat.lat + "," + lonlat.lon + ");\">" + "</p></form></div>");
        popup.setBackgroundColor("#FFF");
        popup.setOpacity(0.9);
        popup.autoSize = true;
        markers.map.addPopup(popup, true);
    }


    function sndReq() {
		var tourId = $("#selectReise").val();
	
		$.ajax({
			url : "getPOI.php",
			success : handleResponse
		});
    }


    function handleResponse(data, textStatus) {
        var json = jQuery.parseJSON(data);
		$("#counter").html(" (bisher "+json.length+") ");
        markers.clearMarkers();
        for (i = 0; i < json.length; i++) {
			var lola = (new OpenLayers.LonLat(parseFloat(json[i].lon), parseFloat(json[i].lat))).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());
            namen[i] = json[i].name;
            beschreibung[i] = json[i].desc;
            typ[i] = json[i].type;
            tourId[i] = json[i].tourId;
            id[i] = json[i].id;
            feature = new OpenLayers.Feature(osm, lola);
			var icon, dir;

			dir = "mapicons-orange";
			if(beschreibung[i].toLowerCase().indexOf("erledigt") != -1)
				dir = "mapicons-green";
          
			feature.data.icon = new OpenLayers.Icon("images/"+dir+"/"+json[i].type + ".png", new OpenLayers.Size(32, 37), new OpenLayers.Pixel(-16, -34));
            

            marker = feature.createMarker();
            marker.id = i;
            markers.addMarker(marker);
			//console.log(json[i] );
            marker.events.register("mousedown", marker,   
				function () {
					var popFeature = new OpenLayers.Feature(osm, this.lonlat);
					var lonlatTrans = (new OpenLayers.LonLat( this.lonlat.lon,  this.lonlat.lat)).transform(map.getProjectionObject(), new OpenLayers.Projection("EPSG:4326"));
		
					popup2 = popFeature.createPopup(true);
					popup2.setContentHTML("<div style=\"width:185; \"><p><b><font size=\"3\">" + namen[this.id] + "<font size='2'></b><br><font size='2'>" + beschreibung[this.id] + "</font><br>" + "<input type=\"button\" name=\"Text 2\" value=\"Bearbeiten\" onclick=\"create_edit_popup(popup2, " + this.id + "," + this.lonlat.lat + "," + this.lonlat.lon + ");\">" + "<input type=\"button\" name=\"Text 3\" value=\"L&ouml;schen\" onclick=\"delete_poi(" + id[this.id] + ");\"></p>Zeige Position auf <a href='http://maps.google.de/maps?ll=" + lonlatTrans.lat + "," + lonlatTrans.lon + "&spn=0.005,0.005&t=h&q=" + lonlatTrans.lat + "," + lonlatTrans.lon + "' target='_blank'>Google Satellitenbild</a>, <a href='http://www.bing.com/maps/default.aspx?v=2&cp=" + lonlatTrans.lat + "~" + lonlatTrans.lon + "&style=h&lvl=16&sp=Point." + lonlatTrans.lat + "_" + lonlatTrans.lon + "' target='_blank'>Bing Satellitenbild</a></div>");
					popup2.setBackgroundColor("#FFF");
					popup2.setOpacity(0.9);
					popup2.autoSize = true;
					markers.map.addPopup(popup2, true);
				}
			);
		
        }
        
    }

    function poi_senden(lat, lon) {
        var lonlatTrans = (new OpenLayers.LonLat(lon, lat)).transform(map.getProjectionObject(), new OpenLayers.Projection("EPSG:4326"));
		var titel = encodeURIComponent($("input[name=titel]").val());	        
		var beschreibung = encodeURIComponent($("#beschreibung").val());	        
		var data = "titel=" + titel + "&beschreibung=" + beschreibung + "&latitude=" + lonlatTrans.lat + "&longitude=" + lonlatTrans.lon + "&typ=" + $("#select_typ").val();
        $.ajax({url: "sendPOI.php", type: "GET", data: data, success: 
		  function (reqCode) {
				if (reqCode == 1) {
				    $(".ready").fadeOut("slow");
				    $(".done").fadeIn("slow");
				} else {
				    alert("Fehler beim Abschicken des Formulares.");
				}
			}
		});
        var t = setTimeout("sndReq()", 200);
        popup.destroy();
        return false;
    }


    function delete_poi(id) {
        var data = "id=" + id;
        $.ajax({url: "deletePOI.php", type: "GET", data: data, success: 
			function (reqCode) 
			{
				if (reqCode != 1) 
					alert("Fehler beim Abschicken des Formulares.");
			}
		});
        var t = setTimeout("sndReq()", 200);
        popup2.destroy();
        return false;
    }


    function update_poi(lat, lon, id) {
        var lonlatTrans = (new OpenLayers.LonLat(lon, lat)).transform(map.getProjectionObject(), new OpenLayers.Projection("EPSG:4326"));
		var titel = encodeURIComponent($("input[name=titel_update]").val());	        
		var beschreibung = encodeURIComponent($("#beschreibung_update").val());
		console.log("Encodierter Titel: " + titel);
		var data = "titel=" + titel + "&beschreibung=" + beschreibung + "&latitude=" + lonlatTrans.lat + "&longitude=" + lonlatTrans.lon + "&id=" + id + "&typ=" + $("#select_typ_update").val();
        $.ajax({url: "updatePOI.php", type: "GET", data: data, success: 
			function (reqCode) 
			{
				if (reqCode != 1) 
					alert("Fehler beim Abschicken des Formulares.");
			}
		});
        var t = setTimeout("sndReq()", 200);
        popup2.destroy();
        return false;
    }

	function search()
		{
			var city = "Braunschweig";

			var street = $('#street').val();
			if(street == "Straße")
				street = "";
			
			if(street != "" && city == "")
			{
				alert("Sie müssen eintweder eine Stadt eingeben, oder eine Stadt und eine Straße.");
				return;
			}

			var query = encodeURIComponent(city);
			if(street != "")
				query = encodeURIComponent(street) + ", " + encodeURIComponent(city);
			var url = "http://open.mapquestapi.com/nominatim/v1/search?format=json&q=" + query + "&addressdetails=1&limit=1";
//alert(url);
			$.getJSON(url,
				function (json) 
				{
					console.log(json);
					if(json.length > 0)
					{
						var village = json[0].address.village;
						if(!village)
							village = json[0].address.city;
						var lat = json[0].lat;
						var lon = json[0].lon;
						var center_lonlat = (new OpenLayers.LonLat(lon, lat)).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());
        				var zoom = (street == "") ? 12 : 18;
				        map.setCenter(center_lonlat, zoom);
						if(village != "" && village.toLowerCase() != city.toLowerCase())
							alert("Es wurde eine Adresse gefunden, diese liegt aber in " + village + ".");
					}
					else
						alert("Adresse nicht gefunden: " + query);
				}
			);
			return false;
		}
