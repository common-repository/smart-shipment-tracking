<?php
function wfsxc3dsa_showOptinPage($Triad_Mark_PLUGIN_SLUG_NAME,$Triad_Mark_PLUGIN_SLUG_NAME_WP,$Triad_Mark_PLUGIN_NAME,$Triad_Mark_PREFIX){

$user = wp_get_current_user();
$name = empty($user->user_firstname) ? $user->display_name : $user->user_firstname;
$email = $user->user_email;
$site_link = '<a href="' . get_site_url() . '">' . get_site_url() . '</a>';
$website = get_site_url();
$default_login_press_redirect = $Triad_Mark_PLUGIN_SLUG_NAME;
if (isset($_GET['redirect-page'])) {
    $default_login_press_redirect = sanitize_text_field(wp_unslash($_GET['redirect-page']));
}
?>


<style media="screen">
#wpwrap {
  background-color: #f1f1f1
}
#wpcontent {
  padding: 0!important
}
#triadmark-logo-wrapper {
  padding: 10px 0;
  width: 80%;
  margin: 0 auto;
  border-bottom: solid 1px #d5d5d5
}
#triadmark-logo-wrapper-inner {
  max-width: 600px;
  width: 100%;
  margin: auto
}
#triadmark-splash {
  width: 80%;
  margin: auto;
  background-color: #fdfdfd;
  text-align: center
}
#triadmark-splash h1 {
  margin-top: 40px;
  margin-bottom: 25px;
  font-size: 26px
}
#triadmark-splash-main {
  padding-bottom: 0
}
#triadmark-splash-permissions-toggle {
  font-size: 12px
}
#triadmark-splash-permissions-dropdown h3 {
  font-size: 16px;
  margin-bottom: 5px
}
#triadmark-splash-permissions-dropdown p {
  margin-top: 0;
  font-size: 14px;
  margin-bottom: 20px
}
#triadmark-splash-main-text {
  font-size: 16px;
  padding: 0;
  margin: 0
}
#triadmark-splash-footer {
  width: 80%;
  padding: 15px 0;
  border: 1px solid #d5d5d5;
  font-size: 10px;
  text-align: center;
  margin-top: 238px;
  margin-left: auto;
  margin-right: auto;
}
#triadmark-ga-optout-btn {
  background: none!important;
  border: none;
  padding: 0!important;
  font: inherit;
  color: #7f7f7f;
  border-bottom: 1px solid #7f7f7f;
  cursor: pointer;
  margin-bottom: 20px;
  font-size: 14px
}
.about-wrap .nav-tab + .nav-tab{
  border-left: 0;
}
.about-wrap .nav-tab:focus{

  box-shadow: none;
}
#triadmark-ga-submit-btn {
  height: 40px;
  margin: 30px;
  margin-bottom: 15px;
  font-size: 16px;
  line-height: 40px;
  padding: 0 20px;
}
#triadmark-ga-submit-btn:after{
  content: '\279C';
}
.triadmark-splash-box {
  width: 100%;
  max-width: 600px;
  background-color: #fff;
  border: solid 1px #d5d5d5;
  margin: auto;
  margin-bottom: 20px;
  text-align: center;
  padding: 15px
}

.about-wrap .nav-tab{
  height: auto;
  float: none;
  display: inline-block;
  margin-right: 0;
  margin-left: 0;
  font-size: 18px;
  width: 33.333%;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  padding: 8px 15px;
}
.step-wrapper .triadmark-splash-box{
  padding: 0;
  border: 0;
}
.nav-tab-wrapper{
  margin:0;
  font-size: 0;
}
.nav-tab-wrapper, .wrap h2.nav-tab-wrapper{
  margin:0;
  font-size: 0;
}
.triadmark-tab-content{
  display: none;
  border:1px solid #d5d5d5;
  padding:1px 20px 20px;
  border-top: 0;
}
.triadmark-tab-content.active{
  display: block;
}
.triadmark-seprator{
  border:0;
  border-top: 1px solid #ccc;
  margin: 50px 0;
}
.admin_page_triadmark-optin #wpwrap{
  background: #f1f1f1;
}
#wpbody{
  padding-right: 0;
}

