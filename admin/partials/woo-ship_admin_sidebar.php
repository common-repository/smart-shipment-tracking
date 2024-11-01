<?php

$args=array("plugin_name"=>"Smart Shipment Tracking");

$response=wp_remote_get( "http://plugins.triadmark.com/pluginsAnalytics/sidebar.php?plugin_name=Smart%20Shipment%20Tracking", $args);


$body=$response['body'];




$response=json_decode($body,true);



?>

<div class="woo-ship_admin_sidebar">
	<div class="woo-ship_admin_sidebar_inner">

		<div class="woo-ship-sidebar__section">                    	
			<h3 class="top-border">Your opinion matters to us!</h3>
			<p>If you enjoy using Smart Shipment Tracking plugin, please take a minute and <a href="https://wordpress.org/support/plugin/smart-shipment-tracking/reviews/#new-post" target="_blank">share your review</a>		
			</p>        
		</div>    	
			
		<div class="woo-ship-sidebar__section">
			<h3 class="top-border">More plugins by Triad Mark</h3>
				
			<ul>

				<?php

				if(isset($response['plugins']) && count($response['plugins'])){
					foreach ($response['plugins'] as $key => $value) {
					?>
					<li><img class="plugin_thumbnail" src=<?php echo 'https://ps.w.org/' . $value['slug'] . '/assets/icon-256x256.png' ?>><a class="plugin_url" style="margin-top: -73px;" href="https://wordpress.org/plugins/<?php echo $value['slug']; ?>" target="_blank"><?php echo $value['name'] ?></a></li>
				
				<?php
					}

			

				}
				
	?>

							</ul>  
				
		</div>
	</div>
</div>