<?php 

/*

Created: LeadSoft

Purpose: Free quote widget which redirect user to location page on

front end. first of all showing all stores on the site

when user search by zip code then based on his current 

location calculate ths 

Created: 7-5-2018

*/ 

/**

 * Adds Graph widget.

 */

 

 /************************ Register Widget Area *************************************************/

function office_pride_FreeQuote() {

	// Area 1, located Graph

    register_sidebar( array(

        'name' => __( 'Widget Free Quote', 'office_pride' ),

        'id' => 'op-freequote',

        'description' => __( 'Widget Free Quote Area', 'office_pride' ),

		 'before_widget' => '',

        'after_widget' => '',

        'before_title' => '<div class="flocoffice__head"><h2 class="flocoffice__label">',

        'after_title' => '</h2> </div>',

    ) );

	}



/** Register office_pride_widgets_init() on the widgets_init hook. */

add_action( 'widgets_init', 'office_pride_FreeQuote' );





// Register and load the widget

function wp_freequote_load_widget() {

    register_widget( 'wp_freequote_widget' );

}

add_action( 'widgets_init', 'wp_freequote_load_widget' );

 

// Creating the widget 

class wp_freequote_widget extends WP_Widget {

 

function __construct() {

parent::__construct(

 

// Base ID of your widget

'wp_freequote_widget', 

 

// Widget name will appear in UI

__('Free Quote Widget', 'wp_freequote_widget_domain'), 

 

// Widget description

array( 'description' => __( 'Free Quote Widget', 'wp_freequote_widget_domain' ), ) 

);

}

 

// Creating widget front-end

 

public function widget( $args, $instance ) {

$title = apply_filters( 'widget_title', $instance['title'] );

 

// before and after widget arguments are defined by themes

echo $args['before_widget'];

if ( ! empty( $title ) )

echo $args['before_title'] . $title . $args['after_title'];

 

// This is where you run the code and display the output
$page_id=wp_page_id('page-fullwidth.php');
 ?>

<?php if(isset($_GET['zipcode']) and is_numeric($_GET['zipcode'])){
		$zipcode=$_GET['zipcode'];
	}else{$zipcode='';} ?>        
<div class="free-quote">
                      <form class="free-quote__form" action="<?php echo site_url('/locations/'); ?>" method="get">
                        <input class="free-quote__submit" type="submit" value="Free quote" />
                        <input class="free-quote__input" name="zipcode" value="<?php echo $zipcode ?>" placeholder="Zip code" type="number" onKeyPress="if(this.value.length==5) return false;" />
                      </form>
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

$title = __( 'FIND YOUR LOCAL OFFICE PRIDE', 'wp_freequote_widget_domain' );

}

/*if ( isset( $instance[ 'distance' ] ) ) {

$distance = $instance[ 'distance' ];

}

else {

$distance = __( '30', 'wp_freequote_widget_domain' );

}*/

// Widget admin form

?>

<p>

<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Free Quote' ); ?></label> 

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

} // Class wp_freequote_widget ends here

function wp_page_id($page){
	$post_arr = get_pages(array(
				'meta_key' => '_wp_page_template',
				'meta_value' => $page
			));
	return get_permalink($post_arr[0]->ID);
	}