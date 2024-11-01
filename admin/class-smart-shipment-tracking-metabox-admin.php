<?php

class wfsxc_Tracking_Addon_MetaBox {

	
	private $wfsxc3dsa_qweaw;

	
	private $version;

	
	public function __construct( $wfsxc3dsa_qweaw, $version ) {
		global $wpdb;
		$this->wfsxc3dsa_qweaw = $wfsxc3dsa_qweaw;
		$this->version = $version;
		$this->slug="wfsxc";
		$this->table=$wpdb->prefix."wfsxc_shippment_provider";

	}

	
	public function enqueue_styles() {



		wp_enqueue_style( $this->wfsxc3dsa_qweaw, plugin_dir_url( __FILE__ ) . 'css/plugin-name-public.css', array(), $this->version, 'all' );

	}


	public function enqueue_scripts() {

		wp_enqueue_script( $this->wfsxc3dsa_qweaw, plugin_dir_url( __FILE__ ) . 'js/plugin-name-public.js', array( 'jquery' ), $this->version, false );

	}


	function register_meta_box(){

		add_meta_box( $this->slug.'-shipment-tracking', __( 'Smart Shipment Tracking', $this->slug.'-shipment-tracking' ), array( $this, 'meta_box_loader' ), 'shop_order', 'side', 'high' );

	}

	
	function meta_box_loader(){
		$this->meta_box();
	}

	public function meta_box_delete_tracking() {

		$order_id    = wc_clean( $_POST['order_id'] );
		$tracking_id = wc_clean( $_POST['tracking_id'] );
		$tracking_items = $this->get_tracking_items( $order_id, true );
		
		foreach($tracking_items as $tracking_item){
			if($tracking_item['tracking_id'] == $_POST['tracking_id']){
				$formated_tracking_item = $this->get_formatted_tracking_item( $order_id, $tracking_item );
				
				$tracking_number = $tracking_item['tracking_number'];
				$tracking_provider = $formated_tracking_item['formatted_tracking_provider'];
				$order = wc_get_order(  $order_id );
				// The text for the note
				$note = sprintf(__("Tracking info was deleted for tracking provider %s with tracking number %s", 'shipment-tracking'), $tracking_provider, $tracking_number );
				
				// Add the note
				$order->add_order_note( $note );
			}
		}
		$this->delete_tracking_item( $order_id, $tracking_id );				
	}


	public function delete_tracking_item( $order_id, $tracking_id ) {
		$tracking_items = $this->get_tracking_items( $order_id );

		$is_deleted = false;

		if ( count( $tracking_items ) > 0 ) {
			foreach ( $tracking_items as $key => $item ) {
				if ( $item['tracking_id'] == $tracking_id ) {
					unset( $tracking_items[ $key ] );
					$is_deleted = true;
					$this->func_fix_shipment_tracking_for_deleted_tracking($order_id, $key, $item);
					break;
				}
			}
			$this->save_tracking_items( $order_id, $tracking_items );
		}

		return $is_deleted;
	}


	public function func_fix_shipment_tracking_for_deleted_tracking( $order_id, $key, $item ){
		$shipment_status = get_post_meta( $order_id, "shipment_status", true);
		if( isset( $shipment_status[$key] ) ){
			unset($shipment_status[$key]);
			update_post_meta( $order_id, "shipment_status", $shipment_status);
		}
	}


	public function meta_box() {
		global $post;
		global $wpdb;
		
		$WC_Countries = new WC_Countries();
		$countries = $WC_Countries->get_countries();
		
		$woo_shippment_table_name = $this->table;

		
		$tracking_items = $this->get_tracking_items( $post->ID );
	
		$shippment_countries = $wpdb->get_results( "SELECT shipping_country FROM $woo_shippment_table_name WHERE display_in_order = 1 GROUP BY shipping_country" );
		
		$shippment_providers = $wpdb->get_results( "SELECT * FROM $woo_shippment_table_name" );
		
		$default_provider = get_option("wfsxc_default_provider" );
	

		$smart_shipment_default_mark = 	get_option("smart_shipment_default_mark" );
		$smart_shipment_status_partial_shipped = get_option('smart_shipment_status_partial_shipped');
		$value = "rtest";
		$cbvalue = '';
		if($smart_shipment_default_mark == 1){
			
				$cbvalue = 1;	
						
		}		

		$wc_status_shipped = get_option('wc_status_shipped');
		if($wc_status_shipped == 1){
			$change_order_status_label = __( 'Mark as shipped', 'shipment-tracking' );
			$shipped_label = 'Shipped';
		} else{
			$change_order_status_label = __( 'Mark as shipped?', 'shipment-tracking' );
			$shipped_label = 'Shipped';
		}
		
		
						
		 echo '<style>

		 		#change_to_shipped{
		 			    margin-left: 12px;
		 		}
		 	   </style>

