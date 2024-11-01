<style type="text/css">
	#ex1{
		max-width: 60%;
	}
	table.dataTable thead .sorting{
	background-image:url("<?php echo plugin_dir_url('smart-shipment-tracking') ?>smart-shipment-tracking/admin/img/sort_both.png")
	}
	table.dataTable thead .sorting_asc{
	background-image:url("<?php echo plugin_dir_url('smart-shipment-tracking') ?>smart-shipment-tracking/admin/img/sort_asc.png")
	}

	table.dataTable thead .sorting_asc_disabled{
		background-image:url("<?php echo plugin_dir_url('smart-shipment-tracking') ?>smart-shipment-tracking/admin/img/sort_asc_disabled.png")
		}

	table.dataTable thead .sorting_desc_disabled{
			background-image:url("<?php echo plugin_dir_url('smart-shipment-tracking') ?>smart-shipment-tracking/admin/img/sort_desc_disabled.png")
			}
</style>
<section id="content4" class="tab_section">

	<div class="d_table">

		<div class="tab_inner_container">	
		
			<section id="" class="tpage_section" style="display:block;">
			<form method="post" id="wc_sst_upload_csv_form" action="" enctype="multipart/form-data">	
			<h3 class="table-heading"><?php _e('Upload CSV', 'shipment-tracking'); ?></h3>	
			<table class="form-table upload_csv_table">
				<tbody>
					<tr valign="top" class="">
						<td scope="row" class="input_file_cl" colspan="2">
							<input type="file" name="trcking_csv_file" id="trcking_csv_file">
						</td>
					</tr> 
					<tr valign="top" class="">
						<th scope="row" class="th_80">
							<label for=""><?php _e('Replace tracking info if exists? (if not checked, the tracking info will be added)', 'shipment-tracking'); ?></label>													
						</th>
						<td scope="row" class="th_20">
							<input type="checkbox" id="replace_tracking_info" name="replace_tracking_info" class="" value="1"/>
						</td>
					</tr>
					<tr valign="top" class="">
						<th scope="row" colspan="2">
							<div class="submit">
								<button name="save" class="button-primary btn_ast2 btn_large" type="submit" value="Save"><?php _e('Upload', 'shipment-tracking'); ?></button>
								<div class="spinner" style="float:none"></div>
								<div class="success_msg" style="display:none;"><?php _e('Settings Saved.', 'shipment-tracking'); ?></div>
								<div class="error_msg" style="display:none;"></div>
								<?php wp_nonce_field( 'wc_sst_upload_csv_form', 'wc_sst_upload_csv_form' );?>
								<input type="hidden" name="action" value="wc_sst_upload_csv_form_update">
							</div>	
						</th>
					</tr>
					<tr class="bulk_upload_status_tr" style="display:none;">
						<td scope="row" colspan="2">
							<div id="p1" class="mdl-progress mdl-js-progress" style="display:none;"></div>
							<h3 class="progress_title" style="display:none;"><?php _e('Upload Progress - ', 'shipment-tracking'); ?>
								<span class="progress_number"></span>
							</h3>
							<ol class="csv_upload_status">								
							</ol>
						</td>
					</tr>	
				</tbody>				
			</table>
			</form>	






			</section>	
		
	</div>
		

		
<?php include 'woo-ship_admin_sidebar.php';?>	
	</div>
</section>