
<section id="content6" class="tab_section">
	<div class="d_table" style="">
		<div class="tab_inner_container">	
			<form method="post" id="wc_sst_addons_form" class="addons_inner_container" action="" enctype="multipart/form-data"> 

				<?php 

				foreach ($addons['plugins'] as $key => $value) { ?>



					<div class="sst_addons_section">	
					<table class="form-table heading-table">
						<tbody>
							<tr valign="top" class="addons_header tracking_item_addons_header">
								<td>
									<img style="width: 51px;" src="<?php echo 'https://ps.w.org/' . $value['slug'] . '/assets/icon-256x256.png' ?>">
								</td>
								<td>
									<h3 style="margin-left: 14px;"><?php echo $value['name'] ?></h3>
								</td>
							</tr>

						</tbody>
					</table>
						
					<table class="form-table">
						<tbody>						
							<tr style="height: 140px;">
								<td>
									<p style="margin-top: 4px;"><?php echo $value['description'] ?></p>
								</td>
							</tr>
						</tbody>
					</table>	
					<table class="form-table">
						<tbody>
							<tr valign="top">						
								<td class="button-column">
									<div class="submit">																
										<a href="https://wordpress.org/plugins/<?php echo $value['slug'] ?>" target="blank" class="button-primary btn_ast2 btn_large">Get This Add-on &gt;</a>	
									</div>	
								</td>
							</tr>
						</tbody>
					</table>							
									</div>
				
				

				<?php
				}
				?>

			
			</form>
		</div>
<?php include 'woo-ship_admin_sidebar.php'; ?>
	</div>
</section>