		 <div id="tracking-itemss">';



		
		if ( count( $tracking_items ) > 0 ) {
			foreach ( $tracking_items as $tracking_item ) {				
				$this->display_html_tracking_item_for_meta_box( $post->ID, $tracking_item );
			}
		}
		echo '</div>';
		
		echo '<button class="button show-tracking-form" type="button">' . __( 'Add Tracking Info', 'shipment-tracking' ) . '</button>';
		
		echo '<div id="shipment-tracking-form" style="display:none">';
		
		echo '<p class="form-field tracking_provider_field"><label for="tracking_provider">' . __( 'Provider:', 'shipment-tracking' ) . '</label><br/>

		<select id="tracking_pro" name="tracking_provider" class="chosen_select" style="width:100%;">';	

			echo '<option value="">'.__( 'Select Provider', 'shipment-tracking' ).'</option>';
		foreach($shippment_countries as $s_c){
			if($s_c->shipping_country != 'Global'){
				$country_name = esc_attr( $WC_Countries->countries[$s_c->shipping_country] );
			} else{
				$country_name = 'Global';
			}
			echo '<optgroup label="' . $country_name . '">';
				$country = $s_c->shipping_country;				
				$shippment_providers_by_country = $wpdb->get_results( "SELECT * FROM $woo_shippment_table_name WHERE shipping_country = '$country' AND display_in_order = 1" );
				foreach ( $shippment_providers_by_country as $providers ) {
					//echo '<pre>';print_r($providers);echo '</pre>';
					$selected = ( $default_provider == esc_attr( $providers->ts_slug )  ) ? 'selected' : '';
					echo '<option value="' . esc_attr( $providers->ts_slug ) . '" '.$selected. '>' . esc_html( $providers->provider_name ) . '</option>';
				}
			echo '</optgroup>';	
		}

		echo '</select> ';
		
		woocommerce_wp_hidden_input( array(
			'id'    => 'wc_shipment_tracking_get_nonce',
			'value' => wp_create_nonce( 'get-tracking-item' ),
		) );

		woocommerce_wp_hidden_input( array(
			'id'    => 'wc_shipment_tracking_delete_nonce',
			'value' => wp_create_nonce( 'delete-tracking-item' ),
		) );

		woocommerce_wp_hidden_input( array(
			'id'    => 'wc_shipment_tracking_create_nonce',
			'value' => wp_create_nonce( 'create-tracking-item' ),
		) );		

		woocommerce_wp_text_input( array(
			'id'          => 'tracking_num',
			'label'       => __( 'Tracking Number:', 'shipment-tracking' ),
			'placeholder' => '',
			'description' => '',
			'value'       => '',
		) );


		woocommerce_wp_text_input( array(
			'id'          => 'date_shipped',
			'label'       => __( 'Date Shipped:', 'shipment-tracking' ),
			'placeholder' => date_i18n( 'd-m-Y',time() ),
			'description' => '',
			'class'       => 'date-picker-field',
			'value'       => date_i18n( __(get_option('date_format'), 'shipment-tracking' ), current_time( 'timestamp' ) ),
		) );	



		woocommerce_wp_textarea_input( array(
			'id'          => 'tracking_note',
			'label'       => __( 'Note:', 'shipment-tracking' ),
			'placeholder' =>__( 'Notes Related To Tracking', 'shipment-tracking' ),
			'description' => '',

		) );


		

		woocommerce_wp_checkbox( array(
			'id'          => 'change_to_shipped',
			'label'       => __( $change_order_status_label, 'shipment-tracking' ),		
			'description' => '',
			'cbvalue'       => '',
		) );
	
		echo '<button class="button button-primary btn_green button-save-form-tracking">' . __( 'Save Tracking', 'shipment-tracking' ) . '</button>';

		
		echo '</div>';
		$provider_array = array();

		foreach ( $shippment_providers as $provider ) {
			$provider_array[ sanitize_title( $provider->provider_name ) ] = urlencode( $provider->provider_url );
		}
		
		$js = "

			jQuery( 'p.custom_tracking_link_field, p.custom_tracking_provider_field ').hide();

