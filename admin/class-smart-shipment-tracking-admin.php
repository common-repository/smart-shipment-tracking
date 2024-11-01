<?php

class wfsxc_Smart_Tracking_Addon_Admin {

	private $wfsxc3dsa_qweaw;

	private $version;


	public function __construct( $wfsxc3dsa_qweaw, $version ) {

		global $wpdb;
		$this->wfsxc3dsa_qweaw = $wfsxc3dsa_qweaw;
		$this->version = $version;
		$this->table=$wpdb->prefix."wfsxc_shippment_provider";
		$this->slug="wfsxc";

		$this->hooks();
		

	}


	private function hooks(){
		
		require_once('partials/admin_get_provider_html.php');	

		add_action( 'wp_ajax_'.$this->slug.'_filter_shipiing_provider_by_status', array( $this, 'filter_shipiing_provider_by_status') );
		add_action( 'wp_ajax_'.$this->slug.'_add_custom_shipment_provider', array( $this, 'add_custom_shipment_provider') );
		add_action( 'wp_ajax_'.$this->slug.'_shipping_provider_delete', array( $this, 'shipping_provider_delete') );
		add_action( 'wp_ajax_'.$this->slug.'_update_custom_shipment_provider', array( $this, 'update_custom_shipment_provider') );
		add_action( 'wp_ajax_'.$this->slug.'_get_provider_details', array( $this, 'get_provider_details') );
		add_action( 'wp_ajax_'.$this->slug.'_update_shipment_status', array( $this, 'update_shipment_status') );
		add_action( 'wp_ajax_'.$this->slug.'_upload_tracking_csv', array( $this, 'upload_tracking_csv') );
		add_action( 'wp_ajax_'.$this->slug.'_export_tracking_csv', array( $this, 'export_tracking_csv') );
		add_action( 'wp_ajax_'.$this->slug.'_all_provider_status_active', array( $this, 'all_provider_status_active') );
		add_action( 'wp_ajax_'.$this->slug.'_all_provider_status_inactive', array( $this, 'all_provider_status_inactive') );

		add_action( 'wp_ajax_'.$this->slug.'_update_default_provider', array( $this, 'update_default_provider') );


		add_action( 'champaklal', array( $this, 'mm_email_before_order_table'), 10, 4 );
		add_action("admin_init",array( $this, 'general_form_response'));

		

	}


	function update_default_provider(){
		$checked=sanitize_text_field($_POST['checked']);
		$default_provider=sanitize_text_field($_POST['default_provider']);
		if($checked == 1){
			update_option("wfsxc_default_provider", $default_provider );
		} else{
			update_option("wfsxc_default_provider", '' );
		}
		exit;
	}



	function all_provider_status_inactive(){
		global $wpdb;
		$data_array = array(
			'display_in_order' => 0,			
		);
		$where_array = array(
			'display_in_order' => 1,			
		);
		$status = 'inactive';
		$wpdb->update( $this->table, $data_array, $where_array);
		update_option("wfsxc_default_provider", '' );
		$default_shippment_providers = $wpdb->get_results( "SELECT * FROM $this->table WHERE display_in_order = 0" );	
		$html = $this->get_provider_html($default_shippment_providers,$status);
		exit;
	}

	function general_form_response(){
		if(isset($_POST['action']) && $_POST['action'] == 'wc_general_form_update'){

			


			if(isset($_POST['include_tracking_info'])){


				$statusesData=sanitize_text_field($_POST['include_tracking_info']);

				foreach ($statusesData as $key => $value) {
					$statusesData[$key]=sanitize_text_field($statusesData[$key]);
				}

				update_option( 'wfsxc_checked_statuses', $statusesData );

			}



			if(isset($_POST['create_shipped_status'])){


				$create_shipped_status=sanitize_text_field($_POST['create_shipped_status']);


				if($create_shipped_status){
					update_option( 'create_shipped_status', 1 );
				}
				else{
					delete_option( 'create_shipped_status');
				}

				

			}






			if(isset($_POST['send_email'])){


				$send_email=sanitize_text_field($_POST['send_email']);


				if($send_email){
					update_option( 'send_email', 1 );
				}
				else{
					delete_option( 'send_email');
				}

				

			}











		}


	}


	


	public function get_provider_details(){
		$id = wc_clean($_POST['provider_id']);
		global $wpdb;
		$shippment_provider = $wpdb->get_results( "SELECT * FROM $this->table WHERE id=$id" );
		$image = wp_get_attachment_url($shippment_provider[0]->custom_thumb_id);
		echo json_encode( array('id' => $shippment_provider[0]->id,'provider_name' => $shippment_provider[0]->provider_name,'provider_url' => $shippment_provider[0]->provider_url,'shipping_country' => $shippment_provider[0]->shipping_country,'custom_thumb_id' => $shippment_provider[0]->custom_thumb_id,'image' => $image) );exit;			
	}

	public function shipping_provider_delete(){				

		$provider_id = wc_clean($_POST['provider_id']);
		if ( ! empty( $provider_id ) ) {
			global $wpdb;
			$where = array(
				'id' => $provider_id,
				'shipping_default' => 0
			);
			$wpdb->delete( $this->table, $where );
		}
		$status = 'custom';
		$default_shippment_providers = $wpdb->get_results( "SELECT * FROM $this->table WHERE shipping_default = 0" );	
		$html = wfsxc_get_provider_html($default_shippment_providers,$status);
		echo $html;exit;
	}

	public function update_custom_shipment_provider(){
		
		global $wpdb;	
		$provider_id = wc_clean($_POST['provider_id']);	
		$data_array = array(
			'shipping_country' => sanitize_text_field($_POST['shipping_country']),
			'provider_name' => sanitize_text_field($_POST['shipping_provider']),
			'ts_slug' => sanitize_title($_POST['shipping_provider']),
			'custom_thumb_id' => sanitize_text_field($_POST['thumb_id']),
			'provider_url' => sanitize_text_field($_POST['tracking_url'])		
		);
		$where_array = array(
			'id' => $provider_id,			
		);
		$wpdb->update( $this->table, $data_array, $where_array );
		$status = 'custom';
		$default_shippment_providers = $wpdb->get_results( "SELECT * FROM $this->table WHERE shipping_default = 0" );	
		$html = wfsxc_get_provider_html($default_shippment_providers,$status);
		echo $html;exit;
	}

