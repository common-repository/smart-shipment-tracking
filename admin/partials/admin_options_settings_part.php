<?php
$arrays=$this->get_settings_data();
$checked = '';



?>










		<table class="form-table">
			<tbody>



			<!-- 	<tr valign="top" class="">
															
					<th scope="row" class="titledesc">
						<label for="">Create Shipped Staus</label>
					</th>
										<td class="forminp">
                    														
								<span class=" multiple_checkbox">
									<label class="" for="create_shipped">
										<input type="hidden" name="include_tracking_info[create_shipped]" value="0">
										<input type="checkbox" id="create_shipped" name="include_tracking_info[create_shipped]" class="" checked="" value="1">
										<span class="multiple_label">Shipped</span>	
										<br>
									</label>																		
								</span>																									                                            
					</td>
				</tr> -->


            	<?php foreach( (array)$arrays as $id => $array ){
				
					if($array['show']){
					?>
                	<?php if($array['type'] == 'title'){ ?>
                		<tr valign="top titlerow">
                        	<th colspan="2"><h3><?php echo $array['title']?></h3></th>
                        </tr>    	
                    <?php continue;} ?>
				<tr valign="top" class="<?php echo $array['class']; ?>">
					<?php if($array['type'] != 'desc'){ ?>										
					<td colspan="1" scope="row" class="titledesc"  >
						<label for=""><?php echo $array['title']?><?php if(isset($array['title_link'])){ echo $array['title_link']; } ?>
							<?php if( isset($array['tooltip']) ){?>
                            	<span class="woocommerce-help-tip tipTip" title="<?php echo $array['tooltip']?>"></span>
                            <?php } ?>
                        </label>
					</td>
			
					<?php } ?>
					<td class="forminp"  <?php if($array['type'] == 'desc'){ ?> colspan=2 <?php } ?>>
                    	<?php if( $array['type'] == 'checkbox' ){								
                    		if(get_option($id)){
									$checked = 'checked';
							} else{
									$checked = '';
							} 
							
							if(isset($array['disabled']) && $array['disabled'] == true){
								$disabled = 'disabled';
								$checked = '';
							} else{
								$disabled = '';
							}


							?>

						<span class="mdl-list__item-secondary-action">

						
							<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="<?php echo $id?>">
								<input type="hidden" name="<?php echo $id?>" value="0"/>
								<input type="checkbox" id="<?php echo $id?>" name="<?php echo $id?>" class="mdl-switch__input" <?php echo $checked ?> value="1" <?php echo $disabled; ?>/>
							</label>
						</span>
                        <?php } elseif( $array['type'] == 'multiple_checkbox' ){ ?>
								<?php 
								$op = 1;	
								foreach((array)$array['options'] as $key => $val ){
									if($val['type'] == 'default'){											
										$multi_checkbox_data = get_option('wfsxc_checked_statuses');


								
										if(isset($multi_checkbox_data[$key]) && $multi_checkbox_data[$key] == 1){
											$checked="checked";
										} else{
											$checked="";
										}?>
								<span class=" multiple_checkbox">
									<label class="" for="<?php echo $key?>">
										<input type="hidden" name="<?php echo $id?>[<?php echo $key?>]" value="0"/>
										<input type="checkbox" id="<?php echo $key?>" name="<?php echo $id?>[<?php echo $key?>]" class=""  <?php echo $checked; ?> value="1"/>
										<span class="multiple_label"><?php echo $val['status']; ?></span>	
										</br>
									</label>																		
								</span>												
								<?php }									
								}
								foreach((array)$array['options'] as $key => $val ){	
							
									if($val['type'] == 'custom'){
										$multi_checkbox_data = get_option('wfsxc_checked_statuses');

																			
										if(isset($multi_checkbox_data[$key]) && $multi_checkbox_data[$key] == 1){
											$checked="checked";
										} else{
											$checked="";
										}
								if($op == 1){ ?>
									<div style="margin: 10px 0;">
										<strong style="font-weight: 700;padding-bottom: 3px;">
											<?php _e( 'Custom Statuses', 'shipment-tracking' ); ?>
										</strong>
									</div>
								<?php } ?>
								<span class="multiple_checkbox">
									<label class="" for="<?php echo $key?>">	
										<input type="hidden" name="<?php echo $id?>[<?php echo $key?>]" value="0"/>
										<input type="checkbox" id="<?php echo $key?>" name="<?php echo $id?>[<?php echo $key?>]" class=""  <?php echo $checked; ?> value="1"/>
										<span class="multiple_label"><?php echo $val['status']; ?></span>	
										</br>
									</label>																		
								</span>
									<?php $op++; }
								}
								?>
						
                        <?php }  elseif( isset( $array['type'] ) && $array['type'] == 'dropdown' ){?>
                        	<?php
								if( isset($array['multiple']) ){
									$multiple = 'multiple';
									$field_id = $array['multiple'];
								} else {
									$multiple = '';
									$field_id = $id;
								}
							?>

                        <?php } elseif( $array['type'] == 'title' ){?>
						<?php }

						elseif( $array['type'] == 'label' ){ ?>
							<fieldset>
                               <label><?php echo $array['value']; ?></label>
                            </fieldset>
						<?php }
						elseif( $array['type'] == 'tooltip_button' ){ ?>
							<fieldset>
								<a href="<?php echo $array['link']; ?>" class="button-primary" target="<?php echo $array['target'];?>"><?php echo $array['link_label'];?></a>
                            </fieldset>
						<?php }
						elseif( $array['type'] == 'button' ){ ?>
							<fieldset>
								<button class="button-primary btn_green2 <?php echo $array['button_class'];?>" <?php if($array['disable']  == 1){ echo 'disabled'; }?>><?php echo $array['label'];?></button>
							</fieldset>
						<?php }
						else { ?>
                                                    
                        	<fieldset>
                                <input class="input-text regular-input " type="text" name="<?php echo $id?>" id="<?php echo $id?>" style="" value="<?php echo get_option($id)?>" placeholder="<?php if(!empty($array['placeholder'])){echo $array['placeholder'];} ?>">
                            </fieldset>
                        <?php } ?>
                        
					</td>
				</tr>
				<?php if(isset($array['desc']) && $array['desc'] != ''){ ?>
					<tr class="<?php echo $array['class']; ?>"><td colspan="2" style=""><p class="description"><?php echo (isset($array['desc']))? $array['desc']: ''?></p></td></tr>
				<?php } ?>				
	<?php } } ?>
			</tbody>
		</table>
	<?php 