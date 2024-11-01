<?php

function wfsxc_get_provider_html($default_shippment_providers,$status){
		$WC_Countries = new WC_Countries();
?>
		<div class="provider_list">
		<?php 
			if($default_shippment_providers){
			foreach($default_shippment_providers as $d_s_p){ ?>
			<div class="provider <?php if($d_s_p->display_in_order == 1) { echo 'active_provider'; } ?>">
				<div class="provider_inner">
					<div class="row-1">
						<div class="left-div">
							<a href="<?php echo str_replace("%number%","",$d_s_p->provider_url ); ?>" title="<?php echo str_replace("%number%","",$d_s_p->provider_url ); ?>" target="_blank">
							<?php  if( $d_s_p->shipping_default == 1 ){ ?>
							<img class="provider-thumb" src="<?php echo plugin_dir_url(dirname(__DIR__, 1)) ?>admin/provider-img/<?php echo sanitize_title($d_s_p->provider_name);?>.png?v=">
							<?php } else{ 
							$custom_thumb_id = $d_s_p->custom_thumb_id;
							$image_attributes = wp_get_attachment_image_src( $custom_thumb_id , array('60','60') );
							//echo '<pre>';print_r($custom_thumb_id);echo '</pre>';exit;
							if($custom_thumb_id != 0){ ?>
								<img class="provider-thumb" src="<?php echo $image_attributes[0]; ?>">
							<?php } else{
							?>
								<img class="provider-thumb" src="<?php echo plugin_dir_url(dirname(__DIR__, 1)) ?>admin/provider-img/icon-default.png">
							<?php } ?>
							<?php } ?>					
							</a>
						</div>
						<div class="right-div">
							<a href="<?php echo str_replace("%number%","",$d_s_p->provider_url ); ?>" title="<?php echo str_replace("%number%","",$d_s_p->provider_url ); ?>" target="_blank">
								<span class="provider_name"><?php echo $d_s_p->provider_name; ?></span>
							</a>
							<br>
							<span class="provider_country"><?php
										$search  = array('(US)', '(UK)');
										$replace = array('', '');
										if($d_s_p->shipping_country && $d_s_p->shipping_country != 'Global'){
											echo str_replace($search, $replace, $WC_Countries->countries[$d_s_p->shipping_country]);
										} elseif($d_s_p->shipping_country && $d_s_p->shipping_country == 'Global'){
											echo 'Global';
										}													
										?></span>
						</div>
					</div>
					<div class="row-2">
						<div class="default-provider">
							<?php $default_provider = get_option("wfsxc_default_provider" );?>
							<label for="make_default_<?php echo $d_s_p->id; ?>" id="default_label_<?php echo $d_s_p->id; ?>" class="<?php if($d_s_p->display_in_order != 1) { echo 'disable_label'; } ?>">
								<input type="checkbox" id="make_default_<?php echo $d_s_p->id; ?>" name="make_provider_default" data-id="<?php echo $d_s_p->id; ?>" class="make_provider_default" value="<?php echo sanitize_title( $d_s_p->provider_name )?>" <?php if( $default_provider == sanitize_title( $d_s_p->provider_name ) )echo 'checked';?> <?php if($d_s_p->display_in_order != 1) { echo 'disabled'; } ?>>
								<span>default</span>
							</label>
						</div>
						<div class="provider-status">
							<?php if( $d_s_p->shipping_default == 0 ){ ?>
								<span class="dashicons dashicons-edit edit_provider" data-pid="<?php echo $d_s_p->id; ?>"></span>
								<span class="dashicons dashicons-trash remove" data-pid="<?php echo $d_s_p->id; ?>"></span>													
							<?php } ?>
							<span class="mdl-list__item-secondary-action">
								<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="list-switch-<?php echo $d_s_p->id; ?>">
									<input type="checkbox" name="select_custom_provider[]" id="list-switch-<?php echo $d_s_p->id; ?>" class="mdl-switch__input status_slide" value="<?php echo $d_s_p->id; ?>" <?php if($d_s_p->display_in_order == 1) { echo 'checked'; } ?> />
									<span>Enable</span>
								</label>
							</span>
						</div>
					</div>
				</div>	
			</div>	
			<?php } } else{ 
				$p_status = 'active';
			?>
				<h3><?php echo sprintf(__("You don't have any %s shipping providers.", 'shipment-tracking'), $p_status); ?></h3>
			<?php }
			if($status == 'custom'){ ?>
				<div class="provider">
					<div class="provider_inner add_custom_provider_div">
						<div class="add_custom_p_a"><?php _e("Add Custom Provider", "shipment-tracking")?></div>
						<a href="javascript:void(0);" class="add_custom_inner add_custom_provider"><span class="dashicons dashicons-plus-alt"></span></a>
					</div>
				</div>
			<?php }
			?>		
		</div>	
		<?php 
	}