<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * A custom Expedited Order WooCommerce Email class
 *
 * @since 0.1
 * @extends \WC_Email
 */
class wfsxc_Shipped_Order_Email extends WC_Email {

	public function __construct() {

        // set ID, this simply needs to be a unique name
        $this->id = 'wc_shipped_order';

        // this is the title in WooCommerce Email settings
        $this->title = 'Shipped Order';

        // this is the description in WooCommerce email settings
        $this->description = 'This email is sent to customer including the tracking details when an order is shipped';

        // these are the default heading and subject lines that can be overridden using the settings
        $this->heading = 'Order Shipped';
        $this->subject = 'Shipped : Your Order is on the Way!';

        // these define the locations of the templates that this email should use, we'll just use the new order template since this email is similar
        $this->template_html  = 'emails/shipped-new-order.php';
        $this->template_plain = 'emails/plain/shipped-new-order.php';

      
        // Trigger on new paid orders

        $this->template_base = EMAIL_TEMPLATE_PATH;


        //add_action( 'woocommerce_order_status_wc-order-shipped', array( WC(), 'send_transactional_email' ), 10, 10 );
        //add_action("woocommerce_order_status_changed", array( $this, 'my_awesome_publication_notification' ),10, 4);

        // add_filter( 'woocommerce_email_actions', 'so_27112461_woocommerce_email_actions' );
        // add_action("woocommerce_order_status_order-shipped", array( $this, 'my_awesome_publication_notification' ));
        add_action("woocommerce_order_status_changed", array( $this, 'my_awesome_publication_notification' ),10, 4);




        // Call parent constructor to load any other defaults not explicity defined here
        parent::__construct();

        

        // this sets the recipient to the settings defined below in init_form_fields()
        $this->recipient = $this->get_option( 'recipient' );

        // if none was entered, just use the WP admin email as a fallback
        if ( ! $this->recipient )
            $this->recipient = get_option( 'admin_email' );
    }



    public function get_default_subject() {
            //return __( 'Your {site_title} order has been received!', 'woocommerce' );
        return __( 'Shipped : Your Order is on the Way!', 'woocommerce' );

        
    }

        /**
         * Get email heading.
         *
         * @since  3.1.0
         * @return string
         */
    public function get_default_heading() {
            return __( 'Thank you for your order', 'woocommerce' );
    }



    function my_awesome_publication_notification($order_id, $from_status, $to_status, $order) {
       global $woocommerce;
       $order = new WC_Order( $order_id );
     
       if($to_status === 'order-shipped' ) {
          	
       		$this->trigger($order_id);
         
         }

    }



    public function trigger( $order_id ) {

       

        // bail if no order ID is present

        if ( ! $order_id )
            return;

        // setup order object
        $this->object = new WC_Order( $order_id );


        // bail if shipping method is not expedited
        // if ( ! in_array( $this->object->get_shipping_method(), array( 'Three Day Shipping', 'Next Day Shipping' ) ) )
        //     return;
    
        // replace variables in the subject/headings
        $this->find[] = '{order_date}';
        $this->replace[] = date_i18n( woocommerce_date_format(), strtotime( $this->object->order_date ) );

        $this->find[] = '{order_number}';
        $this->replace[] = $this->object->get_order_number();

        if ( ! $this->is_enabled() || ! $this->get_recipient() )
            return;
   
        // woohoo, send the email!


        $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );


        //echo $this->get_content();
    }



    public function get_content_html() {
        ob_start();
        // woocommerce_get_template( $this->template_html, array(
        //     'order'         => $this->object,
        //     'email_heading' => $this->get_heading()
        // ) );
        


        wc_get_template( $this->template_html, array( 
                        'order'         => $this->object,
                        'email_heading' => "Your Order has been Shipped",
                        'email'    => $this,
                        'sent_to_admin'      => false,
                        'plain_text'         => false     
        ), 'woocommerce-tracking-addon/', EMAIL_TEMPLATE_PATH );

        return ob_get_clean();
    }


    /**
     * get_content_plain function.
     *
     * @since 0.1
     * @return string
     */
    public function get_content_plain() {
        ob_start();
        woocommerce_get_template( $this->template_plain, array(
            'order'         => $this->object,
            'email_heading' => "Your Order has been Shipped"
        ) );
        return ob_get_clean();
    }




     public function init_form_fields() {

        $this->form_fields = array(
            'enabled'    => array(
                'title'   => 'Enable/Disable',
                'type'    => 'checkbox',
                'label'   => 'Enable this email notification',
                'default' => 'yes'
            ),
            'recipient'  => array(
                'title'       => 'Recipient(s)',
                'type'        => 'text',
                'description' => sprintf( 'Enter recipients (comma separated) for this email. Defaults to <code>%s</code>.', esc_attr( get_option( 'admin_email' ) ) ),
                'placeholder' => '',
                'default'     => ''
            ),
            'subject'    => array(
                'title'       => 'Subject',
                'type'        => 'text',
                'description' => sprintf( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', $this->subject ),
                'placeholder' => '',
                'default'     => ''
            ),
            'heading'    => array(
                'title'       => 'Email Heading',
                'type'        => 'text',
                'description' => sprintf( __( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.' ), $this->heading ),
                'placeholder' => '',
                'default'     => ''
            ),
            'email_type' => array(
                'title'       => 'Email type',
                'type'        => 'select',
                'description' => 'Choose which format of email to send.',
                'default'     => 'html',
                'class'       => 'email_type',
                'options'     => array(
                    'plain'     => 'Plain text',
                    'html'      => 'HTML', 'woocommerce',
                    'multipart' => 'Multipart', 'woocommerce',
                )
            )
        );
    }





} 