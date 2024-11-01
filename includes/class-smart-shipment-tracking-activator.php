<?php

class Tracking_Addon_Activator {



	public static function activate($wfsxc3dsa_qweaw,$PLUGIN_VERSION) {
		update_option( 'create_shipped_status', 1 );
		update_option( 'send_email', 1 );
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-smart-shipment-tracking-install.php';
		$shipment= new wfsxc_Install($wfsxc3dsa_qweaw,$PLUGIN_VERSION);

		
	}


	

	

}
