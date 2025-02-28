
jQuery( function( $ ) {

	var wc_shipment_tracking_items = {

		// init Class
		init: function() {
			$( '#wfsxc-shipment-tracking' )
				.on( 'click', 'a.delete-shipment-tracking', this.delete_tracking )				
				.on( 'click', 'button.show-tracking-form', this.show_form )
				.on( 'click', 'button.button-save-form-tracking', this.save_form );
		},

		// When a user enters a new tracking item
		save_form: function () {
		
			var error;	
			var tracking_number = jQuery("#tracking_num");
			var tracking_provider = jQuery("#tracking_pro");
			
			if( tracking_number.val() === '' ){				
				showerror( tracking_number );error = true;
			} else{
				var pattern = /^[0-9a-zA-Z- \b]+$/;			
				if(!pattern.test(tracking_number.val())){			
					showerror( tracking_number );
					error = true;
				} else{
					hideerror(tracking_number);
				}				
			}
			if( tracking_provider.val() === '' ){				
				jQuery("#tracking_pro").siblings('.select2-container').find('.select2-selection').css('border-color','red');
				error = true;
			} else{
				jQuery("#tracking_pro").siblings('.select2-container').find('.select2-selection').css('border-color','#ddd');
				hideerror(tracking_provider);
			}
			
			
			if(jQuery("tr").hasClass("ASTProduct_row")){
				var qty = false;
				jQuery(".ASTProduct_row").each(function(index){
					var ASTProduct_qty = jQuery(this).find('input[type="number"]').val();
					if(ASTProduct_qty > 0){
						qty = true;		
						return false;					
					}
				});						
			}

			if(qty == false){
				jQuery('.qty_validation').show();
				return false;
			} else{
				jQuery('.qty_validation').hide();
			} 
			
			if(error == true){
				return false;
			}
			if ( !$( 'input#tracking_num' ).val() ) {
				return false;
			}

			$( '#shipment-tracking-form' ).block( {
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			} );
			var change_order_to_shipped = jQuery('input[name=change_to_shipped]:checked').val();
			
			 if(change_order_to_shipped == 'yes'){
				checked = 'change_order_to_shipped';
			} else if($('input#change_to_shipped').prop("checked") == true){
				checked = 'change_order_to_shipped';
			} else{
				checked = 'no';
			}
						
			var product_data = [];
			jQuery(".ASTProduct_row").each(function(index){
				var ASTProduct_qty = jQuery(this).find('input[type="number"]').val();
				if(ASTProduct_qty > 0){
					product_data.push({
						product: jQuery(this).find('.product_id').val(),				
						qty: jQuery(this).find('input[type="number"]').val(),				
					});					
				}
			});	
			
			var jsonString = JSON.stringify(product_data);						
			var data = {
				action:                   'wfsxc_shipment_tracking_form',
				order_id:                 woocommerce_admin_meta_boxes.post_id,
				tracking_provider:        $( '#tracking_pro' ).val(),
				custom_tracking_provider: $( '#custom_tracking_provider' ).val(),
				custom_tracking_link:     $( 'input#custom_tracking_link' ).val(),
				tracking_number:          $( 'input#tracking_num' ).val(),
				date_shipped:             $( 'input#date_shipped' ).val(),
				note:             		  $( 'textarea#tracking_note' ).val(),
				productlist: 	          jsonString, 
				change_order_to_shipped:  checked,
				security:                 $( '#wc_shipment_tracking_create_nonce' ).val()
			};
			
			jQuery.ajax({
				url: woocommerce_admin_meta_boxes.ajax_url,		
				data: data,
				type: 'POST',				
				success: function(response) {				
					$( '#shipment-tracking-form' ).unblock();
					if ( response == 'reload' ) {
						location.reload(true);
						return false;
					}
					if ( response != '-1' ) {
						$( '#shipment-tracking-form' ).hide();
						$( '#wfsxc-shipment-tracking #tracking-itemss' ).append( response );
						$( '#wfsxc-shipment-tracking button.show-tracking-form' ).show();
						$( '#tracking_provider' ).selectedIndex = 0;
						$( '#custom_tracking_provider' ).val( '' );
						$( 'input#custom_tracking_link' ).val( '' );
						$( 'input#tracking_num' ).val( '' );
						$( 'input#date_shipped' ).val( '' );
						if(checked == 'change_order_to_shipped'){
							jQuery('#order_status').val('wc-order-shipped');											
							jQuery('#order_status').select2().trigger('change');
							jQuery('#post').before('<div id="order_updated_message" class="updated notice notice-success is-dismissible"><p>Order updated.</p><button type="button" class="notice-dismiss update-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>');						
						} else if(checked == 'change_order_to_partial_shipped'){
							jQuery('#order_status').val('wc-partial-shipped');											
							jQuery('#order_status').select2().trigger('change');
							jQuery('#post').before('<div id="order_updated_message" class="updated notice notice-success is-dismissible"><p>Order updated.</p><button type="button" class="notice-dismiss update-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>');						
						}
					}
				},
				error: function(response) {
					console.log(response);			
				}
			});			
			return false;
		},

		// Show the new tracking item form
		show_form: function () {
			$( '#wfsxc-shipment-tracking #shipment-tracking-form' ).show();
			$( '#wfsxc-shipment-tracking .show-tracking-form' ).hide();
		},

		// Delete a tracking item
		delete_tracking: function() {

			var tracking_id = $( this ).attr( 'rel' );

			$( '#tracking-item-' + tracking_id ).block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});

			var data = {
				action:      'wfsxc_shipment_tracking_delete_item',
				order_id:    woocommerce_admin_meta_boxes.post_id,
				tracking_id: tracking_id,
				security:    $( '#wc_shipment_tracking_delete_nonce' ).val()
			};

			$.post( woocommerce_admin_meta_boxes.ajax_url, data, function( response ) {
				$( '#tracking-item-' + tracking_id ).unblock();
				if ( response != '-1' ) {
					$( '#tracking-item-' + tracking_id ).remove();
				}
			});

			return false;
		},

		refresh_items: function() {
			var data = {
				action:                   'wc_shipment_tracking_get_items',
				order_id:                 woocommerce_admin_meta_boxes.post_id,
				security:                 $( '#wc_shipment_tracking_get_nonce' ).val()
			};

			$( '#woocommerce-shipment-tracking' ).block( {
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			} );

			$.post( woocommerce_admin_meta_boxes.ajax_url, data, function( response ) {
				$( '#woocommerce-shipment-tracking' ).unblock();
				if ( response != '-1' ) {
					$( '#woocommerce-shipment-tracking #tracking-itemss' ).html( response );
				}
			});
		},
	}

	wc_shipment_tracking_items.init();

	window.wc_shipment_tracking_refresh = wc_shipment_tracking_items.refresh_items;
} );
jQuery(document).on("click", ".update-dismiss", function(){	
	jQuery('#order_updated_message').fadeOut();
});
function showerror(element){
	element.css("border-color","red");
}
function hideerror(element){
	element.css("border-color","");
}
jQuery(document).ready(function() {
	jQuery('#tracking_provider').select2({
		matcher: modelMatcher
	});
});
function modelMatcher (params, data) {				
	data.parentText = data.parentText || "";
	
	// Always return the object if there is nothing to compare
	if (jQuery.trim(params.term) === '') {
		return data;
	}
	
	// Do a recursive check for options with children
	if (data.children && data.children.length > 0) {
		// Clone the data object if there are children
		// This is required as we modify the object to remove any non-matches
		var match = jQuery.extend(true, {}, data);
	
		// Check each child of the option
		for (var c = data.children.length - 1; c >= 0; c--) {
		var child = data.children[c];
		child.parentText += data.parentText + " " + data.text;
	
		var matches = modelMatcher(params, child);
	
		// If there wasn't a match, remove the object in the array
		if (matches == null) {
			match.children.splice(c, 1);
		}
		}
	
		// If any children matched, return the new object
		if (match.children.length > 0) {
		return match;
		}
	
		// If there were no matching children, check just the plain object
		return modelMatcher(params, match);
	}
	
	// If the typed-in term matches the text of this term, or the text from any
	// parent term, then it's a match.
	var original = (data.parentText + ' ' + data.text).toUpperCase();
	var term = params.term.toUpperCase();
	
	
	// Check if the text contains the term
	if (original.indexOf(term) > -1) {
		return data;
	}
	
	// If it doesn't contain the term, don't return anything
	return null;
}

