<?php 

/*

Created: LeadSoft

Purpose: create the widget file for showing the location on 

front end. first of all showing all stores on the site

when user search by zip code then based on his current 

location calculate ths 


*/ 

/**

 * Adds Graph widget.

 */

 

 /************************ Register Widget Area *************************************************/

function office_pride_custom_widgets() {

	// Area 1, located Graph

    register_sidebar( array(

        'name' => __( 'Widget Find Your Local Shop', 'office_pride' ),

        'id' => 'op-nearestshop',

        'description' => __( 'Local Shop Widget Area', 'office_pride' ),

		 'before_widget' => '',

        'after_widget' => '',

        'before_title' => '<div class="flocoffice__head"><h2 class="flocoffice__label">',

        'after_title' => '</h2> </div>',

    ) );

	}



/** Register office_pride_widgets_init() on the widgets_init hook. */

add_action( 'widgets_init', 'office_pride_custom_widgets' );





// Register and load the widget

function wpb_load_widget() {

    register_widget( 'wpb_widget' );

}

add_action( 'widgets_init', 'wpb_load_widget' );

 

// Creating the widget 

class wpb_widget extends WP_Widget {

 

function __construct() {

parent::__construct(

 

// Base ID of your widget

'wpb_widget', 

 

// Widget name will appear in UI

__('Store Location Widget', 'wpb_widget_domain'), 

 

// Widget description

array( 'description' => __( 'Store Location Widget', 'wpb_widget_domain' ), ) 

);

}

 

// Creating widget front-end

 

public function widget( $args, $instance ) {add_action('wp_footer', 'footer_script');
$op_widget = new Widget_location();
$node = $op_widget->current_location_lat_lng();

$title = apply_filters( 'widget_title', $instance['title'] );

 

// before and after widget arguments are defined by themes

echo $args['before_widget'];

if ( ! empty( $title ) )

echo $args['before_title'] . $title . $args['after_title'];

 

// This is where you run the code and display the output
/*wp_enqueue_script( 'google-location', 'https://maps.googleapis.com/maps/api/js?libraries=geometry&key='.$op_google_api_key.'&sensor=true', array ( 'jquery' ), 1.1, true);
wp_enqueue_script('google-location');*/

 ?>
 <div class="flocoffice__foo">
                    <p class="flocoffice__foo-text">
					<a data-lat="<?php echo $node['lat']?>" data-lng="<?php echo $node['lng']?>" class="nearLocationLtn flocoffice__foo-text-link " href="#" data-modal="zip-code">Find location near me</a>
					</p>
                  </div>
    <style>#opLocation{position: relative; overflow: hidden;display:none;}</style>
	<div class="flocoffice__body" id="flocoffice__body"><br />
 	<div class="getquote__top-free-quote">
      <div class="free-quote">
        <form class="free-quote__form LocationSearch" action="#" method="post">
          <button class="free-quote__submit" type="submit">show locations nearest me</button>
          <input class="free-quote__input ZipCodeFild" type="text" placeholder="Zip code" maxlength="5">
        </form>
      </div>
    </div><br /><br /><br />
	</div>
	<div class="flocoffice__foo">
                    <p class="flocoffice__foo-text"><span class="zipcode-box" style="display:none">
					<span class="flocoffice__foo-text-uppercase">Locations near&nbsp;</span><span class="nearzipcode">33815</span>
					&nbsp;-&nbsp;<a class="flocoffice__foo-text-link js-open-modal" href="#" data-modal="zip-code">Search a different zip code ></a>
					</span></p>
                  </div>
 <?php 

echo $args['after_widget'];

}

         

// Widget Backend 

public function form( $instance ) {

if ( isset( $instance[ 'title' ] ) ) {

$title = $instance[ 'title' ];

}

else {

$title = __( 'FIND YOUR LOCAL OFFICE PRIDE', 'wpb_widget_domain' );

}

/*if ( isset( $instance[ 'distance' ] ) ) {

$distance = $instance[ 'distance' ];

}

else {

$distance = __( '30', 'wpb_widget_domain' );

}*/

// Widget admin form

?>

<p>

<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 

<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />



<!--<label for="<?php //echo $this->get_field_id( 'distance' ); ?>"><?php //_e( 'Distance:' ); ?></label> 

<input class="widefat" id="<?php //echo $this->get_field_id( 'distance' ); ?>" name="<?php //echo $this->get_field_name( 'distance' ); ?>" type="number" value="<?php //echo esc_attr( $distance ); ?>" />-->

</p>

<?php 

}

     

// Updating widget replacing old instances with new

public function update( $new_instance, $old_instance ) {

$instance = array();

$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

//$instance['distance'] = ( ! empty( $new_instance['distance'] ) ) ? strip_tags( $new_instance['distance'] ) : '';

return $instance;

}

} // Class wpb_widget ends here
function update_store_location_data(){global $wpdb;$drawMap = 'false';
	
	$data = json_decode(stripslashes($_POST['param']), true);//  echo $current_user->ID.'-=-'.$data['mention'];
	$html = $location = '';
	$png = network_site_url().'wp-content/plugins/wpOfficePride/include/template/assests/img/single-pin.png';
	
	$op_widget = new Widget_location();
	if($data['lat'] == 'onload'){
		$sql = $wpdb->get_results("SELECT * FROM op_knack_db"); 
	}
	elseif($data['lat'] != '' and $data['lng'] != '' and $data['lat'] != 'onload'){
		$sql = $op_widget->return_closes_data(array($data['lat'], $data['lng']));
		}
	if(!empty($sql)){
	
	
	$i = 1;
	foreach($sql as $data){$drawMap = 'true';
		$address = $data->address;
		$location .= '["'.$data->website_title.'","'.$data->website_url.'","'.$address.'","'.$data->phone.'","'.$data->lat.','.$data->lng.'","'.$png.'","'.$data->city.'","'.$data->zip.'","'.$data->st.'"],';
		
		if($i == 1){$nearzip = $data->zip; $phone = $data->phone;}
		
		$address = $data->address.' '.$data->city.', '.$data->st.' '.$data->zip;

		$html .= '<div class="flocoffice__item"><a class="flocoffice__item-place-link" href="'.$data->website_url.'"></a>
				<a class="flocoffice__place" href="'.$data->website_url.'"><img class="flocoffice__place-icon" src="'.site_url().'/wp-content/themes/officepride/img/icons/marker-green.svg" alt="" width="14" height="18">

			  	<div class="flocoffice__place-text">'.$data->website_title.'</div>

			  </a>
			  
			  <div class="flocoffice__item-inner">

				<div class="flocoffice__distance"><span>'.number_format($data->distance,1).'</span><span>&nbsp;Miles</span></div>

				<a class="flocoffice__call" href="'.$data->phone.'"><img class="flocoffice__call-icon" src="'.site_url().'/wp-content/themes/officepride/img/icons/call-square.svg" alt="" width="16" height="16">

				<div class="flocoffice__call-text">'.$data->phone.'</div>

				</a>

				<div class="flocoffice__address">'.$data->address.' <br>

				  '.$data->city.', '.$data->st.' '.$data->zip.'</div>

			  </div></div>';

		$i++; 
		
		}
		$location = '['.substr($location, 0, -1).']';
	}
	
	else{$html = '<h4 style="text-align:center" class="error_location">Record not found near your location.</h4>';}
	// echo $html; exit;
	echo json_encode(array('html' => $html, 'zipcode' => $nearzip, 'locations' => $location, 'phone' => $phone, 'drawMap' => $drawMap));exit;
	}