			jQuery( 'input#tracking_number, #tracking_provider' ).change( function() {

				var tracking  = jQuery( 'input#tracking_number' ).val();
				var provider  = jQuery( '#tracking_provider' ).val();
				var providers = jQuery.parseJSON( '" . json_encode( $provider_array ) . "' );

				var postcode = jQuery( '#_shipping_postcode' ).val();

				if ( ! postcode.length ) {
					postcode = jQuery( '#_billing_postcode' ).val();
				}

				postcode = encodeURIComponent( postcode );

				var link = '';

				if ( providers[ provider ] ) {
					link = providers[provider];
					link = link.replace( '%25number%25', tracking );
					link = link.replace( '%252%24s', postcode );
					link = decodeURIComponent( link );

					jQuery( 'p.custom_tracking_link_field, p.custom_tracking_provider_field' ).hide();
				} else {
					jQuery( 'p.custom_tracking_link_field, p.custom_tracking_provider_field' ).show();

					link = jQuery( 'input#custom_tracking_link' ).val();
				}

				if ( link ) {
					jQuery( 'p.preview_tracking_link a' ).attr( 'href', link );
					jQuery( 'p.preview_tracking_link' ).show();
				} else {
					jQuery( 'p.preview_tracking_link' ).hide();
				}

			} ).change();";

		if ( function_exists( 'wc_enqueue_js' ) ) {
			wc_enqueue_js( $js );
		} else {
			WC()->add_inline_js( $js );
		}

		

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


	function add_order_shipment_status_column_header( $columns ) {
	


		$columns['tracking-status'] = 'Tracking Status';
    return $columns;
	}









	function add_order_shipment_status_column_content( $column ) {
   
	    global $post;



	    if ( 'tracking-status' === $column ) {

	    }
	 
	    if ( 'tracking-status' === $column ) {
			

			$tracking_items = $this->get_tracking_items( $post->ID );

//print_r($tracking_items);
			$shipment_status = get_post_meta( $post->ID, "shipment_status", true);			
			$wp_date_format = get_option( 'date_format' );


			if($wp_date_format == 'd/m/Y'){
				$date_format = 'd/m'; 
			} else{
				$date_format = 'm/d';
			}
			if ( count( $tracking_items ) > 0 ) {

				?>



				<ul class="wcsst-tracking-number-list">
					

					<?php foreach ( $tracking_items as $key => $tracking_item ) {
						$single_details = $this->get_single_provider_by_slug($tracking_item['tracking_provider']);
					?> 

					
						<li id="tracking-item-<?php echo $tracking_item['tracking_id'];?>" class="tracking-item-<?php echo $tracking_item['tracking_id'];?>">
							<div>
								<b><?php echo $single_details['provider_name']; ?></b>
							</div>
							<?php if($single_details['provider_url']!="#") { ?>
								<a href="<?php echo str_replace("%number%", $tracking_item['tracking_number'], $single_details['provider_url']); ?>" target="_blank"><?php echo $tracking_item['tracking_number'] ?></a>
							<?php } else {
								echo $tracking_item['tracking_number'];
							} ?>
							
						</li>

					<?php  } ?>
				</ul>

				<?php
			} else {
				echo '–';
			}
		}
	}

