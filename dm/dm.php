<?php

/*
   Plugin Name: DM Plugin
   Version: 0.1
   Author: JM
   Description: DM Silo plugin
   Text Domain: dm 
   License: GPLv3
  */

session_start();

define("DEBUG_MODE",0); 
define("WNY_DEBUG",0);
define("WNY_PATH",dirname(__FILE__));

require_once(dirname(__FILE__)."/widget.php"); 
function search($search, $subject){
  $pos = strpos($subject, $search); 
  return $pos !== false ? true : false;  
}

if(search("wp-admin",$_SERVER["REQUEST_URI"] )   ){  
  wp_enqueue_script( 'admin_tweak', plugin_dir_url( __FILE__ ) . 'admin_tweak.js?t='.time(), array('jquery'), '1.0.0', true ); 

  
    function dm_init() {  
      $categories = get_categories(array('hide_empty' => false));  
      echo "<select id='select_category' style='display:none;'>";
      foreach($categories as $cat){
        echo "<option value='$cat->name'>Content of ".$cat->name."</option>";
      }
      echo "</select>"; 
  }

  add_action( 'admin_footer', 'dm_init' );
} 





?>