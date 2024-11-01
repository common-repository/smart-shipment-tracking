jQuery(document).on("click", ".shipping_provider_tab li", function(){
	jQuery(".shipping_provider_tab li").removeClass("current");
	jQuery(this).addClass("current");
		
	var target = jQuery(this).data("target");
	
	jQuery(".targets").hide();
	jQuery(".target-"+target).show();
});
jQuery(document).on("click", "#wc_sst_status_delivered", function(){
	if(jQuery(this).prop("checked") == true){
        jQuery(this).closest('tr').removeClass('disable_row');				
    } else{
		jQuery(this).closest('tr').addClass('disable_row');
	}	
});

jQuery(document).on("click", "#smart_shipment_status_partial_shipped", function(){
	if(jQuery(this).prop("checked") == true){
        jQuery(this).closest('tr').removeClass('disable_row');				
    } else{
		jQuery(this).closest('tr').addClass('disable_row');
	}	
});
jQuery(document).on("click", "#wc_sst_status_updated_tracking", function(){
	if(jQuery(this).prop("checked") == true){
        jQuery(this).closest('tr').removeClass('disable_row');				
    } else{
		jQuery(this).closest('tr').addClass('disable_row');
	}	
});

jQuery(document).on("change", "#wc_sst_select_email_type", function(){
	jQuery("#content2 ").block({
		message: null,
		overlayCSS: {
			background: "#fff",
			opacity: .6
		}	
    });	
	var email_type = jQuery(this).val();
	var ajax_data = {
		action: 'update_email_type',
		email_type: email_type,	
	};	
	jQuery.ajax({
		url: ajaxurl,
		data:ajax_data,
		type: 'POST',
		success: function(response) {
			if(email_type == 'wc_email'){		
				jQuery('label.tab_label[for="tab5"]').hide();
				jQuery('.manage_delivered_order_email_link').show();
			} else{	
				jQuery('label.tab_label[for="tab5"]').show();
				jQuery('.manage_delivered_order_email_link').hide();
			}	
			jQuery("#content2 ").unblock();			
			var snackbarContainer = document.querySelector('#demo-toast-example');
			var data = {message: 'Data updated successfully.'};
			snackbarContainer.MaterialSnackbar.showSnackbar(data);
		},
		error: function(response) {
			console.log(response);			
		}
	});	
});
jQuery( document ).ready(function() {	
	jQuery(".woocommerce-help-tip").tipTip();
	
	if(jQuery('#wc_sst_status_delivered').prop("checked") == true){
		jQuery('.status_label_color_th').show();		
	} else{
		jQuery('.status_label_color_th').hide();		
	}

	if(jQuery('#smart_shipment_status_partial_shipped').prop("checked") == true){
		jQuery('.partial_shipped_status_label_color_th').show();		
	} else{
		jQuery('.partial_shipped_status_label_color_th').hide();			
	}	
	
	jQuery('#wc_sst_status_label_color').wpColorPicker({
		change: function(e, ui) {
			var color = ui.color.toString();			
			jQuery('.order-status-table .order-label.wc-delivered').css('background',color);
		}, 
	});
	jQuery('#smart_shipment_status_partial_shipped_label_color').wpColorPicker({
		change: function(e, ui) {
			var color = ui.color.toString();			
			jQuery('.order-status-table .order-label.wc-partially-shipped').css('background',color);
		},
	});
	jQuery('#wc_sst_status_updated_tracking_label_color').wpColorPicker({
		change: function(e, ui) {
			var color = ui.color.toString();			
			jQuery('.order-status-table .order-label.wc-updated-tracking').css('background',color);
		},
	});
	jQuery('#wc_sst_select_primary_color').wpColorPicker({
		change: function(e, ui) {
			var color = ui.color.toString();		
			jQuery('#tracking_preview_iframe').contents().find('.bg-secondary').css('background-color',color);
			jQuery('#tracking_preview_iframe').contents().find('.tracker-progress-bar-with-dots .secondary .dot').css('border-color',color);
			jQuery('#tracking_preview_iframe').contents().find('.text-secondary').css('color',color);
			jQuery('#tracking_preview_iframe').contents().find('.progress-bar.bg-secondary:before').css('background-color',color);
			jQuery('#tracking_preview_iframe').contents().find('.tracking-number').css('color',color);
			jQuery('#tracking_preview_iframe').contents().find('.view_table_rows').css('color',color);
			jQuery('#tracking_preview_iframe').contents().find('.hide_table_rows').css('color',color);
			jQuery('#tracking_preview_iframe').contents().find('.tracking-detail.tracking-layout-2').css('color',color);
			jQuery('#tracking_preview_iframe').contents().find('.view_old_details').css('color',color);
			jQuery('#tracking_preview_iframe').contents().find('.hide_old_details').css('color',color);
			jQuery('#tracking_preview_iframe').contents().find('.tracking-table tbody tr td').css('color',color);			
		},
	});		
	jQuery('#wc_sst_select_border_color').wpColorPicker({
		change: function(e, ui) {
			var color = ui.color.toString();		
			jQuery('#tracking_preview_iframe').contents().find('.col.tracking-detail').css('border','1px solid '+color);
		},
	});		
	jQuery('.color_field input').wpColorPicker();		
});
jQuery(document).on("change", "#wc_sst_status_label_font_color", function(){
	var font_color = jQuery(this).val();
	jQuery('.order-status-table .order-label.wc-delivered').css('color',font_color);
});
jQuery(document).on("change", "#smart_shipment_status_partial_shipped_label_font_color", function(){
	var font_color = jQuery(this).val();
	jQuery('.order-status-table .order-label.wc-partially-shipped').css('color',font_color);
});
jQuery(document).on("change", "#wc_sst_status_updated_tracking_label_font_color", function(){
	var font_color = jQuery(this).val();
	jQuery('.order-status-table .order-label.wc-updated-tracking').css('color',font_color);
});
jQuery(document).on("click", '#variable_tag #var_input', function(e){
	jQuery(this).focus();
	jQuery(this).select();
	jQuery(this).next('.copy').show().delay(1000).fadeOut();	
	document.execCommand('copy');	
});