<?php 
/*
Created: LeadSoft
Purpose: create the sortcode file for showing the map on 
front end. first of all showing all stores on the site
when user search by zip code then based on his current 
location calculate ths 

*/ 
add_shortcode("Display_Store_Map", "Display_Store_Map"); 	
function Display_Store_Map( $atts ){ob_start();global $wpdb;
	if(isset($_GET['zipcode']) and is_numeric($_GET['zipcode'])){
		$zipcode = $_GET['zipcode'];
	}else{$zipcode = '';}
	$op_google_api_key = $wpdb->get_var("SELECT option_value FROM ".$wpdb->base_prefix."options where option_name = 'op_google_api_key' ");  
	$current = network_site_url().'wp-content/plugins/wpOfficePride/include/template/assests/img/current.png';
	
	$Widget_location = new Widget_location();
	$node = $Widget_location->current_location_lat_lng(); ?>
<script type='text/javascript' src='<?php echo network_site_url() ?>/wp-includes/js/jquery/jquery.js'></script>    
<script src="https://maps.googleapis.com/maps/api/js?libraries=geometry&key=<?php echo $op_google_api_key; ?>&sensor=true"></script>
<link href="<?php echo network_site_url('/wp-content/themes/officepride/css/main.css') ?>" rel="stylesheet">

<script>
	var geocoder = null;
    var map = null;
    var customerMarker = null;
    var gmarkers = [];
    var closest = [];
    
    function initialize() {
      geocoder = new google.maps.Geocoder(); 
	  var map=new google.maps.Map(document.getElementById("opLocation"),{       
									zoom: 8,       
									center: new google.maps.LatLng(37.0902, 95.7129),
									mapTypeId: google.maps.MapTypeId.ROADMAP,
									styles: [{"featureType":"administrative","elementType":"all","stylers":[{"saturation":"-100"}]},{"featureType":"administrative.province","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","elementType":"all","stylers":[{"saturation":-100},{"lightness":"50"},{"visibility":"simplified"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":"-100"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"all","stylers":[{"lightness":"30"}]},{"featureType":"road.local","elementType":"all","stylers":[{"lightness":"40"}]},{"featureType":"transit","elementType":"all","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]},{"featureType":"water","elementType":"labels","stylers":[{"lightness":-25},{"saturation":-100}]}]
								});
	}

	function codeAddress(address){ 
  		var address = document.getElementById("address").value;
		geocoder.geocode( { 'address': address}, function(results, status) {
		  if (status == google.maps.GeocoderStatus.OK) {
			  var lat = results[0].geometry.location.lat();
			  var lng = results[0].geometry.location.lng(); 
		  
				  ajaxDrawWidget(address, lat, lng);
			} else {
				alert('Please enter the correct zip code.');
			}
		  });
		}
	
	function DrawCemterPosition(address, map){ 
		geocoder.geocode( { 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
		  map.setCenter(results[0].geometry.location);
		  map.setZoom(8);
	  if (customerMarker) customerMarker.setMap(null);
		  customerMarker = new google.maps.Marker({
			  map: map,
			  position: results[0].geometry.location,
			  icon: '<?php echo $current ?>'
		  });
		}
	  });
		}
		
	jQuery('.LocationSearch button.free-quote__submit, button.free-quote__submit').click(function(e){e.preventDefault();
		var zipcode = jQuery('.modal-content input.free-quote__input, .ZipCodeFild').val();
		if(zipcode == '' || zipcode == 'undefined' || zipcode == null){
			var zipcode = jQuery('.modal-content input.free-quote__input, .ZipCodeFild:eq(1)').val();
			}
		codeAddress(zipcode);
		//ajaxDrawWidget(zipcode, '', '');
		return false;
		});
			
	function ajaxDrawWidget(address, lat, lng){
		jQuery.ajax({
					 type: "POST",
					 dataType : "JSON", 
					 ContentType: "application/json", 
					 data: {'action':'update_store_location_data','param':JSON.stringify({'lat':lat, 'lng':lng})}, 
					 url: "<?php echo admin_url(); ?>admin-ajax.php",
					 success: function(r){
						
						if(lat != 'onload'){ 
							jQuery('.flocoffice__body').html(r.html);
						}
						 
						if(address == 'current'){ 
							jQuery('.nearzipcode').text(r.zipcode);
							jQuery('.zipcode-box').removeAttr('style');
							}
							
					    if(r.zipcode != '' && r.zipcode != null && r.zipcode != 'undefined' && address != 'current'){
							jQuery('.nearzipcode').text(address);
							jQuery('.zipcode-box').removeAttr('style');
							}
						
						if(document.getElementById("NearStorePhone") !== null){
							jQuery('#NearStorePhone a').attr('href', 'tel:+'+r.phone).text(r.phone);
							}
						
						if(address == 'current'){ 
								  jQuery('.nearzipcode').text(r.zipcode);
								  jQuery('.zipcode-box').removeAttr('style');
								  }
							  
						jQuery('.nearzipcode').text(address);
						jQuery('.zipcode-box').removeAttr('style');
									  
						jQuery('.modal__close').trigger('click');
							  	
						jQuery('.modal__close').trigger('click');
						
						if(r.drawMap == 'true'){
							var locations = jQuery.parseJSON(r.locations);  
						/*  ###########################################################  */
																	
						  var numberOfResults = 3;
						  	var cent_lat = parseFloat(37.0902); var cent_lng = parseFloat(95.7129);
							
							map = new google.maps.Map(document.getElementById('opLocation'), 
								{       
									zoom: 8,       
									center: new google.maps.LatLng(cent_lat, cent_lng),//37.0902, 95.7129  52.6699927, -0.7274620
									mapTypeId: google.maps.MapTypeId.ROADMAP,
									styles: [{"featureType":"administrative","elementType":"all","stylers":[{"saturation":"-100"}]},{"featureType":"administrative.province","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","elementType":"all","stylers":[{"saturation":-100},{"lightness":"50"},{"visibility":"simplified"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":"-100"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"all","stylers":[{"lightness":"30"}]},{"featureType":"road.local","elementType":"all","stylers":[{"lightness":"40"}]},{"featureType":"transit","elementType":"all","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]},{"featureType":"water","elementType":"labels","stylers":[{"lightness":-25},{"saturation":-100}]}]
								});       
						    
							DrawCemterPosition(address, map);
							
							/*###################################*/
							  var infowindow = new google.maps.InfoWindow();      
							  var marker, i;      
							  var bounds = new google.maps.LatLngBounds();
							  
							  for (i = 0; i < locations.length; i++) {
								  	
										var coordStr = locations[i][4];
									var coords = coordStr.split(",");
									var pt = new google.maps.LatLng(parseFloat(coords[0]),parseFloat(coords[1]));
										bounds.extend(pt);
										marker = new google.maps.Marker({         
														position: pt,         
														map: map,
														icon: locations[i][5],
														address: locations[i][2],
														title: locations[i][0],
														url: locations[i][1],
														phone: locations[i][3],
														city: locations[i][6],
														zip: locations[i][7],
														st: locations[i][8],
														lat: locations[i][9],
														lng: locations[i][10],
														html: locations[i][0]+"<br>"+locations[i][2]
														});                              
										gmarkers.push(marker);
										google.maps.event.addListener(marker, 'click', (function(marker, i) {         return function() 
										{           infowindow.setContent(marker.html);
													infowindow.open(map, marker);         
										}       
									})
									(marker, i));     
								}
							  map.fitBounds(bounds);  
						}
						},
					 error: function(XMLHttpRequest, textStatus, errorThrown){}
				});
	}
	
	// ajaxDrawWidget('', 'onload', '')
<?php if(isset($_GET['zipcode']) and is_numeric($_GET['zipcode'])){?>
		setTimeout(function(){
		//	jQuery('button.free-quote__submit').trigger('click');
		codeAddress(<?php echo $zipcode ?>);
			}, 1000);
<?php	}else{ ?>	
		ajaxDrawWidget('', 'onload', '')
<?php } ?>
	google.maps.event.addDomListener(window, 'load', initialize);
	
    </script>
<style>.flocoffice__body { height:100vh; }
.comclean__flocoffice { margin:0; }
#opLocation { position: relative; overflow: hidden; height:100% }
.location-search { background-color: #00964d; z-index: 3; height:100%; border-bottom: solid .3em #000000; overflow: hidden; }
.location-search header { padding: 3em 1em 2em; display: block; height: auto; }
.location-search header h3 { color: #FFFFFF; margin-bottom: .25em; font-size: 18px; line-height: 1; padding-bottom: 5px; }
.location-search header .input-container { box-shadow: 0 5px 10px rgba(0,0,0,0.4); display: flex; position: relative; z-index: 5; }
.location-search header input.search-field { color: #363636; background-color: #FFFFFF; font-size: 15px; height: 2em; outline: none; padding: 0 .3em; text-align: center; -webkit-flex: 1 1 auto; -ms-flex: 1 1 auto; flex: 1 1 auto; border: 0; -moz-appearance: none; border-radius: 0; }
.location-search header .submit-btn { color: #FFFFFF; padding: .2em .5em; display: flex; text-align: center; border: 0; font-size: 16px; outline: 0; /* transition: color .4s ease 0s, background-color .4s ease 0s; */
    -webkit-flex: 0 1 auto; -ms-flex: 0 1 auto; flex: 0 1 auto; background-color: #39424b; -webkit-justify-content: center; -ms-flex-pack: center; justify-content: center; -webkit-align-items: center; -ms-flex-align: center; align-items: center; margin: 0; text-decoration: none; box-shadow: none; }
.no-padd { padding:0 }
.left-padd { padding:0px 0px 0px 15px; }
.right-padd { padding:0px 15px 0px 0px; }
@media(max-width:768px){#opLocation {min-height:500px }.flocoffice__body { height:auto !important; }.flocoffice__body{padding:0;}}
</style>
<div class="row">
  <div class="col-sm-3 col-md-3 col-sm-12 left-padd">
    <div class="location-search">
      <header class="cf">
        <h3>Find a Location</h3>
        <div class="input-container">
          <input class="search-field" value="<?php echo $zipcode ?>" placeholder="Enter City or Zip Code" id="address" type="number" onKeyPress="if(this.value.length==5) return false;">
          <a href="javascript:void(0);" onclick="codeAddress();" class="submit-btn btn v1">Search</a> </div>
      </header>
      <div class="comclean__flocoffice">
        <div class="flocoffice">
        	<div class="flocoffice__body" id="flocoffice__body"></div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-sm-9 col-md-9 col-sm-12 no-padd">
    <div id="opLocation" style="position: relative; overflow: hidden;width:100%;"></div>
  </div>
</div>
<?php return ob_get_clean();}

