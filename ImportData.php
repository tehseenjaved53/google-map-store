<?php
/*
Plugin Name: WP Office Pride
Plugin URI: http://leadzworld.com
Description: Copy data from knack database to wordpress sub-sites
Version: 1.0
Author: M.Tehseen
Author URI: http://leadzworld.com
Text Domain: leadzworld.com
Domain Path: /leadzworld
*/
// error_reporting(E_ALL);
// ini_set('display_errors', 1); 
defined('ABSPATH') or die('<h1 style="top: 50%;width: 100%;text-align: center;position: absolute;">Sorry! You can not access direct file, please contact with admin for furthor detail.</h1>');
define( 'PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PLUGIN_URL', plugin_dir_url( __FILE__ ) ); 
define( 'ADMIN_URL', admin_url('/admin.php') );
$include_Lib = new include_Lib(array('freequote_widget.p' => 'include/widget', 'sc-map.p' => 'include/template', 'shortcode.p' => 'include/template', 'widget.p' => 'include/widget', 'custom_widget.p' => 'include/widget'));
$blogID =  get_current_blog_id();
$franchise = get_blog_option($blogID, 'subsite_id', true);global $wpdb;
$blogData = $wpdb->get_row("SELECT * FROM op_knack_db where franchise_number = '".$franchise."' ");
	
/*function add_new_blog_field($blog_id) {
    switch_to_blog($blog_id);
    $subsite_id_value = $_POST['blog']['subsite_id'];
    update_option( 'subsite_id', $subsite_id_value);

    restore_current_blog();
}
add_action( 'wpmu_new_blog', 'add_new_blog_field', 100);*/

function pg_save_custom_site_options(){
    global $wpdb, $pagenow;
	
    if( 'site-info.php' == $pagenow && isset($_REQUEST['action']) && 'update-site' == $_REQUEST['action'] ){
        if ( isset( $_POST['blog']['url'] ) ){
			update_blog_option($_POST['id'], 'subsite_id', $_POST['blog']['subsite_id']);			
			}
    }
	
	if(isset($_POST['update_data']) and $_POST['update_data'] == 'Save Data' ){
		//echo '<pre>'; print_r($_POST); echo '</pre>'; exit('Good-Smile');
		$data = $data_type = array();
		foreach($_POST as $key => $val){
			if($key != 'page' and $key != 'update_knack_data' and $key != 'id' and $key != 'update_data'){
				$data[$key] = $val;
				$data_type[] = '%s';
				}
			}
		
		if(!empty($data)){
			$result = $wpdb->update('op_knack_db', 
								$data,
								array('id' => $_POST['id']), 
								$data_type,
								array('%d')
							   );
			
			$row = $wpdb->get_row("Select * from op_knack_db where id = '".$_GET['edit']."' "); 
			
							   
			if ($result === false) {$message = 4;}
			if ($result === 0) {$message = 5;}
			if ($result > 0) {$message = 6;}
			
			}
		echo '<script>window.location.href ="'.admin_url('/network/admin.php?page=update_knack_data&message='.$message).'"</script>';exit;
		}
	
	if(isset($_GET['show_data']) and is_admin() and is_user_logged_in() ){global $wpdb;
	// where franchise_number = '".$frachise."' 
	// $sql = $wpdb->get_row("SELECT * FROM op_knack_db where franchise_number = '".$frachise."' ");
	$png = "http://maps.google.com/mapfiles/ms/icons/blue.png";
	$sql = $wpdb->get_results("SELECT * FROM op_knack_db"); 
	echo '<pre>'; print_r($sql); echo '</pre>'; exit('Good-Smile');
	// create json file
	/*$html = '';
	foreach($sql as $data){
		$address = $data->address.' '.$data->city.', '.$data->st.' '.$data->zip.', USA';
		$html .= '[ "'.$data->website_title.'", "'.$data->city.'","'.$address.'", "'.$data->st.'", "'.$data->lat.','.$data->lng.'","'.$png.'"],';
		}
	echo '['.substr($html, 0, -1).']';
	exit;*/
	
	
	/*$sql = $wpdb->get_results("SELECT * FROM op_knack_db where lat = '' OR lat is NULL "); 
	if(!empty($sql)){
		foreach($sql as $data){
			$address = $data->address.' '.$data->city.', '.$data->st.' '.$data->zip.', USA';
		$node = return_lat_lng($address);
		echo "UPDATE `op_knack_db`
								  SET `lat` = '".$node['lat']."',
									`lng` = '".$node['lng']."'
								  WHERE `id` = '".$data->id."'
								 <br /><br />";
			$wpdb->query("UPDATE `op_knack_db`
								  SET `lat` = '".$node['lat']."',
									`lng` = '".$node['lng']."'
								  WHERE `id` = '".$data->id."'
								 ");
			}
		exit;}*/
	}

}
add_action('admin_init', 'pg_save_custom_site_options');

