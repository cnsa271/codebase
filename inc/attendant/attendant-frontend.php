<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( 'You are not allowed to call this page directly.' );
}
/**
 * OSH Attendant Frontend Part
 */
class OSHAttendantFrontend
{
  
  public function __construct(){
    
    add_action('wp_enqueue_scripts', array($this,'enqueue_scripts')); //hook to enequeue the javascript to the page.   
    
  }

/*
      Enqueue Frontend JS
  */
  public function enqueue_scripts(){
    
      wp_enqueue_script('attendant_js', get_template_directory_uri() . '/assets/js/attendant.js', array('jquery'), '1.0', true ); 
      wp_localize_script('attendant_ajax_js',  'frontend_ajax_object',  array('ajaxurl' => admin_url('admin-ajax.php')) ); 
  } 
   

}

$attendant_frontend = new OSHAttendantFrontend();