	function add_custom_shipment_provider(){
		
		global $wpdb;
		$shipping_provider=sanitize_text_field($_POST['shipping_provider']);
		$woo_shippment_table_name = $this->table;
		$provider_slug = $this->create_slug($shipping_provider);		
		if($provider_slug == ''){
			$provider_slug = sanitize_text_field($shipping_provider);
		}
		
		$data_array = array(
			'shipping_country' => sanitize_text_field($_POST['shipping_country']),
			'provider_name' => sanitize_text_field($_POST['shipping_provider']),
			'ts_slug' => $provider_slug,
			'provider_url' => sanitize_text_field($_POST['tracking_url']),
			'custom_thumb_id' => sanitize_text_field($_POST['thumb_id']),			
			'display_in_order' => 1,
			'shipping_default' => 0,
		);
		

		$alreadyExist=$wpdb->get_results( "SELECT * FROM $this->table WHERE provider_name='".$data_array['provider_name']."' AND shipping_country = '".$data_array['shipping_country']."' " );



		if(!count($alreadyExist)){
			$result = $wpdb->insert( $woo_shippment_table_name, $data_array );
			
		}


		
		
		$status = 'custom';
		$default_shippment_providers = $wpdb->get_results( "SELECT * FROM $this->table WHERE shipping_default = 0" );
			
		$html = wfsxc_get_provider_html($default_shippment_providers,$status);
		echo $html;exit;		
	}


	public function all_provider_status_active(){
		global $wpdb;
		$data_array = array(
			'display_in_order' => 1,			
		);
		$where_array = array(
			'display_in_order' => 0,			
		);
		$wpdb->update( $this->table, $data_array, $where_array);
		$status = 'active';
		$default_shippment_providers = $wpdb->get_results( "SELECT * FROM $this->table WHERE display_in_order = 1" );	
		$html = $this->get_provider_html($default_shippment_providers,$status);
		exit;
	}


	public function filter_shipiing_provider_by_status(){		
		$status = sanitize_text_field($_POST['status']);
		global $wpdb;		
		if($status == 'active'){			
			$default_shippment_providers = $wpdb->get_results( "SELECT * FROM $this->table WHERE display_in_order = 1" );	
		}
		if($status == 'inactive'){			
			$default_shippment_providers = $wpdb->get_results( "SELECT * FROM $this->table WHERE display_in_order = 0" );	
		}
		if($status == 'custom'){			
			$default_shippment_providers = $wpdb->get_results( "SELECT * FROM $this->table WHERE shipping_default = 0" );	
		}
		if($status == 'all'){
			$status = '';
			$default_shippment_providers = $wpdb->get_results( "SELECT * FROM $this->table" );	
		}


		$html = wfsxc_get_provider_html($default_shippment_providers,$status);
		echo $html;exit;		
	}


	function update_shipment_status(){			
		global $wpdb;
		$checked=sanitize_text_field($_POST['checked']);
		$id=sanitize_text_field($_POST['id']);		
		$woo_shippment_table_name = $this->table;
		$success = $wpdb->update($woo_shippment_table_name, 
			array(
				"display_in_order" => $checked,
			),	
			array('id' => $id)
		);
		exit;	
	}

	public static function create_slug($text){
		// replace non letter or digits by -
		$text = preg_replace('~[^\pL\d]+~u', '-', $text);
		
		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		
		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);
		
		// trim
		$text = trim($text, '-');
		
		// remove duplicate -
		$text = preg_replace('~-+~', '-', $text);
		
		// lowercase
		$text = strtolower($text);
		
		if (empty($text)) {
			return '';
		}
		