jQuery(document).on("click", ".add_inline_tracking", function(){
	
	jQuery(this).closest('.wc_actions').block({
		message: null,
		overlayCSS: {
			background: "#fff",
			opacity: .6
		}	
    });
	
	var order_id = jQuery(this).attr('href');
	order_id = order_id.replace("#", "");
	jQuery('.add_tracking_number_form #order_id').val(order_id);	
	
	var ajax_data = {
		action: 'sst_open_inline_tracking_form',
		order_id: order_id,	
	};
	
	jQuery.ajax({
		url: ajaxurl,		
		data: ajax_data,
		type: 'POST',						
		success: function(response) {
			jQuery( ".add_tracking_popup" ).remove();
			jQuery("body").append(response);						
			jQuery('.add_tracking_popup').show();
			jQuery('.wc_actions').unblock();
		},
		error: function(response) {			
			jQuery('.wc_actions').unblock();			
		}
	});		
});

jQuery(document).on("click", ".mark_shipped_checkbox", function(){
	if(jQuery(this).prop("checked") == true){
		jQuery('.mark_shipped_checkbox').prop('checked', false);
		jQuery(this).prop('checked', true);		
	}
});
	
jQuery(document).on("click", ".popupclose", function(){
	jQuery('.add_tracking_popup').hide();	
});

