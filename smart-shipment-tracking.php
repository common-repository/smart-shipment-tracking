<?php
/*
    Plugin Name: Smart Shipment Tracking
    Plugin URI: https://www.triadmark.com/
    Description: Complete your customers order by using Smart Shipping Tracking Plugin by adding Tracking Numbers of your customers orders and to keep your customer updated by SMS or Email!
    Author: Triad Mark 
    Version: 1.0.3
    Author URI: https://www.triadmark.com
    * WC tested up to: 5.0.0
*/


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


define( 'wfsxc3dsa_pluginversion', '1.0.3' );
define( 'wfsxc3dsa_qweaw', 'smart-shipment-tracking' );




if (!defined('wfsxc3dsa_pluginbasepath')) define('wfsxc3dsa_pluginbasepath', plugin_dir_path( __FILE__ ));

function wfsxc3dsa_activatewootracking() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-smart-shipment-tracking-activator.php';
	Tracking_Addon_Activator::activate(wfsxc3dsa_qweaw,wfsxc3dsa_pluginversion);

	
}

function wfsxc3dsa_deactivatewootracking() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-smart-shipment-tracking-deactivator.php';
	Tracking_Addon_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'wfsxc3dsa_activatewootracking' );
register_deactivation_hook( __FILE__, 'wfsxc3dsa_deactivatewootracking' );


require plugin_dir_path( __FILE__ ) . 'includes/class-smart-shipment-tracking.php';


$plugin = new wfsxc3dsa_Tracking_Addon();
$plugin->run();



require_once('admin/analytics/class.analytics.php');
$m=array(

    array(

        "page"=>"contact",
        "position"=>"submenu",
        "show"=>true
    ),

    array(
        "page"=>"support",
        "position"=>"submenu",
        "show"=>true
    )

);
$analytics = new Triad_Mark_analytics("Smart Shipment Tracking", "smart-shipment-tracking", "smart-shipment-tracking/smart-shipment-tracking.php",wfsxc3dsa_pluginversion,"smart-shipment-tracking",$m);

