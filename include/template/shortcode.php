<?php 

/*

Created: LeadSoft

Purpose: create the sortcode file for showing the sub

site detail on front end side

based on site ids and its also

use for echo data when direct call to this funtion

but best way to use shortcode for backend and frontend.

*/

add_shortcode("Display_Site_Meta", "Display_Site_Meta"); 	

function Display_Site_Meta( $atts ){global $blogData, $blogID; 

	if($blogID != 1){

	if(is_array($atts) and !empty($atts['column'])){

		extract( shortcode_atts( array(

								  'column' 	=> ''

							  ), $atts ) );

		}else{$column = $atts;}

	

	if($column == 'address'){

		 return $blogData->address.', '.$blogData->city.' '.$blogData->zip .' '. $blogData->st; 

		}

	if(!empty($blogData->$column) and isset($blogData->$column)){return $blogData->$column; }

	else{}

	if(is_array($atts) and !empty($atts['column'])){}

	}

  } ?>