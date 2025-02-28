
<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php /* translators: %s: Customer first name */ ?>
<p><?php printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $order->get_billing_first_name() ) ); ?></p>
<?php /* translators: %s: Site title */ ?>
<p><?php esc_html_e( 'Here are the details of your order', 'woocommerce' ); ?></p>
<?php



echo "<div style='margin-top:40px'></div>";
do_action( 'champaklal', $order, $sent_to_admin, $plain_text, $email );



do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );


do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );


do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );



do_action( 'woocommerce_email_footer', $email );

