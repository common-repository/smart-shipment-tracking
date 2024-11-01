<?php

class Tracking_Addon_Deactivator {


	public static function deactivate() {
		global $wpdb;
	    $table_name = $wpdb->prefix."wfsxc_shippment_provider";
	    $sql = "DROP TABLE IF EXISTS $table_name;";
	    $wpdb->query($sql);
	    delete_option("1.0");
	}

}
