<section id="content2" class="tab_section">
	<div class="tab_inner_container">
		<form method="post" id="wc_sst_settings_form" action="" enctype="multipart/form-data">
			<?php #nonce?>
					
			<table class="form-table heading-table">
				<tbody>
					<tr valign="top">
						<td>
							<h3 style=""><?php _e( 'General Settings', 'shipment-tracking' ); ?></h3>
						</td>
					</tr>
				</tbody>
			</table>
			<?php require_once('admin_options_settings_part.php') ?>
			<table class="form-table">
				<tbody>
					<tr valign="top">						
						<td class="button-column">
							<div class="submit">								
								<button name="save" class="button-primary woocommerce-save-button btn_ast2 btn_large" type="submit" value="Save changes"><?php _e( 'Save Changes', 'shipment-tracking' ); ?></button>
								<div class="spinner"></div>								
								<?php wp_nonce_field( 'wc_sst_settings_form', 'wc_sst_settings_form' );?>
								<input type="hidden" name="action" value="wc_general_form_update">
							</div>	
						</td>
					</tr>
				</tbody>
			</table>
						

			
		</form>
	</div>	

	
	<?php include 'woo-ship_admin_sidebar.php';?>
</section>