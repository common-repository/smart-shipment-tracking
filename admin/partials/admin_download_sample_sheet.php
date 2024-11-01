
					<section id="content5" class="tab_section">

						<div class="d_table">

							<div class="tab_inner_container">
							


								
		

			
			<form method="post" id="export_csv_form_" action="" enctype="multipart/form-data">
			<h3 class="table-heading"><?php _e('Download Sample Sheet', 'shipment-tracking'); ?></h3>
			<table class="form-table">
				<tbody>
					<tr valign="top" style="border-bottom: 0;">
						<td>
							<span style="margin-right: 23px"><?php _e('Please Select Date Range: ', 'shipment-tracking'); ?></span>
							<input style="width: 165px" type="" name="dates" id="dates">
						</td>

					</tr>



					<tr valign="top" style="border-bottom: 0;">
						<td>
							<span style="margin-right: 10px"><?php _e('Order Staus To Be Exported: ', 'shipment-tracking'); ?></span>



							
							  
					
							<select style="width: 1000px;height: 144px;" name="status[]" id="status" multiple="multiple">

								<?php

								foreach (wc_get_order_statuses() as $key => $value) {

									echo '<option value="'.$key.'" '.(($value=="Processing") ? 'selected' : '') .' >'.$value.'</option>';
		
								}
								?>
								
							</select>


							<p style="font-size: 12px;
        padding-left: 186px;    margin-top: 4px;"><b style="font-weight: bold;">Note: </b><b>Press CTRL to select multiple Order Status</b></p>

						</td>


					</tr>





					<tr valign="top" class="">
						<th scope="row" colspan="2">
							<div class="submit">
								<button name="save" class="button-primary btn_ast2 btn_large" type="submit" value="Save">Download Sample Sheet</button>
								<div class="spinner" style="float:none"></div>
								<div class="success_msg" style="display:none;">Settings Saved.</div>
								<div class="error_msg" style="display:none;"></div>
								<input type="hidden" id="export_csv_form2" name="export_csv_form2" value="75a7101c59"><input type="hidden" name="_wp_http_referer" value="/wordpress/wp-admin/admin.php?page=smart-shipment-tracking&amp;tab=bulk-upload"><input type="hidden" name="action" value="wfsxc_export_tracking_csv">
							</div>	
						</th>
					</tr>

					<tr valign="top">
						<td style="padding-top: 0;" colspan="2">
							<p></p>	
						</td>
					</tr>
				</tbody>
			</table>
			</form>


			<script type="text/javascript">
					jQuery('input[name="dates"]').daterangepicker({



						locale: {
					      format: 'DD/MM/YY'
					    }


					});

			</script>


			

<div id="ex1" class="modal">

	<div id="modalTable">


		<table id="example" class="display" style="width:100%">
	        <thead>
	            <tr>
	                <th>Order ID</th>
	                <th>Customer Name</th>
	                <th>City</th>
	                <th>Tracking Provider</th>
	                <th>Tracking Number</th>
	                <th>Date Shipped</th>
	                <th>Status Shipped</th>
	            </tr>
	        </thead>
	        <tbody id="csvBody">


	        </tbody>
	        <tfoot>
	            <tr>
	                <th>Order ID</th>
	                <th>Customer Name</th>
	                <th>City</th>
	                <th>Tracking Provider</th>
	                <th>Tracking Number</th>
	                <th>Date Shipped</th>
	                <th>Status Shipped</th>
	            </tr>
	        </tfoot>
	    </table>
        		
    </div>
    <small style="color: red;
    display: block;
    margin-bottom: 7px;
    font-size: 13px;">Please press proceed to add tracking information.</small>

    <div class="modal-footer">
          
          


          <a href="#close-modal" rel="modal:close" ><button name="proceed" onclick="sendAjax()" id="proceed" class="button-primary btn_ast2 btn_large" type="button" value="Proceed">Proceed</button></a>

          <a href="#close-modal" rel="modal:close" ><button name="cancel" id="cancel" class="button-primary btn_ast2 btn_large" type="button" value="Cancel">Cancel</button></a>
    </div>
  

</div>












							</div>
						</div>
					</section>