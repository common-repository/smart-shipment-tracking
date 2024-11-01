<?php
class wfsxc3dsa_Tracking_Addon {

	
	protected $loader;

	
	protected $wfsxc3dsa_qweaw;

	
	protected $version;

	
	public function __construct() {
		if ( defined( 'wfsxc3dsa_pluginversion' ) ) {
			$this->version = wfsxc3dsa_pluginversion;
		} else {
			$this->version = '1.0.0';
		}


		if ( defined( 'wfsxc3dsa_qweaw' ) ) {
			$this->wfsxc3dsa_qweaw = wfsxc3dsa_qweaw;
		} else {
			$this->wfsxc3dsa_qweaw = 'smart-shipment-tracking';
		}


		

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	
	private function load_dependencies() {

		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-smart-shipment-tracking-loader.php';

		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-smart-shipment-tracking-i18n.php';

		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-smart-shipment-tracking-admin.php';

	


		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-smart-shipment-tracking.php';


		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-smart-shipment-tracking-metabox-admin.php';


		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-email-admin.php';




		$this->loader = new wfsxc_Tracking_Addon_Loader();

	}

	
	private function set_locale() {

		$plugin_i18n = new wfsxc_Tracking_Addon_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	
	private function define_admin_hooks() {

		$plugin_admin = new wfsxc_Smart_Tracking_Addon_Admin( $this->get_wfsxc3dsa_qweaw(), $this->get_version() );
		$plugin_admin_meta_box = new wfsxc_Tracking_Addon_MetaBox( $this->get_wfsxc3dsa_qweaw(), $this->get_version() );
		$plugin_email_admin = new wfsxc_Tracking_Email_Admin( $this->get_wfsxc3dsa_qweaw(), $this->get_version() );

		
		




		if(in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )){

			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

			$this->loader->add_action('admin_menu', $plugin_admin,"registerMainPage");

			


			$this->loader->add_action( 'add_meta_boxes',  $plugin_admin_meta_box , 'register_meta_box'  );

			$this->loader->add_action( 'woocommerce_process_shop_order_meta',  $plugin_admin_meta_box , 'save_meta_box',0,2  );
			$this->loader->add_action( 'wp_ajax_wfsxc_shipment_tracking_form',  $plugin_admin_meta_box , 'save_meta_box_ajax',0,2  );
			add_action( 'wp_ajax_wfsxc_shipment_tracking_delete_item', array( $plugin_admin_meta_box , 'meta_box_delete_tracking' ) );
			
			//add column after tracking
			add_filter( 'manage_edit-shop_order_columns', array( $plugin_admin_meta_box, 'add_order_shipment_status_column_header'), 20 );
			//shipment status content in order page
			add_action( 'manage_shop_order_posts_custom_column', array( $plugin_admin_meta_box, 'add_order_shipment_status_column_content') );



			if(get_option('create_shipped_status')){

				add_action( 'init', array( $plugin_admin_meta_box, 'register_shipped_status') );
				add_filter( 'wc_order_statuses', array( $plugin_admin_meta_box, 'add_awaiting_shippped_to_order_statuses') );

			}


			


			add_filter( 'bulk_actions-edit-shop_order', array( $plugin_admin_meta_box, 'custom_dropdown_bulk_actions_shop_order'), 20, 1 );



			if(get_option('send_email')){

				add_filter( 'woocommerce_email_classes', array( $plugin_email_admin, 'add_shipped_order_woocommerce_email') );

			}




		}

		else{
			$this->loader->add_action('admin_menu', $plugin_admin,"installWoocommerce");
		}

		
		

	}




	
	private function define_public_hooks() {


	}

	
	public function run() {
		$this->loader->run();
	}

	

	
	public function get_wfsxc3dsa_qweaw() {
		return $this->wfsxc3dsa_qweaw;
	}

	
	public function get_loader() {
		return $this->loader;
	}

	
	public function get_version() {
		return $this->version;
	}

}