jQuery(document).on("submit", "#add_tracking_number_form", function(){
	
	var form = jQuery('#add_tracking_number_form');
	var error;
	var tracking_provider = jQuery("#add_tracking_number_form #tracking_provider");
	var tracking_number = jQuery("#add_tracking_number_form #tracking_number");
	var date_shipped = jQuery("#add_tracking_number_form #date_shipped");
		
	
	if( tracking_provider.val() === '' ){				
		showerror(tracking_provider);
		error = true;
	} else{		
		hideerror(tracking_provider);
	}	
	
	if( tracking_number.val() === '' ){				
		showerror(tracking_number);
		error = true;
	} else{		
		var pattern = /^[0-9a-zA-Z- \b]+$/;	
		if(!pattern.test(tracking_number.val())){			
			showerror(tracking_number);
			error = true;
		} else{
			hideerror(tracking_number);
		}
	}	
	
	if( date_shipped.val() === '' ){				
		showerror(date_shipped);
		error = true;
	} else{		
		hideerror(date_shipped);
	}
	
	
	if(jQuery("tr").hasClass("ASTProduct_row")){
		var qty = false;
		jQuery(".ASTProduct_row").each(function(index){
			var ASTProduct_qty = jQuery(this).find('input[type="number"]').val();
			if(ASTProduct_qty > 0){
				qty = true;		
				return false;					
			}
		});						
	}

	if(qty == false){
		jQuery('.qty_validation').show();
		return false;
	} else{
		jQuery('.qty_validation').hide();
	} 
	
	if(error == true){
		return false;
	}	
	
	jQuery("#add_tracking_number_form").block({
		message: null,
		overlayCSS: {
			background: "#fff",
			opacity: .6
		}	
    });
	jQuery.ajax({
		url: ajaxurl,		
		data: form.serialize(),
		type: 'POST',		
		success: function(response) {				
			location.reload();
		},
		error: function(response) {
			console.log(response);			
		}
	});
	return false;
});


jQuery(document).on("click", ".inline_tracking_delete", function(){
	var r = confirm( 'Do you really want to delete tracking number?' );
	if (r === true) {
		var tracking_id = jQuery( this ).attr( 'rel' );	
		var order_id = jQuery( this ).data( 'order' );	
		jQuery( '#tracking-item-' + tracking_id ).block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		var ajax_data = {
			action: 'wfsxc_shipment_tracking_delete_item',		
			tracking_id: tracking_id,
			order_id: order_id,
		};
			jQuery.ajax({
				url: ajaxurl,		
				data: ajax_data,
				type: 'POST',		
				success: function(response) {				
					jQuery( '#tracking-item-' + tracking_id ).unblock();
					if ( response != '-1' ) {
						jQuery( '.tracking-item-' + tracking_id ).remove();
					}
				},
				error: function(response) {
					console.log(response);			
				}
			});
	} else {		
		return;
	}	
});

function showerror(element){
	element.css("border","1px solid red");
}
function hideerror(element){
	element.css("border","1px solid #ddd");
}