		return $text;
	}


	public function enqueue_styles() {


		wp_enqueue_style( 'smart_shipment_tracking_admin',  plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), $this->version);
		wp_enqueue_style( 'front_style',  plugin_dir_url( __FILE__ ) . 'css/front.css', array(),$this->version );
		wp_enqueue_style( 'woocommerce_admin_styles' );
		wp_enqueue_style('thickbox');

		wp_enqueue_style( 'smart_shipment_tracking_daterangepicker',  plugin_dir_url( __FILE__ ) . 'css/daterangepicker.css', array(), $this->version);

		wp_enqueue_style( 'smart_shipment_tracking_datatables',  plugin_dir_url( __FILE__ ) . 'css/datatables.min.css', array(), $this->version);

		wp_enqueue_style( 'smart_shipment_tracking_modal',  plugin_dir_url( __FILE__ ) . 'css/jquery.modal.min.css', array(), $this->version);

		
		
		
	}


	public function enqueue_scripts() {




		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';


		wp_register_script( 'select2', WC()->plugin_url() . '/assets/js/select2/select2.full' . $suffix . '.js', array( 'jquery' ), '4.0.3' );
		wp_enqueue_script( 'select2');

		
		wp_enqueue_style( 'front_style',  plugin_dir_url( __FILE__ ) . 'assets/css/front.css', array(), $this->version );	
		
		wp_enqueue_script( 'smart-shipment-tracking-js', plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), $this->version);
		
		wp_localize_script( 'smart-shipment-tracking-js', 'smart_admin_js', array(
			'i18n' => array(
				'get_shipment_status_message' => __( 'Get Shipment Status is limited to 100 orders at a time, please select up to 100 orders.', 'woo-advanced-shipment-tracking' ),
			),			
		) );
		
		wp_register_script( 'selectWoo', WC()->plugin_url() . '/assets/js/selectWoo/selectWoo.full' . $suffix . '.js', array( 'jquery' ), '1.0.4' );
		wp_register_script( 'wc-enhanced-select', WC()->plugin_url() . '/assets/js/admin/wc-enhanced-select' . $suffix . '.js', array( 'jquery', 'selectWoo' ), WC_VERSION );
		wp_register_script( 'jquery-blockui', WC()->plugin_url() . '/assets/js/jquery-blockui/jquery.blockUI' . $suffix . '.js', array( 'jquery' ), '2.70', true );
		
		wp_enqueue_script( 'selectWoo');
		wp_enqueue_script( 'wc-enhanced-select');
		
		wp_register_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );
		wp_enqueue_style( 'woocommerce_admin_styles' );
		
		wp_register_script( 'jquery-tiptip', WC()->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip.min.js', array( 'jquery' ), WC_VERSION, true );
		wp_enqueue_script( 'jquery-tiptip' );
		wp_enqueue_script( 'jquery-blockui' );
		wp_enqueue_script( 'wp-color-picker' );		
		wp_enqueue_script( 'jquery-ui-sortable' );		
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');		
		wp_enqueue_style('thickbox');		
		
		wp_enqueue_script( 'material-js', plugin_dir_url( __FILE__ ) . 'js/material.min.js', array( 'jquery' ), $this->version );						
		
		//wp_enqueue_script( 'ajax-queue', plugin_dir_url( __FILE__ ) . 'js/jquery.ajax.queue.js', array( 'jquery' ), $this->version);


				
		wp_enqueue_script( 'smart_shipment_tracking_settings', plugin_dir_url( __FILE__ ) . 'js/settings.js', array( 'jquery','jquery-tiptip','wp-color-picker' ), $this->version );		
		
		wp_enqueue_script( 'front-js', plugin_dir_url( __FILE__ ) . 'js/front.js', array( 'jquery' ), $this->version );
		
		wp_register_script( 'shipment_tracking_table_rows', plugin_dir_url( __FILE__ ) . 'js/shipping_row.js' , array( 'jquery', 'wp-util' ), $this->version );
		wp_localize_script( 'shipment_tracking_table_rows', 'shipment_tracking_table_rows', array(
			'i18n' => array(				
				'data_saved'	=> __( 'Data saved successfully.', 'woo-advanced-shipment-tracking' ),
				'delete_provider' => __( 'Really delete this entry? This will not be undo.', 'woo-advanced-shipment-tracking' ),
				'upload_only_csv_file' => __( 'You can upload only csv file.', 'woo-advanced-shipment-tracking' ),
				'browser_not_html' => __( 'This browser does not support HTML5.', 'woo-advanced-shipment-tracking' ),
				'upload_valid_csv_file' => __( 'Please upload a valid CSV file.', 'woo-advanced-shipment-tracking' ),
			),
			'delete_rates_nonce' => wp_create_nonce( "delete-rate" ),
		) );

		wp_enqueue_script( 'shipment_tracking_table_rows' );
		wp_enqueue_media();	

		wp_enqueue_script( 'smart_shipment_tracking_momentjs', plugin_dir_url( __FILE__ ) . 'js/moment.min.js', array( 'jquery' ), $this->version );	

		wp_enqueue_script( 'smart_shipment_tracking_daterangepicker', plugin_dir_url( __FILE__ ) . 'js/daterangepicker.min.js', array( 'jquery' ), $this->version );

		wp_enqueue_script( 'smart_shipment_tracking_datatables', plugin_dir_url( __FILE__ ) . 'js/datatables.min.js', array( 'jquery' ), $this->version );	

		wp_enqueue_script( 'smart_shipment_tracking_modals', plugin_dir_url( __FILE__ ) . 'js/jquery.modal.min.js', array( 'jquery' ), $this->version );	

		






	}

	function isDate($value) 
	{
		if (!$value) {
			return false;
		}
	
		try {
			new \DateTime($value);
			return true;
		} catch (\Exception $e) {
			return false;
		}
	}


	public function get_formated_order_id($order_id){
		if ( is_plugin_active( 'custom-order-numbers-for-woocommerce/custom-order-numbers-for-woocommerce.php' ) ) {
			$alg_wc_custom_order_numbers_enabled = get_option('alg_wc_custom_order_numbers_enabled');
			if($alg_wc_custom_order_numbers_enabled == 'yes'){
				$args = array(
					'post_type'		=>	'shop_order',			
					'posts_per_page'    => '1',
					'meta_query'        => array(
						'relation' => 'AND', 
						array(
						'key'       => '_alg_wc_custom_order_number',
						'value'     => $order_id
						),
					),
					'post_status' => array('wc-pending', 'wc-processing', 'wc-on-hold', 'wc-completed', 'wc-delivered', 'wc-cancelled', 'wc-refunded', 'wc-failed','wc-bit-payment') , 	
				);
				$posts = get_posts( $args );
				$my_query = new WP_Query( $args );				
				
				if( $my_query->have_posts() ) {
					while( $my_query->have_posts()) {
						$my_query->the_post();
						if(get_the_ID()){
							$order_id = get_the_ID();
						}									
					} // end while
				} // end if
				wp_reset_postdata();	
			}			
		}
		
		if ( is_plugin_active( 'woocommerce-sequential-order-numbers/woocommerce-sequential-order-numbers.php' ) ) {
						
			$s_order_id = wc_sequential_order_numbers()->find_order_by_order_number( $order_id );			
			if($s_order_id){
				$order_id = $s_order_id;
			}
		}
		
		if ( is_plugin_active( 'woocommerce-sequential-order-numbers-pro/woocommerce-sequential-order-numbers.php' ) ) {
			
			// search for the order by custom order number
			$query_args = array(
				'numberposts' => 1,
				'meta_key'    => '_order_number_formatted',
				'meta_value'  => $order_id,
				'post_type'   => 'shop_order',
				'post_status' => 'any',
				'fields'      => 'ids',
			);
			
			$posts = get_posts( $query_args );
			list( $order_id ) = ! empty( $posts ) ? $posts : null;			
		}
		
		if ( is_plugin_active( 'wp-lister-amazon/wp-lister-amazon.php' ) ) {
			$wpla_use_amazon_order_number = get_option( 'wpla_use_amazon_order_number' );
			if($wpla_use_amazon_order_number == 1){
				$args = array(
					'post_type'		=>	'shop_order',			
					'posts_per_page'    => '1',
					'meta_query'        => array(
						'relation' => 'AND', 
						array(
						'key'       => '_wpla_amazon_order_id',
						'value'     => $order_id
						),
					),
					'post_status' => array('wc-pending', 'wc-processing', 'wc-on-hold', 'wc-completed', 'wc-delivered', 'wc-cancelled', 'wc-refunded', 'wc-failed','wc-bit-payment') , 	
				);
				$posts = get_posts( $args );
				$my_query = new WP_Query( $args );				
				
				if( $my_query->have_posts() ) {
					while( $my_query->have_posts()) {
						$my_query->the_post();
						if(get_the_ID()){
							$order_id = get_the_ID();
						}									
					} // end while
				} // end if
				wp_reset_postdata();	
			}			
		}	
		
		if ( is_plugin_active( 'wp-lister/wp-lister.php' ) || is_plugin_active( 'wp-lister-for-ebay/wp-lister.php' )) {
			$args = array(
				'post_type'		=>	'shop_order',			
				'posts_per_page'    => '1',
				'meta_query'        => array(
					'relation' => 'OR', 
					array(
						'key'       => '_ebay_extended_order_id',
						'value'     => $order_id
					),
					array(
						'key'       => '_ebay_order_id',
						'value'     => $order_id
					),					
				),
				'post_status' => array('wc-pending', 'wc-processing', 'wc-on-hold', 'wc-completed', 'wc-delivered', 'wc-cancelled', 'wc-refunded', 'wc-failed','wc-bit-payment') , 	
			);
			$posts = get_posts( $args );
			$my_query = new WP_Query( $args );				
			
			if( $my_query->have_posts() ) {
				while( $my_query->have_posts()) {
					$my_query->the_post();
					if(get_the_ID()){
						$order_id = get_the_ID();
					}									
				} // end while
			} // end if
			wp_reset_postdata();
		}
		return $order_id;
	}


	public function get_tracking_items( $order_id, $formatted = false ) {
		
		global $wpdb;
		$order = wc_get_order( $order_id );		
		if($order){	
			if ( version_compare( WC_VERSION, '3.0', '<' ) ) {			
				$tracking_items = get_post_meta( $order_id, $this->slug.'_shipment_tracking_items', true );
			} else {						
				$order          = new WC_Order( $order_id );		
				$tracking_items = $order->get_meta( $this->slug.'_shipment_tracking_items', true );			
			}
			
			if ( is_array( $tracking_items ) ) {
				if ( $formatted ) {
					foreach ( $tracking_items as &$item ) {
						$formatted_item = $this->get_formatted_tracking_item( $order_id, $item );
						$item           = array_merge( $item, $formatted_item );
					}
				}
				return $tracking_items;
			} else {
				return array();
			}
		} else {
			return array();
		}
	}

	public function get_tracking_item( $order_id, $tracking_id, $formatted = false ) {
		$tracking_items = $this->get_tracking_items( $order_id, $formatted );

		if ( count( $tracking_items ) ) {
			foreach ( $tracking_items as $item ) {
				if ( $item['tracking_id'] === $tracking_id ) {
					return $item;
				}
			}
		}

		return null;
	}


	function export_tracking_csv(){
		



			$daterange=sanitize_text_field($_POST['dates']);
			$daterange=explode("-",$daterange);

			$dateFrom=trim($daterange[0]);
			$dateTo=trim($daterange[1]);




			
			$dateFrom = DateTime::createFromFormat("d/m/y" , $dateFrom);
			$dateFrom= $dateFrom->format('Y-m-d');

			$dateTo = DateTime::createFromFormat("d/m/y" , $dateTo);
			$dateTo= $dateTo->format('Y-m-d');




			$statuses=array();
			foreach ($_POST['status'] as $key => $value) {
				$value=sanitize_text_field($value);
				array_push($statuses, $value);
			}
			
			


			$response=$this->getDataForCsv($dateFrom,$dateTo,$statuses);

			


			$csv=$this->createCsvFile($response);
			 echo plugin_dir_url( __FILE__ )."export_csv.csv";
			wp_die();





	}


	function createCsvFile($response){


		$myfile = fopen(realpath(dirname(__FILE__))."/export_csv.csv", "w") or die("Unable to open file!");
		$txt = "order_id,customer_name,city,tracking_provider,tracking_number,date_shipped,status_shipped\n";
		fwrite($myfile, $txt);

		

		foreach ($response as $key => $orderDetails) {


			$orderDetails['cust_name']=str_replace(',', '', $orderDetails['cust_name']);
			$orderDetails['city']=str_replace(',', '', $orderDetails['city']);

			$str=$orderDetails['orderid'].",";
			$str.=$orderDetails['cust_name'].",";
			$str.=$orderDetails['city'].",";

			if(count($orderDetails['trackingItem'])){


				foreach ($orderDetails['trackingItem'] as $key => $value) {
						$trackingdetails=$value['formatted_tracking_provider'].",";
						$trackingdetails.=$value['tracking_number'].",";
						$trackingdetails.=date_i18n( "d-m-Y", $value['date_shipped'] ).",";
						$trackingdetails.=(($orderDetails['status'] == 'order-shipped')?'1':'0');
						$str2=$str.$trackingdetails."\n";
						fwrite($myfile, $str2);


				}
			}
			else{
				$str.=",,,";
				$str.=(($orderDetails['status'] == 'order-shipped')?'1':'0')."\n";
				fwrite($myfile, $str);
			}

			
		}

		fclose($myfile);

	}

	function getDataForCsv($from,$to,$statuses){
		global $wpdb;
		$query="
		    SELECT DISTINCT oi.order_id
		    FROM {$wpdb->prefix}term_relationships tr
		    INNER JOIN {$wpdb->prefix}term_taxonomy tt
		        ON tr.term_taxonomy_id = tt.term_taxonomy_id
		    INNER JOIN {$wpdb->prefix}terms t
		        ON tt.term_id = t.term_id
		    INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta oim
		        ON tr.object_id = oim.meta_value
		    INNER JOIN {$wpdb->prefix}woocommerce_order_items oi
		        ON oim.order_item_id = oi.order_item_id
		    INNER JOIN {$wpdb->prefix}posts as o
		        ON oi.order_id = o.ID
		    WHERE tt.taxonomy = 'product_cat'
		    AND oim.meta_key = '_product_id'
		    AND o.post_type = 'shop_order'
		    AND o.post_status IN ( '" . implode( "','", $statuses ) . "' )
		    AND o.post_date BETWEEN '".$from."' AND '".$to."'
		";



		$products_ids = $wpdb->get_col($query);



		$data=array();

		foreach ($products_ids as $key => $value) {
			// Get an instance of the WC_Order Object from the Order ID (if required) 
			$order = wc_get_order( $value ); // Get the Customer ID (User ID)
			$row['orderid']=$value; 
			$row['cust_name']=$order->get_billing_first_name();
			$row['city']=$order->get_billing_city();
			$row['status']=$order->get_status();
			$row['trackingItem']=$this->get_tracking_items( $value,true );



			array_push($data, $row);

		}


		return $data;

	}

	function upload_tracking_csv(){			

		
		$replace_tracking_info = sanitize_text_field($_POST['replace_tracking_info']);
		$order_id = sanitize_text_field($_POST['order_id']);			
		
		
		$order_id = $this->get_formated_order_id($order_id);						
		
		$tracking_provider = sanitize_text_field($_POST['tracking_provider']);
		$tracking_number = sanitize_text_field($_POST['tracking_number']);
		$date_shipped=sanitize_text_field($_POST['date_shipped']);
		$date_shipped = str_replace("/","-",$date_shipped);
		
		if(isset($_POST['sku'])){
			$sku = sanitize_text_field($_POST['sku']);
		}
		
		if(isset($_POST['qty'])){
			$qty = sanitize_text_field($_POST['qty']);
		}
		
		if(empty($date_shipped)){
			$date_shipped = date("d-m-Y");
		}
	
		
		global $wpdb;	
		$woo_shippment_table_name = $this->table;		
		$shippment_provider = $wpdb->get_var( "SELECT COUNT(*) FROM $woo_shippment_table_name WHERE provider_name = '".$tracking_provider."'" );
		
		$order_id=sanitize_text_field($_POST['order_id']);
		if($shippment_provider == 0){
			echo '<li class="error">Failed - Invalid Tracking Provider for Order Id - '.$order_id.'</li>';exit;
		}
		if(empty($tracking_number)){
			echo '<li class="error">Failed - Empty Tracking Number for Order Id - '.$order_id.'</li>';exit;
		}
		if(preg_match('/[^a-z0-9- \b]+/i', $tracking_number)){
			echo '<li class="error">Failed - Special character not allowd in tracking number for Order Id - '.$order_id.'</li>';exit;
		}
		if(empty($date_shipped)){
			echo '<li class="error">Failed - Empty Date Shipped for Order Id - '.$order_id.'</li>';exit;
		}	
		if(!$this->isDate($date_shipped)){
			echo '<li class="error">Failed - Invalid Date Shipped for Order Id - '.$order_id.'</li>';exit;
		}	
		
		if($replace_tracking_info == 1){
			$order = wc_get_order($order_id);
			
			if($order){	
				$tracking_items = $this->get_tracking_items( $order_id );			
				
				if ( count( $tracking_items ) > 0 ) {
					foreach ( $tracking_items as $key => $item ) {
						$tracking_number = $item['tracking_number'];						
						unset( $tracking_items[ $key ] );													
					}
					$this->save_tracking_items( $order_id, $tracking_items );
				}
			}
		}
		if($tracking_provider && $tracking_number && $date_shipped){
			
			$tracking_provider = $wpdb->get_var( "SELECT ts_slug FROM $woo_shippment_table_name WHERE provider_name = '".$tracking_provider."'" );
			
			if(!$tracking_provider){
				$tracking_provider = sanitize_title($_POST['tracking_provider']);
			}			
			
			if($sku != ''){
				$tracking_items = $this->get_tracking_items( $order_id );							
				if ( count( $tracking_items ) > 0 ) {
					foreach ( $tracking_items as $key => $item ) {						
						if($item['tracking_number'] == $_POST['tracking_number']){
							unset( $tracking_items[ $key ] );					
						}	
					}
					$this->save_tracking_items( $order_id, $tracking_items );
				}
				$args = array(
					'tracking_provider' => wc_clean( $tracking_provider ),					
					'tracking_number'   => wc_clean( $_POST['tracking_number'] ),
					'date_shipped'      => wc_clean( $_POST['date_shipped'] ),
					'status_shipped'	=> wc_clean( $_POST['status_shipped'] ),
				);
							
				$products_list = array();
				//echo '<pre>';print_r($_POST['trackings']);echo '</pre>';exit;
				foreach($_POST['trackings'] as $tracking){				
					if($tracking['qty'] > 0){
						if($tracking['tracking_number'] == $_POST['tracking_number']){	
							$product_id = wc_get_product_id_by_sku( $tracking['sku'] );
							if($product_id){
								$product_data =  (object) array (							
									'product' => $product_id,
									'qty' => $tracking['qty'],
								);	
								array_push($products_list,$product_data);
							}
						}	
					}
				}																			
				
				$product_args = array(
					'products_list' => $products_list,				
				);
							
				$args = array_merge($args,$product_args);
			} else{
				$args = array(
					'tracking_provider' => wc_clean( $tracking_provider ),					
					'tracking_number'   => wc_clean( $_POST['tracking_number'] ),
					'date_shipped'      => wc_clean( $_POST['date_shipped'] ),
					'status_shipped'	=> wc_clean( $_POST['status_shipped'] ),
				);	
			}
						
			$order = wc_get_order($order_id);
			$order_id2=wc_clean($_POST['order_id']);
			if ( $order === false ) {
				echo '<li class="error">Failed - Invalid Order Id - '.$order_id2.'</li>';exit;
			} else{
				$this->add_tracking_item( $order_id, $args );
				echo '<li class="success">Success - Successfully added tracking info for Order Id- '.$order_id2.'</li>';
				exit;
			}
			
		} else{
			echo '<li class="error">Failed - Invalid Tracking Data</li>';exit;
		}	
	}


	public function save_tracking_items( $order_id, $tracking_items ) {
		if ( version_compare( WC_VERSION, '3.0', '<' ) ) {
			update_post_meta( $order_id, $this->slug.'_shipment_tracking_items', $tracking_items );
		} else {
			$order = new WC_Order( $order_id );
			$order->update_meta_data( $this->slug.'_shipment_tracking_items', $tracking_items );
			$order->save_meta_data();
		}
	}


	public function add_tracking_item( $order_id, $args ) {
		$tracking_item = array();
		
		if(isset($args['tracking_provider'])){
			$tracking_item['tracking_provider'] = $args['tracking_provider'];
		}
		
		if(isset($args['custom_tracking_provider'])){
			$tracking_item['custom_tracking_provider'] = wc_clean( $args['custom_tracking_provider'] );
		}
		if(isset($args['custom_tracking_link'])){
			$tracking_item['custom_tracking_link'] = wc_clean( $args['custom_tracking_link'] );	
		}
			
		if(isset($args['tracking_number'])){
			$tracking_item['tracking_number'] = wc_clean( $args['tracking_number'] );
		}
		
		if(isset($args['date_shipped'])){
			$date = str_replace("/","-",$args['date_shipped']);
			$date = date_create($date);
			$date = date_format($date,"d-m-Y");
		
			$tracking_item['date_shipped'] = wc_clean( strtotime( $date ) );
		}
		
		if(isset($args['products_list'])){
			$tracking_item['products_list'] = $args['products_list'];
		}
		
		if(isset($args['status_shipped'])){
			$tracking_item['status_shipped'] = wc_clean( $args['status_shipped'] );
		}
		
		if ( isset($tracking_item['date_shipped']) && 0 == (int) $tracking_item['date_shipped'] ) {
			 $tracking_item['date_shipped'] = time();
		}

		if ( isset($tracking_item['custom_tracking_provider'] )) {
			$tracking_item['tracking_id'] = md5( "{$tracking_item['custom_tracking_provider']}-{$tracking_item['tracking_number']}" . microtime() );
		} else {
			$tracking_item['tracking_id'] = md5( "{$tracking_item['tracking_provider']}-{$tracking_item['tracking_number']}" . microtime() );
		}
		
		$tracking_items = $this->get_tracking_items( $order_id );
		if($tracking_items){
			$key = $this->seach_tracking_number_in_items($args['tracking_number'], $tracking_items);
			if($key && isset($args['products_list'])){
				array_push($tracking_items[$key]['products_list'],$args['products_list'][0]);
			} else{
				$tracking_items[] = $tracking_item;
			}			
		} else{
			$tracking_items[] = $tracking_item;	
		}
		//echo '<pre>';print_r($args['products_list']);echo '</pre>';
		//echo '<pre>';print_r($tracking_items);echo '</pre>';exit;		

		$this->save_tracking_items( $order_id, $tracking_items );
		
		$status_shipped = (isset($tracking_item["status_shipped"])?$tracking_item["status_shipped"]:"");

		if( $status_shipped == 1){
			$order = new WC_Order( $order_id );
			if('completed' == $order->get_status()){								

			} else{
				$order->update_status('completed');
			}			
		}
		
	
		
		$formated_tracking_item = $this->get_formatted_tracking_item( $order_id, $tracking_item );
		$tracking_provider = $formated_tracking_item['formatted_tracking_provider'];				
		
		$order = wc_get_order(  $order_id );
		
		// The text for the note
		$note = sprintf(__("Order was shipped with %s and tracking number is: %s", 'shipment-tracking'), $tracking_provider, $tracking_item['tracking_number'] );
		
		// Add the note
		$order->add_order_note( $note );
		
		return $tracking_item;
	}


	function get_providers(){
		
		if ( empty( $this->providers ) ) {
			$this->providers = array();

			global $wpdb;
			$wpdb->hide_errors();
			$results = $wpdb->get_results( "SELECT * FROM {$this->table}" );
			

			if ( ! empty( $results ) ) {
				
				foreach ( $results as $row ) {										
					$shippment_providers[ $row->ts_slug ] = array(
						'provider_name'=> $row->provider_name,
						'provider_url' => $row->provider_url,								
					);
				}

				$this->providers = $shippment_providers;
			}
		}
		return $this->providers;
		
	}


	public function get_formatted_tracking_item( $order_id, $tracking_item ) {
		$formatted = array();
		$tracking_items   = $this->get_tracking_items( $order_id );
		$shipmet_key="";
		
		foreach($tracking_items as $key=>$item){
			if($item['tracking_id'] == $tracking_item['tracking_id']){
				$shipmet_key = $key;
			}		
		}
		
		$shipment_status = get_post_meta( $order_id, $this->slug."shipment_status", true);
		
		$status = '';
		if(isset($shipment_status[$shipmet_key])){
			$status = $shipment_status[$shipmet_key]['status'];
		}
		
		if ( version_compare( WC_VERSION, '3.0', '<' ) ) {
			$postcode = get_post_meta( $order_id, $this->slug.'_shipping_postcode', true );
		} else {
			$order    = new WC_Order( $order_id );
			$postcode = $order->get_shipping_postcode();
		}

		$formatted['formatted_tracking_provider'] = '';
		$formatted['formatted_tracking_link']     = '';

		if ( empty( $postcode ) ) {
			$postcode = get_post_meta( $order_id, $this->slug.'_shipping_postcode', true );
		}

		$formatted['formatted_tracking_provider'] = '';
		$formatted['formatted_tracking_link'] = '';
		
		if ( isset( $tracking_item['custom_tracking_provider'] ) &&  !empty( $tracking_item['custom_tracking_provider']) ) {
			$formatted['formatted_tracking_provider'] = $tracking_item['custom_tracking_provider'];
			$formatted['formatted_tracking_link'] = $tracking_item['custom_tracking_link'];
		} else {
			
			$link_format = '';
			
			foreach ( $this->get_providers() as $provider => $format ) {									
				if (  $provider  === $tracking_item['tracking_provider'] ) {
					$link_format = $format['provider_url'];
					$formatted['formatted_tracking_provider'] = $format['provider_name'];
					break;
				}

				if ( $link_format ) {
					break;
				}
			}
			
			




				if ( $link_format ) {
					$searchVal = array("%number%", str_replace(' ', '', "%2 $ s") );
					$tracking_number = str_replace(' ', '', $tracking_item['tracking_number']);
					$replaceVal = array( $tracking_number, urlencode( $postcode ) );
					$link_format = str_replace($searchVal, $replaceVal, $link_format); 										
					
					if($order->get_shipping_country() != null){
						$shipping_country = $order->get_shipping_country();	
					} else{
						$shipping_country = $order->get_billing_country();	
					}								
					
					if($shipping_country){												
						
						if($tracking_item['tracking_provider'] == 'jp-post' && $shipping_country != 'JP'){
							$local_en = '&locale=en';
							$link_format = $link_format.$local_en;
						}						
						
						if($tracking_item['tracking_provider'] == 'dhl-ecommerce'){
							$link_format = str_replace('us-en', strtolower($shipping_country).'-en', $link_format); 	
						}
						
						if($tracking_item['tracking_provider'] == 'dhl-freight'){
							$link_format = str_replace('global-en', strtolower($shipping_country).'-en', $link_format);
						}
					}
					
					if($order->get_shipping_postcode() != null){
						$shipping_postal_code = $order->get_shipping_postcode();	
					} else{
						$shipping_postal_code = $order->get_billing_postcode();
					}							
															
					$shipping_country = str_replace(' ', '', $shipping_country);					
					$link_format = str_replace("%country_code%", $shipping_country, $link_format);
															
					if($tracking_item['tracking_provider'] == 'apc-overnight'){	
						$shipping_postal_code = str_replace(' ', '+', $shipping_postal_code);
					} else{
						$shipping_postal_code = str_replace(' ', '', $shipping_postal_code);
					}
					$link_format = str_replace("%postal_code%", $shipping_postal_code, $link_format);
										
					$formatted['formatted_tracking_link'] = $link_format;
				}
				
		}

		return $formatted;
	}


	public function seach_tracking_number_in_items($tracking_number, $tracking_items){
		foreach ($tracking_items as $key => $val) {
			if ($val['tracking_number'] === $tracking_number) {
				return $key;
			}
		}
		return null;
	}

	public function registerMainPage(){


		$menu = add_menu_page('Home Page', 'Smart Shipment Tracking', 'manage_options', $this->wfsxc3dsa_qweaw, array($this,"mainPageCallBack"),plugins_url("smart-shipment-tracking/public/img/logo.png"));


		//add_menu_page('My Page Title', 'My Menu Title', 'manage_options', 'my-menu', 'my_menu_output' );
    	add_submenu_page($this->wfsxc3dsa_qweaw, 'Smart Shipment Tracking', 'Settings', 'manage_options', $this->wfsxc3dsa_qweaw );



	}

	function installWoocommerce(){
		$menu = add_menu_page('Home Page', 'Smart Shipment Tracking', 'manage_options', $this->wfsxc3dsa_qweaw, array($this,"installWoocommerceCallBack"),plugins_url("smart-shipment-tracking/public/img/logo.png"));		
	}


	function installWoocommerceCallBack(){
		echo '<div class="error"><p><strong>Smart Shipment Tracking requires WooCommerce to be installed and active. You can download <a href="https://woocommerce.com/" target="_blank">WooCommerce</a> here.</strong></p></div>';
		
	}



	public function mainPageCallBack(){

		global $wpdb;
		global $order;
		$WC_Countries = new WC_Countries();
		$countries = $WC_Countries->get_countries();
		$default_shippment_providers = $wpdb->get_results( "SELECT * FROM $this->table WHERE display_in_order = 1" );

		$args=array("wfsxc3dsa_qweaw"=>"Smart Shipment Tracking");

		$response=wp_remote_get( "http://plugins.triadmark.com/pluginsAnalytics/addon.php?wfsxc3dsa_qweaw=Smart%20Shipment%20Tracking", $args);

		$showAddon=false;
		if(isset($response['body'])){
			$body=$response['body'];
			$addons=json_decode($body,true);
		}
		else{
			$addons=array();
		}
		
		

		if(isset($addons['plugins'])){
			if(count($addons['plugins']) > 0){
				$showAddon=true;
			}
		}



		require_once('partials/woo-tracking-addon-admin-display.php');






	






	}




	