	public function get_formatted_tracking_item( $order_id, $tracking_item ) {
		$formatted = array();
		$tracking_items   = $this->get_tracking_items( $order_id );
		$shipmet_key="";
		//sprint_r($tracking_items);
		foreach($tracking_items as $key=>$item){
			if($item['tracking_id'] == $tracking_item['tracking_id']){
				$shipmet_key = $key;
			}		
		}
		
		$shipment_status = get_post_meta( $order_id, "shipment_status", true);
		
		$status = '';
		if(isset($shipment_status[$shipmet_key])){
			$status = $shipment_status[$shipmet_key]['status'];
		}
		
		if ( version_compare( WC_VERSION, '3.0', '<' ) ) {
			$postcode = get_post_meta( $order_id, '_shipping_postcode', true );
		} else {
			$order    = new WC_Order( $order_id );
			$postcode = $order->get_shipping_postcode();
		}

		$formatted['formatted_tracking_provider'] = '';
		$formatted['formatted_tracking_link']     = '';

		if ( empty( $postcode ) ) {
			$postcode = get_post_meta( $order_id, '_shipping_postcode', true );
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


	public function display_shipment_tracking_info( $order_id, $item ){
		$shipment_status = get_post_meta( $order_id, "shipment_status", true);
		$tracking_id = $item['tracking_id'];
		$tracking_items = $this->get_tracking_items( $order_id );
		$wp_date_format = get_option( 'date_format' );
		if($wp_date_format == 'd/m/Y'){
			$date_format = 'd/m'; 
		} else{
			$date_format = 'm/d';
		}
		if ( count( $tracking_items ) > 0 ) {
			foreach ( $tracking_items as $key => $tracking_item ) {
				if( $tracking_id == $tracking_item['tracking_id'] ){
					if( isset( $shipment_status[$key] )){
						$has_est_delivery = false;
						$data = $shipment_status[$key];
						$status = $data["status"];
						$status_date = $data['status_date'];
						if(!empty($data["est_delivery_date"])){
							$est_delivery_date = $data["est_delivery_date"];
						}
						if( $status != 'delivered' && $status != 'return_to_sender' && !empty($est_delivery_date) ){
							$has_est_delivery = true;
						}
						?>	
						<div class="sst-shipment-status-div">	
                        <span class="sst-shipment-status shipment-<?php echo sanitize_title($status)?>"><?php echo apply_filters( "trackship_status_icon_filter", "", $status )?> <strong><?php echo apply_filters("trackship_status_filter",$status)?></strong></span>
						<span class="">on <?php echo date( $date_format, strtotime($status_date))?></span>
                        <br>
                        <?php if( $has_est_delivery ){?>
                            <span class="wcsst-shipment-est-delivery ft11">Est. Delivery(<?php echo date( $date_format, strtotime($est_delivery_date))?>)</span>
                        <?php } ?>
						</div>	
                        <?php
					}
				}
			}
		}
	}


	public function save_meta_box_ajax() {
		check_ajax_referer( 'create-tracking-item', 'security', true );

		$tracking_number = sanitize_text_field($_POST['tracking_number']);


		if ( isset( $_POST['tracking_number'] ) &&  $_POST['tracking_provider'] != '' && isset( $_POST['tracking_provider'] ) && strlen( $_POST['tracking_number'] ) > 0 ) {
	
			$order_id = wc_clean( $_POST['order_id'] );
			$order = new WC_Order($order_id);
			$args = array(
				'tracking_provider'        => wc_clean($_POST['tracking_provider']),
				'tracking_number'          => wc_clean( $_POST['tracking_number'] ),
				'date_shipped'             => wc_clean( $_POST['date_shipped'] ),
				'note'             => wc_clean( $_POST['note'] ),
			);



			$tracking_item = $this->add_tracking_item( $order_id, $args );
			
			if($_POST['change_order_to_shipped'] == 'change_order_to_shipped'){     
							
				if('shipped' == $order->get_status()){
	
				} else{

					$order->update_status('wc-order-shipped');
					$mailer = WC()->mailer();
					$mails = $mailer->get_emails();

					


					if ( ! empty( $mails ) ) {
					    foreach ( $mails as $mail ) {
					        if ( $mail->id == 'wc_shipped_order' ) {
					           $mail->trigger( $order->id );
					        }
					     }
					}
			
				}																
			}			
			
			$this->display_html_tracking_item_for_meta_box( $order_id, $tracking_item );
		}

		wp_die();
	}

	public function get_single_provider_by_slug ( $ts_slug ) {
		$this->providers = "";

		if ( empty( $this->providers ) ) {
			$this->providers = array();

			global $wpdb;
			$wpdb->hide_errors();
			$results = $wpdb->get_results( "SELECT provider_name, provider_url FROM {$this->table} WHERE ts_slug='$ts_slug'" );
				
			if ( ! empty( $results ) ) {
				$this->providers = "";	
					$provider = array(
						'provider_name'=> $results[0]->provider_name,
						'provider_url' => $results[0]->provider_url,
					);
				$this->providers = $provider;
			}
		}
		return $this->providers;
			
	}


	
	public function display_html_tracking_item_for_meta_box( $order_id, $item ) {

			$formatted = $this->get_formatted_tracking_item( $order_id, $item );
//print_r($formatted);
			?>
			<div class="tracking-item" style="background: #efefef;padding:7px;margin-bottom:5px" id="tracking-item-<?php echo esc_attr( $item['tracking_id'] ); ?>">
				<div class="tracking-content">
					<div class="tracking-content-div">
						<strong style="text-transform: capitalize"><?php echo esc_html( $formatted['formatted_tracking_provider'] ); ?></strong>						
						<?php if ( strlen( $formatted['formatted_tracking_link'] ) > 0 ) { ?>
							- <?php 
							$url = str_replace('%number%',$item['tracking_number'],$formatted['formatted_tracking_link']);
							echo sprintf( '<a href="%s" target="_blank" title="' . esc_attr( __( 'Track Shipment', 'shipment-tracking' ) ) . '">' . __( $item['tracking_number'] ) . '</a>', esc_url( $url ) ); ?>
						<?php } else{ ?>
							<span> - <?php echo $item['tracking_number']; ?></span>
						<?php } ?>
					</div>					
											
					<?php                     
					$this->display_shipment_tracking_info( $order_id, $item );?>
				</div>

				<p><?php echo isset($item['note']) ? $item['note'] : ""  ?> </p>
				<p class="meta">
					<?php /* translators: 1: shipping date */ ?>
					<?php echo esc_html( sprintf( __( 'Added on %s', 'shipment-tracking' ), date_i18n( get_option( 'date_format' ), $item['date_shipped'] ) ) ); ?>
					<a href="#" class="delete-shipment-tracking" rel="<?php echo esc_attr( $item['tracking_id'] ); ?>"><?php _e( 'Delete', 'woocommerce' ); ?></a>                    
				</p>
			</div>
			<?php
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

	public function get_tracking_items( $order_id, $formatted = false ) {
		
		global $wpdb;
		$order = wc_get_order( $order_id );		
		if($order){	
			if ( version_compare( WC_VERSION, '3.0', '<' ) ) {			
				$tracking_items = get_post_meta( $order_id,  $this->slug.'_shipment_tracking_items', true );
			} else {						
				$order          = new WC_Order( $order_id );		
				$tracking_items = $order->get_meta(  $this->slug.'_shipment_tracking_items', true );			
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


	public function save_meta_box( $post_id, $post ) {
		
		if ( isset( $_POST['tracking_number'] ) &&  $_POST['tracking_provider'] != '' && strlen( $_POST['tracking_number'] ) > 0 ) {
			$tracking_number = sanitize_text_field($_POST['tracking_number']);

			$args = array(
				'tracking_provider'        => wc_clean( $_POST['tracking_provider'] ),
				'tracking_number'          => wc_clean( $_POST['tracking_number'] ),
				'date_shipped'             => wc_clean( $_POST['date_shipped'] ),				
			);
			if($_POST['change_order_to_shipped'] == 'yes'){
				$_POST['order_status'] = 'wc-completed';								
			}
			$this->add_tracking_item( $post_id, $args );
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
		if(isset($args['note'])){
			$tracking_item['note'] = wc_clean( $args['note'] );
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





		$this->save_tracking_items( $order_id, $tracking_items );
		
		$status_shipped = (isset($tracking_item["status_shipped"])?$tracking_item["status_shipped"]:"");
		
		if( $status_shipped == 1){
			$order = new WC_Order( $order_id );
			if('shipped' == $order->get_status()){								
	
			} else{
				$order->update_status('wc-order-shipped');
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


	public function seach_tracking_number_in_items($tracking_number, $tracking_items){
		foreach ($tracking_items as $key => $val) {
			if ($val['tracking_number'] === $tracking_number) {
				return $key;
			}
		}
		return null;
	}




// Register new status
	function register_shipped_status() {

		

				register_post_status( 'wc-order-shipped', array(
		        'label'                     => _x( 'Shipped', 'Order status', 'woocommerce' ),
		        'public'                    => true,
		        'exclude_from_search'       => false,
		        'show_in_admin_all_list'    => true,
		        'show_in_admin_status_list' => true,
		        'label_count'               => _n_noop( 'Shipped <span class="count">(%s)</span>', 'Shipped<span class="count">(%s)</span>', 'woocommerce' )
		    ) );

			
			

	}


	// Add to list of WC Order statuses
	function add_awaiting_shippped_to_order_statuses( $order_statuses ) {
	
	    $new_order_statuses = array();

	    // add new order status before processing
	    foreach ($order_statuses as $key => $status) {
	        $new_order_statuses[$key] = $status;
	        if ('wc-processing' === $key) {
	            $new_order_statuses['wc-order-shipped'] = __('Shipped', 'woocommerce' );
	        }
	    }

	    return $new_order_statuses;


	}

	function custom_dropdown_bulk_actions_shop_order( $actions ) {
	    $actions['mark_wc-shipped'] = __( 'Mark Shipped but not paid', 'woocommerce' );
	    return $actions;
	}





}