function admin_footer_hook(){
	global $pagenow;
    if( 'site-info.php' == $pagenow and isset($_GET['id'])){
		echo '<script>jQuery(function(){jQuery("#subsite_id").val("'.get_blog_option($_GET['id'], 'subsite_id').'")})</script>';
		}
	}
add_action('admin_footer', 'admin_footer_hook');

function wpmu_add_add_field_blog_page() {
    wp_register_script('script', PLUGIN_URL.'/assests/js/script.js');
    wp_enqueue_script('script'); 
}
// add_action( "admin_print_scripts-site-new.php", 'wpmu_add_add_field_blog_page' );
add_action( "admin_print_scripts-site-info.php", 'wpmu_add_add_field_blog_page' );

add_action( 'network_admin_menu', 'action_network_admin_menu', 10, 2 );
function action_network_admin_menu() {$myIcon = '';
	add_menu_page("Office Pride", "Office Pride", 10, "import_knack_data", "import_knack_data_page", $myIcon);
	add_submenu_page("import_knack_data", "Import Knack Data", "Import Knack Data", 10, "import_knack_data", 'import_knack_data_page'); 
	add_submenu_page("import_knack_data", "Update Knack Data", "Update Knack Data", 10, "update_knack_data", 'update_knack_data_page'); 
}
function import_knack_data_page(){include(PLUGIN_DIR.'include/import_knack_data_page.php');}
function update_knack_data_page(){include(PLUGIN_DIR.'include/update_knack_data_page.php');}

// import member
if(isset($_POST['import_data']) and isset($_FILES['upload_attachment'])){
		
		if($_POST['import_data'] == 'Import Data' and $_FILES['upload_attachment']['name'] != ''){global $wpdb;
			header('Content-Type: text/html; charset=UTF-8');
			$csvfile = $_FILES['upload_attachment']['name'];
			$info = pathinfo($csvfile);
			if($info['extension'] != 'csv'){
				echo '<script>window.location.href ="'.admin_url('/network/admin.php?page=import_knack_data&message=2').'"</script>';exit;
				}
			
			$extention = $info['extension'];
			$destination = PLUGIN_DIR."csv/" . $csvfile; 
			move_uploaded_file($_FILES['upload_attachment']['tmp_name'], $destination);
			
			$i = 1;
			if (($handle = fopen(PLUGIN_DIR.'csv/'.$csvfile, "r")) === FALSE) return;
    		while (($data = fgetcsv($handle, 100000)) !== FALSE) {
				if($i == 1){
				   $wpdb->query("TRUNCATE TABLE op_knack_db");
				  }	
				  
			    if($i != 1 and trim($data[0]) != ''){
					
				  /*if(!empty($data[6])){ $lat_lng = return_lat_lng($data[6].' '.$data[7].', '.$data[9].' '.$data[8].', USA'); }
				  	else{ $lat_lng = array('lat' => '', 'lng' => ''); }*/
				  
				  $wpdb->insert(
						 'op_knack_db', 
						   array('franchise_number' 	=> $data[0], 
								 'owner_name' 	=> $data[1], 
								 'website_title' 		=> $data[2], 
								 'website_url'		=> $data[3],
								 'updated_title'	=> $data[4], 
								 'updated_url' => $data[5],
								 'address' => $data[6],
								 'city' => $data[7],
								 'zip' => $data[8],
								 'st' => $data[9],
								 'phone' => $data[10],
								 'fac_member' => $data[11],
								 'seo' => $data[12],
								 'account_manager' => $data[13],
								 'notes' => $data[14],
								 'hireology' => $data[15],
								 'status' => $data[16],
								 'status_date_change' => $data[17],
								 'main_contact_email' => $data[18],
								 'monthly_blog' => $data[19],
								 'owner_page_status' => $data[20],
								 'careerplug_code' => $data[21],
								 'hireology_code' => $data[22],
								 'dated' => date('Y-m-d H:i:s'),
								 'lat' => $data[23],
								 'lng' => $data[24]
			   ), 
						   array( '%s', '%s','%s', '%s', '%s', '%s',  '%s', '%s','%s', '%s', '%s', '%s',  '%s', '%s','%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s') 
				  );
			  }
			  $i++;
			}
			
			unlink($destination); 
			echo '<script>window.location.href ="'.admin_url('/network/admin.php?page=import_knack_data&message=1').'"</script>';exit;
			}
		}

