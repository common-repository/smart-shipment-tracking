<?php

if (class_exists('Triad_Mark_views')) {
    return;
}
class Triad_Mark_views {
	private $mainObj;
	function __construct($mainObj){
		$this->mainObj=$mainObj;
	}

	function Triad_Mark_users_name( $user_id = null ) {
		$user_info = $user_id ? new WP_User( $user_id ) : wp_get_current_user();
		if ( $user_info->first_name ) {
			if ( $user_info->last_name ) {
				return $user_info->first_name . ' ' . $user_info->last_name;
			}
			return $user_info->first_name;
		}
		return $user_info->display_name;
	}

	function Triad_Mark_contactView(){
		$current_user = wp_get_current_user();

		?>


		<style type="text/css">
			.subhead{
				    margin-top: 7px;
    				margin-bottom: 4px;
			}
		</style>

		<div id="contactMain">
			<section>
			<header id="header" class="card clearfix">
			<div class="product-header">
			<div class="product-icon">
				

			</div>
			<div class="product-header-body">
			<h1 class="page-title">Have questions? We're happy to help!</h1>
			<h2 class="plugin-title"><?php _e($this->mainObj->get_Triad_Mark_PLUGIN_NAME() , $this->mainObj->get_Triad_Mark_PLUGIN_NAME()); ?></h2>
			<h3>We'll do our best to get back to you as soon as we can.</h3>
			</div>
			</div>
			</header>
			</section>
			<div class="rw-ui-section-container clearfix">
			<section>



			<div>
			<section id="widgets" class="card">
			 <header><h3>Frequently Asked Questions</h3></header>
			<div id="faq">
			<ul class="clearfix">
			<li><p>All submitted data will not be saved and is used solely for the purposes of your support request. You will not be added to a mailing list, solicited without your permission, nor will your site be administered after this support case is closed.</p></li>

			</ul>
			</div>
			</section>



			<section id="contact_form" class="message embed wp-core-ui relative">
			<div>
			<fieldset>
			<input name="security" type="hidden" value="s_secure=6f84ae0ceeff69f6b04d8eed5ce84fc6&amp;s_ts=1581940909">
			<input name="install_id" type="hidden" value="">
			<label class="iconed-input name"><i class="name"></i><input  type="text" name="name" id="name" value="<?php echo esc_html( $this->Triad_Mark_users_name() )  ?>" placeholder="First and Last Name"></label>
			<label class="iconed-input"><i class="email"></i><input type="email" id="email" name="email" value="<?php echo esc_html( $current_user->user_email ); ?>" placeholder="Your Email Address"></label>
			<label class="iconed-input module" style="display: none">

			<select id="context_plugin">
			<option value="5448" selected=""><?php _e($this->mainObj->get_Triad_Mark_PLUGIN_NAME() , $this->mainObj->get_Triad_Mark_PLUGIN_NAME()); ?></option>
			</select>
			</label>




			<input type="hidden" id="plugin_name" name="plugin_name" value="<?php echo $this->mainObj->get_Triad_Mark_PLUGIN_NAME(); ?>">
	




			<ul class="subjects iconed-input">
			<li><label><input type="radio" name="subject" value="Billing Issue"> Billing Issue</label></li>
			<li><label><input type="radio" name="subject" value="Feature Request"> Feature Request</label></li>
			<li><label><input type="radio" name="subject" value="Customization"> Customization</label></li>
			<li><label><input type="radio" name="subject" value="Pre Sale Question"> Pre-Sale Question</label></li>
			<li><label><input type="radio" name="subject" value="Press"> Press</label></li>
			<li><label><input type="radio" name="subject" value="Bug"> Bug</label></li>
			</ul>
				

			
			<p class="subhead">Message:</p>
			<textarea style="width:100%;height: 100px" id="message"></textarea>


			<p class="subhead">Attachments:</p>
			<input type="file" name="attachment[]" id="attachment" multiple>
			</fieldset>
			
			<div class="message-sent">
			<p>Your message has been sent! We'll get back to you as soon as we can.</p>
			<h5>Be AWESOME and spread the word:</h5>
			</div>
			</div>
			<footer>

			<button id="submitContact" value="Send Message" class="primary large button-primary"><span>Send Message <sub>›</sub></span></button>
			
			</footer>
			</section>
			</div>
			</section>
			</div>
			<input type="hidden" id="parent_url" value="">
	</div>

		<?php
	}


	
}