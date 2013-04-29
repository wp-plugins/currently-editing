<?php
/*
Plugin Name: Currently Editing
Plugin URI: http://brandoncamenisch.com
Description: Currently Editing tells whos editing what
Version: 1.01
Author: Brandon Camenisch
Text Domain: currently-editing
Author URI: http://brandoncamenisch.com/
License: GPLv2 or later
*/



// -------------------------------------------------------------------- //
//	Set Up Plugin Constants
// -------------------------------------------------------------------- //

	// NOTE: PLUGINCHIEFCE = PluginChief Currently Editing
	define('CE_URL', plugin_dir_url(__FILE__));
	define('CE_PATH', plugin_dir_path(__FILE__));

// -------------------------------------------------------------------- //
// Gets the variables calls the script and passes variables to js
// -------------------------------------------------------------------- //
  function cescript() {

		if( is_admin() ) {

			global $wpdb,$pagenow, $post;

	      if( 'edit.php' == $pagenow ) {

		      //Vars
		      $string =  end(explode( '=', $_SERVER['QUERY_STRING'])); // Post type
		      $query = "SELECT * FROM $wpdb->postmeta WHERE meta_key = '_edit_lock';";
		      $results = $wpdb->get_results($query);

				if($results === false)
				    return 'error occured'; //<-wont work or display

				if($results != false)

					$editingArray = array();// Define the BLANK array
			    foreach ($results as $result){

					  //Vars
					  $editing 		= 'update-nag';
					  $notEditing = 'notcurrentlyediting';
				  	$lock		 = explode( ':', $result->meta_value );
				  	$current =  current_time('timestamp', 1);
				  	$determineLock = ($lock[0] == $current || $current - $lock[0] <= 60 ? $editing : $notEditing);

					  $editingArray[$result->post_id] =  $determineLock;

			    } //End foreach

				//The script
				wp_enqueue_script('pluginscript', plugins_url('/script.js', __FILE__), array('jquery'));

				//The Style
				wp_enqueue_style('pluginstyle', plugins_url('/currently-editing-style.css', __FILE__));

		 		//The localization to pass the variable array

			  wp_localize_script( 'pluginscript', 'custom',  array( 'editing' => $editingArray ));
		   } //End Page Check
	   } //End if admin
   } //End Function
   add_action( 'admin_head', 'cescript' );