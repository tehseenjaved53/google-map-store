<?php 

/*

Created: LeadSoft

Purpose: create the widget file for showing the location on 

front end. first of all showing all stores on the site

when user search by zip code then based on his current 

location calculate ths 

Created: 7-5-2018

*/ 

/**

 * Adds Graph widget.

 */

class Widget_location{

	public function current_location_lat_lng() {

		$lat = $lng = '';//.$_SERVER['REMOTE_ADDR']

		if($json = @file_get_contents("http://www.geoplugin.net/json.gp?ip=".$_SERVER['REMOTE_ADDR'])) {

		  $obj = json_decode($json);

		  if(isset($obj->geoplugin_latitude) && $obj->geoplugin_latitude != false) {

			$lat = $obj->geoplugin_latitude;

		  }

		  if(isset($obj->geoplugin_longitude) && $obj->geoplugin_longitude != false) {

			$lng = $obj->geoplugin_longitude;

		  }

		 return array('lat' => $lat, 'lng' => $lng);

		}else{return false;}

	  }

	

	public function return_closes_data($distance) {global $wpdb; // HAVING distance < '.$distance.'


		$sql = $wpdb->get_results('SELECT *,

									( 6371 * ACOS( COS( RADIANS('.$distance[0].') ) * COS( RADIANS( lat ) ) * COS( RADIANS( lng ) - RADIANS("'.$distance[1].'") ) + SIN( RADIANS('.$distance[0].') ) * SIN( RADIANS( lat ) ) ) ) AS distance

									FROM op_knack_db

									HAVING distance < 100 

									ORDER BY distance ASC 

									LIMIT 0 , 3

									');

		return $sql;

		}

	}