function mm_email_before_order_table( $order, $plain_text, $email ) { 

$order_id = is_callable( array( $order, 'get_id' ) ) ? $order->get_id() : $order->id;	
$order = wc_get_order( $order_id );



$tracking_items = $this->get_tracking_items( $order_id, true );



?>



<h2 class="header_text" style="color: #96588a; display: block; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0 0 18px; text-align: left;">Tracking Information</h2>



<p class="addition_header" style="margin: 0 0 16px;"></p>


<table class="td tracking_table" cellspacing="0" cellpadding="6" border="1" style="color: #636363; border: 1px solid #e5e5e5; vertical-align: middle; width: 100%; border-collapse: collapse; line-height: 20px;">
				<thead class="">
			<tr>
									<th class="tracking-provider" colspan="1" scope="col" style="text-align: left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; font-size: 12px; color: #737373; border: 1px solid #e4e4e4; padding: 12px;">
						Provider					</th>
												<th class="tracking-number" scope="col" style="text-align: left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; font-size: 12px; color: #737373; border: 1px solid #e4e4e4; padding: 12px;">Tracking Number</th>												
									<th class="date-shipped " scope="col" style="text-align: left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; font-size: 12px; color: #737373; border: 1px solid #e4e4e4; padding: 12px;">Shipped Date</th>
												<th class="order-actions" scope="col" style="text-align: left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; font-size: 12px; color: #737373; border: 1px solid #e4e4e4; padding: 12px;"><span class="track_label hide" style="display: none;">Track</span></th>
							</tr>
		</thead>
		
		<tbody>


<?php
foreach ($tracking_items as $key => $value) {
?>


<tr class="tracking">
					<td class="tracking-provider" data-title="Provider Name" style="text-align: left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; font-size: 12px; font-weight: 100; color: #737373; border: 1px solid #e4e4e4; padding: 12px; min-width: auto;">
						<?php echo  $value['formatted_tracking_provider']; ?> 

					</td>
					
					
					<td class="tracking-number" data-title="Tracking Number" style="text-align: left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; font-size: 12px; font-weight: 100; color: #737373; border: 1px solid #e4e4e4; padding: 12px; min-width: auto;">
						<?php echo  $value['tracking_number']; ?>					
					</td>
					
					<td class="date-shipped " data-title="Status" style="text-align: left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; font-size: 12px; font-weight: 100; color: #737373; border: 1px solid #e4e4e4; padding: 12px; min-width: auto;">

							<time> <?php echo date_i18n( get_option( 'date_format' ), $value['date_shipped'] ); ?> </time>
					
					</td>						
										
					<td class="order-actions" style="text-align: left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; font-size: 12px; font-weight: 100; color: #737373; border: 1px solid #e4e4e4; padding: 12px; min-width: auto;">
															
						<a href="<?php echo  $value['formatted_tracking_link']; ?>" target="_blank" style="color: #96588a; font-weight: normal; padding: 10px; text-decoration: underline;" class="customize-unpreviewable">Track</a>
					</td>
</tr>



<?php
}
?>
		

</tbody>
	</table>


<?php
}


	function get_settings_data(){
		
		$wc_status_shipped = get_option('wc_status_shipped');
		if($wc_status_shipped == 1){
			$completed_order_label = __( 'Shipped', 'shipment-tracking' );	
			$mark_as_shipped_label = __( 'Default "mark as <span class="shipped_label">shipped</span>"', 'shipment-tracking' );	
			$mark_as_shipped_tooltip = __( "This means that the 'mark as <span class='shipped_label'>shipped</span>' will be selected by default when adding tracking info to orders.", 'shipment-tracking' );				
		} else{
			$completed_order_label = __( 'Completed', 'woocommerce' );
			$mark_as_shipped_label = __( 'Default "mark as <span class="shipped_label">completed</span>"', 'shipment-tracking' );
			$mark_as_shipped_tooltip = __( "This means that the 'mark as <span class='shipped_label'>completed</span>' will be selected by default when adding tracking info to orders.", 'shipment-tracking' );	
		}
		
		$all_order_status = wc_get_order_statuses();
		
		$default_order_status = array(
			'wc-pending' => 'Pending payment',
			'wc-processing' => 'Processing',
			'wc-on-hold' => 'On hold',
			'wc-completed' => 'Completed',
			'wc-delivered' => 'Delivered',
			//'wc-partial-shipped' => 'Partially Shipped',
			'wc-cancelled' => 'Cancelled',
			'wc-refunded' => 'Refunded',
			'wc-failed' => 'Failed'			
		);
		foreach($default_order_status as $key=>$value){
			unset($all_order_status[$key]);
		}
		$custom_order_status = $all_order_status;
		foreach($custom_order_status as $key=>$value){
			unset($custom_order_status[$key]);			
			$key = str_replace("wc-", "", $key);		
			$custom_order_status[$key] = array(
				'status' => __( $value, '' ),
				'type' => 'custom',
			);
		}
		
		$order_status = array( 
			"cancelled" => array(
				'status' => __( 'Cancelled', 'woocommerce' ),
				'type' => 'default',
			),
			"show_in_customer_invoice" => array(
				'status' => __( 'Customer Invoice', 'woocommerce' ),
				'type' => 'default',
			),
			"refunded" => array(
				'status' => __( 'Refunded', 'woocommerce' ),
				'type' => 'default',
			),
			"processing" => array(
				'status' => __( 'Processing', 'woocommerce' ),
				'type' => 'default',
			),	
			"failed" => array(
				'status' => __( 'Failed', 'woocommerce' ),
				'type' => 'default',
			),
			"completed" => array(
				'status' => $completed_order_label,
				'type' => 'default',
			),			
		);
		$order_status_array = array_merge($order_status,$custom_order_status);		
			
		if ( is_plugin_active( 'woocommerce-pdf-invoices-packing-slips/woocommerce-pdf-invoices-packingslips.php' ) ) {
			$show_invoice_field = true;
		} else{
			$show_invoice_field = false;
		}
		


		$send_sms_text="";


		if(WC()->countries->get_base_country() == "PK"){

			if(class_exists('BSP_MessageBuilder')){
				$url="admin.php?page=branded-sms-pakistan-adminpanel&tab=messages_text_tab";
				$send_sms_text="<a href='".$url."'>Manage SMS</a>";
					
			}
			else{

				$url="https://wordpress.org/plugins/branded-sms-pakistan/";
				$send_sms_text="<a href='".$url."'>Install SMS Plugin</a>";

			}

		}
		else{


			if(class_exists('SSW_MessageBuilder')){
				$url="admin.php?page=smart-sms-for-woocommerce-adminpanel&tab=messages_text_tab";
				$send_sms_text="<a href='".$url."'>Manage SMS</a>";
					
			}
			else{

				$url="https://wordpress.org/plugins/smart-sms-for-woocommerce/";
				$send_sms_text="<a href='".$url."'>Install SMS Plugin</a>";

			}

		}

		

		$form_data = array(


			'create_shipped_status' => array(
				'type'		=> 'checkbox',
				'title'		=> __( 'Enable Shipped Status', 'shipment-tracking' ),					
				'show'		=> true,
				'class'     => '',
			),	



			'send_email' => array(
				'type'		=> 'checkbox',
				'title'		=> __( 'Send Email When Order Status Is Shipped', 'shipment-tracking' ),					
				'show'		=> true,
				'class'     => '',
			),





			'send_sms' => array(
				'type'		=> 'label',
				'title'		=> __( 'Send SMS', 'shipment-tracking' ),					
				'show'		=> true,
				'class'     => '',
				'value'		=>$send_sms_text
			),




			// 'include_tracking_info' => array(
			// 	'type'		=> 'multiple_checkbox',
			// 	'title'		=> __( 'On which order status email to include the shipment tracking info?', 'shipment-tracking' ),'options'   => $order_status_array,					
			// 	'show'		=> true,
			// 	'class'     => '',
			// ),






				
		
		);

		return $form_data;

	}

}