if(isset($_POST['op_google_api_key']) and $_POST['op_google_api_key'] != ''){
	update_option( 'op_google_api_key', $_POST['op_google_api_key'] );
	echo '<script>window.location.href ="'.admin_url('/network/admin.php?page=import_knack_data&message=3').'"</script>';exit;
	}
		
function message_code($code){
	if($code == 1){return array('#46b450', '<strong>All Data Imported Successfully.</strong>');}
	elseif($code == 2){return array('red', '<strong>Uploaded file is not CSV format.</strong>');}
	if($code == 3){return array('#46b450', '<strong>Record Updated Successfully.</strong>');}
	if($code == 4){return array('red', '<strong>Fail, Data not update.</strong>');}
	if($code == 5){return array('#46b450', '<strong>Success, but no rows were updated.</strong>');}
	if($code == 6){return array('#46b450', '<strong>Success, Record Updated Successfully.</strong>');}
	
	}

/* create required tables */
function roster_activate() {global $wpdb;
	/* create users table */
	$user_meta = "CREATE TABLE `op_knack_db` (
					`id` INT(11) NOT NULL AUTO_INCREMENT,
					`franchise_number` TEXT(11) DEFAULT NULL,
					`owner_name` TEXT,
					`website_title` TEXT,
					`website_url` TEXT,
					`updated_title` TEXT,
					`updated_url` TEXT,
					`address` TEXT,
					`city` TEXT,
					`zip` TEXT,
					`st` TEXT,
					`phone` TEXT,
					`fac_member` TEXT,
					`seo` TEXT,
					`account_manager` TEXT,
					`notes` TEXT,
					`hireology` TEXT,
					`status` TEXT,
					`status_date_change` TEXT,
					`main_contact_email` TEXT,
					`monthly_blog` TEXT,
					`owner_page_status` TEXT,
					`careerplug_code` TEXT,
					`hireology_code` TEXT,
					`dated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					`lat` TEXT,
					`lng` TEXT,
					PRIMARY KEY (`id`)
				  )";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($user_meta);
	}
register_activation_hook( __FILE__, 'roster_activate' );


function return_lat_lng($address){
	//echo 'https://maps.google.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false';exit;
	  $geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false');
	  $output= json_decode($geocode);
	  $latitude = $output->results[0]->geometry->location->lat;
	  $longitude = $output->results[0]->geometry->location->lng;
	  
	  return array('lat' => $latitude, 'lng' => $longitude);
	}

Class include_Lib{
	function __construct($array = array()){
		if(!empty($array) and is_array($array)){
			foreach($array as $key=>$val){
				include(PLUGIN_DIR.$val.'/'.$key.'hp');
				}
			  }
		}
	}
add_filter('site_option_active_sitewide_plugins', 'modify_sitewide_plugins');

function modify_sitewide_plugins($value) {
    global $current_blog;
if($_SERVER['REMOTE_ADDR'] == '119.154.169.168'){}
    
    return $value;
}