add_action('wp_ajax_update_store_location_data' , 'update_store_location_data'); 
add_action('wp_ajax_nopriv_update_store_location_data' , 'update_store_location_data');   



function footer_script(){global $wpdb;
	$current = network_site_url().'wp-content/plugins/wpOfficePride/include/template/assests/img/current.png';
	$op_google_api_key = $wpdb->get_var("SELECT option_value FROM ".$wpdb->base_prefix."options where option_name = 'op_google_api_key' ");  ?>
	<script src="https://maps.googleapis.com/maps/api/js?libraries=geometry&key=<?php echo $op_google_api_key?>&sensor=true"></script>    

	<script>
	var geocoder = null;
    var map = null;
    var customerMarker = null;
    var gmarkers = [];
    var closest = [];
    
    function initialize() {
      geocoder = new google.maps.Geocoder();
	  ajaxDrawWidget('USA', 'onload', '');
	}

	function codeAddress(address){ 
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
	
	function DrawCemterPosition(address, map, lat, lng){
		
		geocoder.geocode( { 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			if(address == 'current'){
				 map.setCenter({lat:parseFloat(lat), lng:parseFloat(lng) }); 
				 var pt = new google.maps.LatLng(parseFloat(lat),parseFloat(lng));
				 }
			else{
				map.setCenter(results[0].geometry.location); 
				var pt = new google.maps.LatLng(parseFloat(lat),parseFloat(lng));
				}
		  	map.setZoom(8);
			  
	  if (customerMarker) customerMarker.setMap(null);
		  customerMarker = new google.maps.Marker({
			  map: map,
			  position: pt,
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
	
	jQuery('body').on('click', '.nearLocationLtn', function(e){e.preventDefault();
		find_me();
		/*var lat = jQuery(this).data('lat');
		var lng = jQuery(this).data('lng');
		ajaxDrawWidget('current', lat, lng);*/
		return false;
		});		
	
	function find_me() {
	  if ( navigator && navigator.geolocation ) {
		navigator.geolocation.getCurrentPosition( geo_success, geo_error,{timeout:10000} );
	  } else {
		alert( "Your browser sucks balls and so do you!");
	  }
	}
 
	function geo_success( position ) {
	  printLatLong( position.coords.latitude, position.coords.longitude );
	  ajaxDrawWidget('current',  position.coords.latitude, position.coords.longitude );
	  //ajaxDrawWidget('current',  28.5383, -81.3792 );
	}
 
	// The PositionError object returned contains the following attributes:
	// code: a numeric response code
	// PERMISSION_DENIED = 1
	// POSITION_UNAVAILABLE = 2
	// TIMEOUT = 3
	// message: Primarily for debugging. It's recommended not to show this error
	// to users.
	function geo_error( err ) {
	  if ( err.code == 1 ) {
		error( "The user denied the request for location information." )
		return false;
	  } else if ( err.code == 2 ) {
		error( "Your location information is unavailable." ); return false;
	  } else if ( err.code == 3 ) {
		error( "The request to get your location timed out." ); return false;
	  } else {
		error( "An unknown error occurred while requesting your location." ); return false;
	  }
	}
	 
	// Output lat and long.
	function printLatLong( lat, long ) {
	  console.log( "Lat: " + lat +  "Lng: " + long );
	}
	 
	function error( msg ) {
	  alert( msg );
	}
			
	function ajaxDrawWidget(address, lat, lng){
		jQuery.ajax({
					 type: "POST",
					 dataType : "JSON", 
					 ContentType: "application/json", 
					 data: {'action':'update_store_location_data','param':JSON.stringify({'lat':lat, 'lng':lng})}, 
					 url: "<?php echo admin_url(); ?>admin-ajax.php",
					 success: function(r) {
						
						// if check for onload map
						if(address != 'USA'){
							jQuery('.flocoffice__body').html(r.html);
						 
						if(address == 'current'){ 
							jQuery('.nearzipcode').text(r.zipcode);
							jQuery('.zipcode-box').removeAttr('style');
							}
							
					    if(r.zipcode != '' && r.zipcode != null && r.zipcode != 'undefined' && address != 'current'){
							jQuery('.nearzipcode').text(address);
							jQuery('.zipcode-box').removeAttr('style');
							}
						
						if(document.getElementById("NearStorePhone") !== null){
							jQuery('#opLocation').css('height', '222px');
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
						
							} 
						
						if(r.drawMap == 'true'){
							var locations = jQuery.parseJSON(r.locations);  
						/*  ###########################################################  */
						
						if(lat == ''){
							var cent_lat = parseFloat(37.0902); var cent_lng = parseFloat(95.7129);
							}else{ var cent_lat = parseFloat(lat); var cent_lng = parseFloat(lng); }
						
						  var numberOfResults = 3; 
							map = new google.maps.Map(document.getElementById('opLocation'), 
								{       
									zoom: 8,
									//minZoom: 1,
									zoomControl:true,
									center: new google.maps.LatLng(cent_lat, cent_lng),//37.0902, 95.7129  52.6699927, -0.7274620
									mapTypeId: google.maps.MapTypeId.ROADMAP,
									styles: [{"featureType":"administrative","elementType":"all","stylers":[{"saturation":"-100"}]},{"featureType":"administrative.province","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","elementType":"all","stylers":[{"saturation":-100},{"lightness":"50"},{"visibility":"simplified"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":"-100"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"all","stylers":[{"lightness":"30"}]},{"featureType":"road.local","elementType":"all","stylers":[{"lightness":"40"}]},{"featureType":"transit","elementType":"all","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]},{"featureType":"water","elementType":"labels","stylers":[{"lightness":-25},{"saturation":-100}]}]
								});       
						    
							DrawCemterPosition(address, map, lat, lng);
							
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
						if(r.drawMap == 'false'){jQuery('#opLocation').html('');}
						
						if(address == 'USA'){
							jQuery('#opLocation').html('').css('display', 'block');
							}
						},
					 error: function(XMLHttpRequest, textStatus, errorThrown){}
				});
	}
	
	
	google.maps.event.addDomListener(window, 'load', initialize);
	
    </script>
<?php }