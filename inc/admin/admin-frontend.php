<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( 'You are not allowed to call this page directly.' );
}
/**
 * OSH Admin Frontend Part
 */
class OSHAdminFrontend
{
  
  public function __construct(){
    
    add_action('wp_enqueue_scripts', array($this,'enqueue_scripts')); //hook to enequeue the javascript to the page.   
    
  }

/*
      Enqueue Frontend JS
  */
  public function enqueue_scripts(){
    
      wp_enqueue_script('admin_js', get_template_directory_uri() . '/assets/js/admin.js', array('jquery'), '1.0', true ); 
      wp_localize_script('admin_ajax_js',  'frontend_ajax_object',  array('ajaxurl' => admin_url('admin-ajax.php')) ); 
  } 
   

}

$admin_frontend = new OSHAdminFrontend();