#wpcontent{
  background-color: #f1f1f1;
}
#triadmark-splash{
  max-width: calc(100% - 64px);

  background: #f1f1f1;
}
.triadmark-splash-box{
  max-width: 100%;
  background: #f1f1f1;
  box-sizing: border-box;
  overflow: hidden;
}
.about-wrap {
  position: relative;
  margin: 25px 35px 0 35px;
  max-width: 80%;
  font-size: 15px;
  width: calc(100% - 64px);
  margin: 0 auto;
}
.triadmark-left-screenshot{
  float: left;
}
.about-wrap p{
  font-size: 14px;
}
.triadmark-text-settings h5{
  margin: 25px 0 5px;
}
.about-wrap .about-description, .about-wrap .about-text{
  font-size: 16px;
}
.about-wrap .feature-section h4,.about-wrap .changelog h3{
  font-size: 1em;
}
h5{
  font-size: 1em;
}
.about-wrap .feature-section img.triadmark-left-screenshot{
  margin-left: 0 !important;
  margin-right: 30px !important;
}
.about-wrap img{

  width: 50%;
}
.triadmark-text-settings{
  overflow: hidden;
}
#triadmark-splash-footer{
  margin-top: 50px;
}
.step-wrapper{
  width: 100%;
  transition: all 0.3s ease-in-out;
  -webkit-transition: all 0.3s ease-in-out;
}
/*.step-wrapper.slide{
  -webkit-transform: translateX(-50%);
  transform: translateX(-50%);
}*/
.step-wrapper:after{
  content: '';
  display: table;
  clear: both;
}
.step{
  width: 100%;
  float: left;
  padding: 0 20px;
  box-sizing: border-box;
}
.triadmark-welcome-screenshots{
  margin-left: 30px !important;
}
#triadmark-splash-footer{
  font-size: 12px;
}
.about-wrap .changelog.triadmark-backend-settings{
  margin-bottom: 20px;
}
.triadmark-backend-settings .feature-section{
  padding-bottom: 20px;
}
a.triadmark-ga-button.button.button-primary{
  height: auto !important;
}
.changelog:last-child{
  margin-bottom: 0;
}
.changelog:last-child .feature-section{
  padding-bottom: 0;
}

#triadmark-logo-text{
  margin-right: 40px;
  position: relative;
  bottom: 0px;
  width: 55px;
  vertical-align: middle;
}
    .triadmark-badge {
      height: 200px;
      width: 200px;
      margin: -12px -5px;
      background: url("<?php echo plugins_url('assets/images/welcome-triadmark.png', __FILE__); ?>") no-repeat;
      background-size: 100% auto;
    }

    .about-wrap .triadmark-badge {
      position: absolute;
      top: 0;
      right: 0;
    }

    .triadmark-welcome-screenshots {
      float: right;
      margin-left: 10px !important;
      border:1px solid #ccc;
      padding:0;
      box-shadow:4px 4px 0px rgba(0,0,0,.05)
    }

    .about-wrap .feature-section {
      margin-top: 20px;
    }

    .about-wrap .feature-section p{
      max-width: none !important;
    }

    .triadmark-welcome-settings{
      clear: both;
      padding-top: 20px;
    }
    .triadmark-left-screenshot {
      float: left !important;
  }
</style>


<?php

