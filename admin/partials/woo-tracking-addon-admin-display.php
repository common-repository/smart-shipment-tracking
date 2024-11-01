<h1>Smart Shipment Tracking</h1>



            <div class="woocommerce woo-ship_admin_layout">
                <div class="sst_admin_content" >					
					<input id="tab2" type="radio" name="tabs" class="tab_input" data-tab="settings" checked>
					<label for="tab2" class="tab_label first_label">
						<span class="material-icons icon-setting">
settings
</span>
<br>
<span><?php _e('SETTINGS', 'woocommerce'); ?></span>




					</label>
					


					<input id="tab1" type="radio" name="tabs" class="tab_input" data-tab="shipping-providers" <?php if(isset($_GET['tab']) && $_GET['tab'] == 'shipping-providers'){ echo 'checked'; } ?>>
					<label for="tab1" class="tab_label">
						<span class="material-icons icon-setting">
local_shipping
</span>
<br>
<?php _e('SHIPPING PROVIDERS', 'shipment-tracking'); ?></label>                                        
					<input id="tab4" type="radio" name="tabs" class="tab_input" data-tab="bulk-upload" <?php if(isset($_GET['tab']) && $_GET['tab'] == 'bulk-upload'){ echo 'checked'; } ?>>					
					<label for="tab4" class="tab_label">
						<span class="material-icons icon-setting">
cloud_upload
</span>
<br>

<?php _e('BULK UPLOAD', 'shipment-tracking'); ?></label>



					<input id="tab5" type="radio" name="tabs" class="tab_input" data-tab="download-sample-sheet" <?php if(isset($_GET['tab']) && $_GET['tab'] == 'download-sample-sheet'){ echo 'checked'; } ?>>					
					<label for="tab5" class="tab_label">

						<span class="material-icons icon-setting">
cloud_download
</span>
<br>

<?php _e('SAMPLE SHEET', 'shipment-tracking'); ?></label>

					
					<?php
					if($showAddon){
					?>
						<input id="tab6" type="radio" name="tabs" class="tab_input" data-tab="addons" <?php if(isset($_GET['tab']) && ($_GET['tab'] == 'addons')){ echo 'checked'; } ?>>
						<label for="tab6" class="tab_label"><?php _e('Add-ons', 'shipment-tracking'); ?></label>
					<?php
					}
					?>
					
				
                    
					

                    

                    <?php  require_once( 'admin_options_shipping_provider.php' );?>

					<?php require_once( 'admin_options_settings.php' );?>
				
		
					<?php require_once( 'admin_options_bulk_upload.php' );?>

					<?php require_once( 'admin_download_sample_sheet.php' );?> 

					<?php require_once( 'admin_options_addons.php' );?> 


					
                </div>				
            </div>            


