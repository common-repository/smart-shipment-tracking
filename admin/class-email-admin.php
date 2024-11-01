<?php

class wfsxc_Tracking_Email_Admin {


	private $wfsxc3dsa_qweaw;


	private $version;


	public function __construct( $wfsxc3dsa_qweaw, $version ) {

		global $wpdb;
		$this->wfsxc3dsa_qweaw = $wfsxc3dsa_qweaw;
		$this->version = $version;
		$this->slug="wfsxc";


		if (!defined('EMAIL_TEMPLATE_PATH')) define('EMAIL_TEMPLATE_PATH', untrailingslashit(wfsxc3dsa_pluginbasepath) . '/admin/');

	}


	function add_shipped_order_woocommerce_email( $email_classes ) {

	    // include our custom email class
	    require( 'email/class-woo-shipped-order-email.php' );

	    // add the email class to the list of email classes that WooCommerce loads
	    $email_classes['wfsxc_Shipped_Order_Email'] = new wfsxc_Shipped_Order_Email();
	    


	    return $email_classes;

	}




	


	



	

	

	

}