//admin_url( 'admin.php?page=' . $default_login_press_redirect )
echo '<form method="post" action="' . admin_url('admin.php?page=' . $Triad_Mark_PLUGIN_SLUG_NAME . '-optin') . '">';
echo "<input type='hidden' name='email' value='$email'>";
echo '<div id="triadmark-splash" style="padding-top:1px">';
echo '<h1> <img id="triadmark-logo-text" src="https://ps.w.org/' . $Triad_Mark_PLUGIN_SLUG_NAME_WP . '/assets/icon-256x256.png"> ' . __('Welcome to ' . $Triad_Mark_PLUGIN_NAME, $Triad_Mark_PLUGIN_NAME) . '</h1>';
echo '<div id="triadmark-splash-main" class="triadmark-splash-box">';
echo '<div class="step-wrapper">';
if (get_option('_Triad_Mark_optin') == 'no' || !get_option('_Triad_Mark_optin')) {
    echo "<div class='first-step step'>";
    echo sprintf(__('%1$s Hey %2$s,  %4$s If you opt-in some data about your installation of ' . $Triad_Mark_PLUGIN_NAME . ' will be sent to <a href="https://www.triadmark.com">Triad Mark</a> (This doesn\'t include stats)%4$s and You will receive new feature updates, security notifications etc %5$sNo Spam.%6$s %4$s%4$s Help us %7$sImprove ' . $Triad_Mark_PLUGIN_NAME . '%8$s %4$s %4$s ', 'triadmark'), '<p id="triadmark-splash-main-text">', '<strong>' . $name . '</strong>', '<strong>' . $website . '</strong>', '<br>', '<i>', '</i>', '<strong>', '</strong>') . '</p>';
    echo "<button type='submit' id='triadmark-ga-submit-btn' class='triadmark-ga-button button button-primary' name='".$Triad_Mark_PREFIX."-submit-optin' >" . __('Allow and Continue  ', $Triad_Mark_PLUGIN_NAME) . "</button><br>";
    echo "<button type='submit' id='triadmark-ga-optout-btn' name='".$Triad_Mark_PREFIX."-submit-optout' >" . __('Skip This Step', $Triad_Mark_PLUGIN_NAME) . "</button>";
    echo '<div id="triadmark-splash-permissions" class="triadmark-splash-box">';
    echo '<a id="triadmark-splash-permissions-toggle" href="#" >' . __('What permissions are being granted?', $Triad_Mark_PLUGIN_NAME) . '</a>';
    echo '<div id="triadmark-splash-permissions-dropdown" style="display: none;">';
    echo '<h3>' . __('Your Website Overview', $Triad_Mark_PLUGIN_NAME) . '</h3>';
    echo '<p>' . __('Your Site URL, WordPress & PHP version, plugins & themes. This data lets us make sure this plugin always stays compatible with the most popular plugins and themes.', $Triad_Mark_PLUGIN_NAME) . '</p>';
    echo '<h3>' . __('Your Profile Overview', $Triad_Mark_PLUGIN_NAME) . '</h3>';
    echo '<p>' . __('Your name and email address.', $Triad_Mark_PLUGIN_NAME) . '</p>';
    echo '<h3>' . __('Admin Notices', $Triad_Mark_PLUGIN_NAME) . '</h3>';
    echo '<p>' . __("Updates, Announcement, Marketing. No Spam.", $Triad_Mark_PLUGIN_NAME) . '</p>';
    echo '<h3>' . __('Plugin Actions', $Triad_Mark_PLUGIN_NAME) . '</h3>';
    echo '<p>' . __("Active, Deactive, Uninstallation and How you use this plugin's features and settings. This is limited to usage data. It does not include any of your sensitive " . $Triad_Mark_PLUGIN_NAME . " data, such as traffic. This data helps us learn which features are most popular, so we can improve the plugin further.", $Triad_Mark_PLUGIN_NAME) . '</p>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}
echo '</div>';
echo '</div>';
echo '</div>';
echo '</form>';
?>

<script type="text/javascript">
jQuery(document).ready(function(s) {
  var o = parseInt(s("#triadmark-splash-footer").css("margin-top"));
  s("#triadmark-splash-permissions-toggle").click(function(a) {
    a.preventDefault(), s("#triadmark-splash-permissions-dropdown").toggle(), 1 == s("#triadmark-splash-permissions-dropdown:visible").length ? s("#triadmark-splash-footer").css("margin-top", o - 208 + "px") : s("#triadmark-splash-footer").css("margin-top", o + "px")
  })
});

</script>


<?php
}
?>