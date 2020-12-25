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

 

public function widget( $args, $instance ) {$op_widget = new Widget_location();

$title = apply_filters( 'widget_title', $instance['title'] );

 

// before and after widget arguments are defined by themes

echo $args['before_widget'];

if ( ! empty( $title ) )

echo $args['before_title'] . $title . $args['after_title'];

 

// This is where you run the code and display the output

$node = $op_widget->current_location_lat_lng();

if($node){

$sql = $op_widget->return_closes_data(array($node['lat'], $node['lng']));

if(!empty($sql)){

	echo '<div class="flocoffice__body">';

	foreach($sql as $reslut){

		$address = $reslut->address.' '.$reslut->city.', '.$reslut->st.' '.$reslut->zip;

		echo '<div class="flocoffice__item"><a class="flocoffice__item-place-link" href="'.$reslut->website_url.'"></a>';

		echo '<a class="flocoffice__place" href="'.$reslut->website_url.'"><img class="flocoffice__place-icon" src="'.site_url().'/wp-content/themes/officepride/img/icons/marker-green.svg" alt="" width="14" height="18">

			  	<div class="flocoffice__place-text">'.$reslut->website_title.'</div>

			  </a>';

		echo '<div class="flocoffice__item-inner">

				<div class="flocoffice__distance"><span>'.number_format($reslut->distance,1).'</span><span>&nbsp;Miles</span></div>

				<a class="flocoffice__call" href="'.$reslut->phone.'"><img class="flocoffice__call-icon" src="'.site_url().'/wp-content/themes/officepride/img/icons/call-square.svg" alt="" width="16" height="16">

				<div class="flocoffice__call-text">'.$reslut->phone.'</div>

				</a>

				<div class="flocoffice__address">'.$reslut->address.' <br>

				  '.$reslut->city.', '.$reslut->st.' '.$reslut->zip.'</div>

			  </div></div>';

	

		}

	echo '
		</div>

		  ';

	}

}else{echo '<h4 class="error_location">Your Current Location Is Incorrect.</h4>';}

 ?>

 	

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