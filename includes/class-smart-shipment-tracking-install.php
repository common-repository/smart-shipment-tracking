<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class wfsxc_Install {



	/**
	 * Instance of this class.
	 *
	 * @var object Class Instance
	 */
	private static $instance;
	
	/**
	 * Initialize the main plugin function
	*/
    public function __construct($wfsxc3dsa_qweaw, $version) {
		
		global $wpdb;

		$this->wfsxc3dsa_qweaw = $wfsxc3dsa_qweaw;
		$this->version = $version;
		
		$this->init();	
    }
	
	/**
	 * Get the class instance
	 *
	 * @return 
	*/
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
	
	/*
	* init from parent mail class
	*/
	public function init(){

		$this->shippment_tracking_install();				


	}


	public function shippment_tracking_install(){
		
		global $wpdb;	


		
		$woo_shippment_table_name = $wpdb->prefix."wfsxc_shippment_provider";

		if($wpdb->get_var("show tables like '$woo_shippment_table_name'") != $woo_shippment_table_name) 
		{
			$charset_collate = $wpdb->get_charset_collate();
			
			$sql = "CREATE TABLE $woo_shippment_table_name (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				provider_name varchar(500) DEFAULT '' NOT NULL,
				ts_slug text NULL DEFAULT NULL,
				provider_url varchar(500) DEFAULT '' NULL,
				shipping_country varchar(45) DEFAULT '' NULL,
				shipping_default tinyint(4) NULL DEFAULT '0',
				custom_thumb_id int(11) NOT NULL DEFAULT '0',
				display_in_order tinyint(4) NOT NULL DEFAULT '1',
				sort_order int(11) NOT NULL DEFAULT '0',				
				PRIMARY KEY  (id)
			) $charset_collate;";			
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
						
			$providers = $this->provider_list();
			$default_country=WC()->countries->get_base_country();
			foreach($providers as $shipping_provider){				
				$shipping_provider['provider_name'];


				if($default_country == $shipping_provider['shipping_country']){

					$success = $wpdb->insert($woo_shippment_table_name, array(
						"provider_name" => $shipping_provider['provider_name'],
						"ts_slug" => $shipping_provider['ts_slug'],
						"provider_url" => $shipping_provider['provider_url'],
						"shipping_country" => $shipping_provider['shipping_country'],
						"shipping_default" => $shipping_provider['shipping_default'],
						"display_in_order" => '1'
					));

				}
				else{
					$success = $wpdb->insert($woo_shippment_table_name, array(
						"provider_name" => $shipping_provider['provider_name'],
						"ts_slug" => $shipping_provider['ts_slug'],
						"provider_url" => $shipping_provider['provider_url'],
						"shipping_country" => $shipping_provider['shipping_country'],
						"shipping_default" => $shipping_provider['shipping_default'],
						"display_in_order" => '0'
					));
				}
				
			}

		}			
	}



	public function provider_list(){
		$providers = array( 
			0 => array (
				"provider_name" => 'Australia Post',				
				"ts_slug" => 'australia-post',
				"provider_url" => 'http://auspost.com.au/track/track.html?id=%number%',
				"shipping_country" => 'AU',
				"shipping_default" => '1',
				"display_in_order" => '0'
			),
			
			1 => array (
				"provider_name" => 'Fastway AU',
				"ts_slug" => 'fastway-au',
				"provider_url" => 'http://www.fastway.com.au/courier-services/track-your-parcel?l=%number%',
				"shipping_country" => 'AU',
				"shipping_default" => '1',
				"display_in_order" => '0'
			),
			
			2 => array (
				"provider_name" => 'post.at',
				"ts_slug" => 'post-at',
				"provider_url" => 'http://www.post.at/sendungsverfolgung.php?pnum1=%number%',
				"shipping_country" => 'AT',
				"shipping_default" => '1',
				"display_in_order" => '0'
			),
				
			3 => array (
				"provider_name" => 'DHL at',
				"ts_slug" => 'dhl-at',
				"provider_url" => 'http://www.dhl.at/content/at/de/express/sendungsverfolgung.html?brand=DHL&AWB=%number%',
				"shipping_country" => 'AT',
				"shipping_default" => '1'
			),

			4 => array (
				"provider_name" => 'DPD.at',
				"ts_slug" => 'dpd-at',
				"provider_url" => 'https://tracking.dpd.de/parcelstatus?locale=de_AT&query=%number%',
				"shipping_country" => 'AT',
				"shipping_default" => '1'
			),

			5 => array (
				"provider_name" => 'Brazil Correios',
				"ts_slug" => 'brazil-correios',
				"provider_url" => 'http://websro.correios.com.br/sro_bin/txect01$.QueryList?P_LINGUA=001&P_TIPO=001&P_COD_UNI=%number%',
				"shipping_country" => 'BR',
				"shipping_default" => '1'
			),

			6 => array (
				"provider_name" => 'Belgium Post',
				"ts_slug" => 'belgium-post',
				"provider_url" => 'https://track.bpost.be/btr/web/#/search?itemCode=%number%',
				"shipping_country" => 'BE',
				"shipping_default" => '1'
			),

			7 => array (
				"provider_name" => 'Canada Post',
				"ts_slug" => 'canada-post',
				"provider_url" => 'http://www.canadapost.ca/cpotools/apps/track/personal/findByTrackNumber?trackingNumber=%number%',
				"shipping_country" => 'CA',
				"shipping_default" => '1'
			),
			
			8 => array (
				"provider_name" => 'DHL cz',
				"ts_slug" => 'dhl-cz',
				"provider_url" => 'http://www.dhl.cz/cs/express/sledovani_zasilek.html?AWB=%number%',
				"shipping_country" => 'CZ',
				"shipping_default" => '1'
			),
			
			9 => array (
				"provider_name" => 'DPD.cz',
				"ts_slug" => 'dpd-cz',
				"provider_url" => 'https://tracking.dpd.de/parcelstatus?locale=cs_CZ&query=%number%',
				"shipping_country" => 'CZ',
				"shipping_default" => '1'
			),

			10 => array (
				"provider_name" => 'Colissimo',
				"ts_slug" => 'colissimo',
				"provider_url" => 'https://www.laposte.fr/outils/suivre-vos-envois?code=%number%',
				"shipping_country" => 'FR',
				"shipping_default" => '1'
			),

			11 => array (
				"provider_name" => 'DHL Intraship (DE)',
				"ts_slug" => 'dhl-intraship-de',
				"provider_url" => 'http://nolp.dhl.de/nextt-online-public/set_identcodes.do?lang=de&idc=%number%&rfn=&extendedSearch=true',
				"shipping_country" => 'DE',
				"shipping_default" => '1'
			),

			12 => array (
				"provider_name" => 'Hermes Germany',
				"ts_slug" => 'hermes-de',
				"provider_url" => 'https://www.myhermes.de/empfangen/sendungsverfolgung/?suche=%number%',
				"shipping_country" => 'DE',
				"shipping_default" => '1'
			),

			13 => array (
				"provider_name" => 'Deutsche Post DHL',
				"ts_slug" => 'deutsche-post-dhl',
				"provider_url" => 'http://nolp.dhl.de/nextt-online-public/set_identcodes.do?lang=de&idc=%number%',
				"shipping_country" => 'DE',
				"shipping_default" => '1'
			),

			14 => array (
				"provider_name" => 'UPS Germany',
				"ts_slug" => 'ups-germany',
				"provider_url" => 'http://wwwapps.ups.com/WebTracking/processInputRequest?sort_by=status&tracknums_displayed=1&TypeOfInquiryNumber=T&loc=de_DE&InquiryNumber1=%number%',
				"shipping_country" => 'DE',
				"shipping_default" => '1'
			),

			15 => array (
				"provider_name" => 'DPD.de',
				"ts_slug" => 'dpd-de',
				"provider_url" => 'https://tracking.dpd.de/parcelstatus?query=%number%&locale=en_DE',
				"shipping_country" => 'DE',
				"shipping_default" => '1'
			),

			16 => array (
				"provider_name" => 'DPD.ie',
				"ts_slug" => 'dpd-ie',
				"provider_url" => 'http://www2.dpd.ie/Services/QuickTrack/tabid/222/ConsignmentID/%number%/Default.aspx',
				"shipping_country" => 'IE',
				"shipping_default" => '1'
			),

			17 => array (
				"provider_name" => 'DHL Express',
				"ts_slug" => 'dhl-express',
				"provider_url" => 'http://www.dhl.it/it/express/ricerca.html?AWB=%number%&brand=DHL',
				"shipping_country" => 'Global',
				"shipping_default" => '1'
			),

			18 => array (
				"provider_name" => 'PostNL',
				"ts_slug" => 'postnl',
				"provider_url" => 'https://mijnpakket.postnl.nl/Claim?Barcode=%number%&Postalcode=%2$s&Foreign=False&ShowAnonymousLayover=False&CustomerServiceClaim=False',
				"shipping_country" => 'NL',
				"shipping_default" => '1'
			),

			19 => array (
				"provider_name" => 'DPD.NL',
				"ts_slug" => 'dpd-nl',
				"provider_url" => 'http://track.dpdnl.nl/?parcelnumber=%number%',
				"shipping_country" => 'NL',
				"shipping_default" => '1'
			),

			20 => array (
				"provider_name" => 'Fastway NZ',
				"ts_slug" => 'fastway-nz',
				"provider_url" => 'https://www.fastway.co.nz/tools/track?l=%number%',
				"shipping_country" => 'NZ',
				"shipping_default" => '1'
			),

			21 => array (
				"provider_name" => 'DPD Romania',
				"ts_slug" => 'dpd-romania',
				"provider_url" => 'https://tracking.dpd.de/parcelstatus?query=%number%&locale=ro_RO',
				"shipping_country" => 'RO',
				"shipping_default" => '1'
			),

			22 => array (
				"provider_name" => 'PostNord Sverige AB',
				"ts_slug" => 'postnord-sverige-ab',
				"provider_url" => 'http://www.postnord.se/sv/verktyg/sok/Sidor/spara-brev-paket-och-pall.aspx?search=%number%',
				"shipping_country" => 'SE',
				"shipping_default" => '1'
			),

			23 => array (
				"provider_name" => 'DHL se',
				"ts_slug" => 'dhl-se',
				"provider_url" => 'http://www.dhl.se/content/se/sv/express/godssoekning.shtml?brand=DHL&AWB=%number%',
				"shipping_country" => 'SE',
				"shipping_default" => '1'
			),

			24 => array (
				"provider_name" => 'UPS.se',
				"ts_slug" => 'ups-se',
				"provider_url" => 'http://wwwapps.ups.com/WebTracking/track?track=yes&loc=sv_SE&trackNums=%number%',
				"shipping_country" => 'SE',
				"shipping_default" => '1'
			),

			25 => array (
				"provider_name" => 'DHL uk',
				"ts_slug" => 'dhl-uk',
				"provider_url" => 'http://www.dhl.com/content/g0/en/express/tracking.shtml?brand=DHL&AWB=%number%',
				"shipping_country" => 'GB',
				"shipping_default" => '1'
			),

			26 => array (
				"provider_name" => 'DPD.co.uk',
				"ts_slug" => 'dpd-co-uk',
				"provider_url" => 'http://www.dpd.co.uk/tracking/trackingSearch.do?search.searchType=0&search.parcelNumber=%number%',
				"shipping_country" => 'GB',
				"shipping_default" => '1'
			),

			27 => array (
				"provider_name" => 'InterLink',
				"ts_slug" => 'interlink',
				"provider_url" => 'http://www.interlinkexpress.com/apps/tracking/?reference=%number%&postcode=%2$s#results',
				"shipping_country" => 'GB',
				"shipping_default" => '1'
			),

			28 => array (
				"provider_name" => 'ParcelForce',
				"ts_slug" => 'parcelforce',
				"provider_url" => 'http://www.parcelforce.com/portal/pw/track?trackNumber=%number%',
				"shipping_country" => 'GB',
				"shipping_default" => '1'
			),

			29 => array (
				"provider_name" => 'Royal Mail',
				"ts_slug" => 'royal-mail',
				"provider_url" => 'https://www.royalmail.com/track-your-item/?trackNumber=%number%',
				"shipping_country" => 'GB',
				"shipping_default" => '1'
			),

			30 => array (
				"provider_name" => 'Fedex',
				"ts_slug" => 'fedex',
				"provider_url" => 'http://www.fedex.com/Tracking?action=track&tracknumbers=%number%',
				"shipping_country" => 'US',
				"shipping_default" => '1'
			),

			31 => array (
				"provider_name" => 'FedEx Sameday',
				"ts_slug" => 'fedex-sameday',
				"provider_url" => 'https://www.fedexsameday.com/fdx_dotracking_ua.aspx?tracknum=%number%',
				"shipping_country" => 'US',
				"shipping_default" => '1'
			),

			32 => array (
				"provider_name" => 'OnTrac',
				"ts_slug" => 'ontrac',
				"provider_url" => 'http://www.ontrac.com/trackingdetail.asp?tracking=%number%',
				"shipping_country" => 'US',
				"shipping_default" => '1'
			),

			33 => array (
				"provider_name" => 'UPS',
				"ts_slug" => 'ups',
				"provider_url" => 'http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=%number%',
				"shipping_country" => 'US',
				"shipping_default" => '1'
			),

			34 => array (
				"provider_name" => 'USPS',
				"ts_slug" => 'usps',
				"provider_url" => 'https://tools.usps.com/go/TrackConfirmAction_input?qtc_tLabels1=%number%',
				"shipping_country" => 'US',
				"shipping_default" => '1'
			),

			35 => array (
				"provider_name" => 'DHL US',
				"ts_slug" => 'dhl-us',
				"provider_url" => 'https://www.logistics.dhl/us-en/home/tracking/tracking-ecommerce.html?tracking-id=%number%',
				"shipping_country" => 'US',
				"shipping_default" => '1'
			),

			36 => array (
				"provider_name" => 'LaserShip',
				"ts_slug" => 'lasership',
				"provider_url" => 'https://www.lasership.com/track.php?track_number_input=%number%',
				"shipping_country" => 'US',
				"shipping_default" => '1'
			),
			37 => array (
				"provider_name" => 'GSO',
				"ts_slug" => 'gso',
				"provider_url" => 'https://www.gso.com/tracking',
				"shipping_country" => 'US',
				"shipping_default" => '1'
			),
			38 => array (
				"provider_name" => 'ABF',
				"ts_slug" => 'abf',
				"provider_url" => 'https://arcb.com/tools/tracking.html',
				"shipping_country" => 'IN',
				"shipping_default" => '1'
			),
			39 => array (
				"provider_name" => 'Associated Global Systems',
				"ts_slug" => 'associated-global-systems',
				"provider_url" => 'https://tracking.agsystems.com/',
				"shipping_country" => 'US',
				"shipping_default" => '1'
			),
			40 => array (
				"provider_name" => 'APC',
				"ts_slug" => 'apc',
				"provider_url" => 'https://us.mytracking.net/APC/track/TrackDetails.aspx?t=%number%',
				"shipping_country" => 'US',
				"shipping_default" => '1'
			),
			41 => array (
				"provider_name" => 'ArrowXL',
				"ts_slug" => 'arrowxl',
				"provider_url" => 'https://askaxl.co.uk/tracking?upi=%number%',
				"shipping_country" => 'GB',
				"shipping_default" => '1'
			),
			42 => array (
				"provider_name" => 'Dai Post',
				"ts_slug" => 'dai-post',
				"provider_url" => 'https://daiglobaltrack.com/tracking.aspx?custtracknbr=%number%',
				"shipping_country" => 'AU',
				"shipping_default" => '1'
			),
			43 => array (
				"provider_name" => 'Deliv',
				"ts_slug" => 'deliv',
				"provider_url" => 'https://tracking.deliv.co/',
				"shipping_country" => 'US',
				"shipping_default" => '1'
			),
			44 => array (
				"provider_name" => 'India Post',
				"ts_slug" => 'india-post',
				"provider_url" => 'https://www.indiapost.gov.in/_layouts/15/dop.portal.tracking/trackconsignment.aspx',
				"shipping_country" => 'IN',
				"shipping_default" => '1'
			),
			45 => array (
				"provider_name" => 'Israel Post',
				"ts_slug" => 'israel-post',
				"provider_url" => 'https://mypost.israelpost.co.il/itemtrace?itemcode=%number%',
				"shipping_country" => 'IL',
				"shipping_default" => '1'
			),
			46 => array (
				"provider_name" => 'Boxberry',
				"ts_slug" => 'boxberry',
				"provider_url" => 'https://boxberry.ru/tracking/',
				"shipping_country" => 'RU',
				"shipping_default" => '1'
			),
			47 => array (
				"provider_name" => 'Canpar',
				"ts_slug" => 'canpar',
				"provider_url" => 'https://www.canpar.ca/en/track/tracking.jsp',
				"shipping_country" => 'CA',
				"shipping_default" => '1'
			),
			48 => array (
				"provider_name" => 'China Post',
				"ts_slug" => 'china-post',
				"provider_url" => 'http://parcelsapp.com/en/tracking/%number%',
				"shipping_country" => 'CN',
				"shipping_default" => '1'
			),
			49 => array (
				"provider_name" => 'Chronopost',
				"ts_slug" => 'chronopost',
				"provider_url" => 'https://www.chronopost.fr/fr/chrono_suivi_search?listeNumerosLT=%number%',
				"shipping_country" => 'FR',
				"shipping_default" => '1'
			),
			50 => array (
				"provider_name" => 'Colis Privé',
				"ts_slug" => 'colis-prive',
				"provider_url" => 'https://www.colisprive.fr/',
				"shipping_country" => 'FR',
				"shipping_default" => '1'
			),
			51 => array (
				"provider_name" => 'Correos Chile',
				"ts_slug" => 'correos-chile',
				"provider_url" => 'https://seguimientoenvio.correos.cl/home/index/%number%',
				"shipping_country" => 'CL',
				"shipping_default" => '1'
			),
			52 => array (
				"provider_name" => 'Correos Costa Rica',
				"ts_slug" => 'correos-costa-rica',
				"provider_url" => 'https://www.correos.go.cr/rastreo/consulta_envios/rastreo.aspx',
				"shipping_country" => 'CR',
				"shipping_default" => '1'
			),
			53 => array (
				"provider_name" => 'CouriersPlease',
				"ts_slug" => 'couriersplease',
				"provider_url" => 'https://www.couriersplease.com.au/tools-track/no/%number%',
				"shipping_country" => 'AU',
				"shipping_default" => '1'
			),
			54 => array (
				"provider_name" => 'Delhivery',
				"ts_slug" => 'delhivery',
				"provider_url" => 'https://www.delhivery.com/track/package/%number%',
				"shipping_country" => 'IN',
				"shipping_default" => '1'
			),
			55 => array (
				"provider_name" => 'Deutsche Post',
				"ts_slug" => 'deutsche-post',
				"provider_url" => 'https://www.deutschepost.de/sendung/simpleQuery.html',
				"shipping_country" => 'DE',
				"shipping_default" => '1'
			),
			56 => array (
				"provider_name" => 'Direct Link',
				"ts_slug" => 'direct-link',
				"provider_url" => 'https://tracking.directlink.com/?itemNumber=%number%',
				"shipping_country" => 'DE',
				"shipping_default" => '1'
			),
			57 => array (
				"provider_name" => 'EC Firstclass',
				"ts_slug" => 'ec-firstclass',
				"provider_url" => 'http://www.ec-firstclass.org/Details.aspx',
				"shipping_country" => 'US',
				"shipping_default" => '1'
			),
			58 => array (
				"provider_name" => 'Ecom Express',
				"ts_slug" => 'ecom-express',
				"provider_url" => 'https://ecomexpress.in/tracking/?tflag=0&awb_field=%number%',
				"shipping_country" => 'IN',
				"shipping_default" => '1'
			),
			59 => array (
				"provider_name" => 'EMS',
				"ts_slug" => 'ems',
				"provider_url" => 'https://www.ems.post/en/global-network/tracking',
				"shipping_country" => 'CN',
				"shipping_default" => '1'
			),
			60 => array (
				"provider_name" => 'Hong Kong Post',
				"ts_slug" => 'hong-kong-post',
				"provider_url" => 'https://www.hongkongpost.hk/en/mail_tracking/index.html',
				"shipping_country" => 'HK',
				"shipping_default" => '1'
			),
			61 => array (
				"provider_name" => 'JP Post',
				"ts_slug" => 'jp-post',
				"provider_url" => 'https://trackings.post.japanpost.jp/services/srv/sequenceNoSearch/?requestNo=%number%&count=100&sequenceNoSearch.x=94&sequenceNoSearch.y=10&locale=en',
				"shipping_country" => 'JP',
				"shipping_default" => '1'
			),	
			62 => array (
				"provider_name" => 'La Poste',
				"ts_slug" => 'la-poste',
				"provider_url" => 'https://www.laposte.fr/particulier/outils/en/track-a-parcel',
				"shipping_country" => 'FR',
				"shipping_default" => '1'
			),
			63 => array (
				"provider_name" => 'Latvijas Pasts',
				"ts_slug" => 'latvijas-pasts',
				"provider_url" => 'https://www.pasts.lv/en/Category/Tracking_of_Postal_Items/',
				"shipping_country" => 'LV',
				"shipping_default" => '1'
			),
			64 => array (
				"provider_name" => 'Ninja Van',
				"ts_slug" => 'ninja-van',
				"provider_url" => 'https://www.ninjavan.co/en-sg/?tracking_id=%number%',
				"shipping_country" => 'SG',
				"shipping_default" => '1'
			),
			65 => array (
				"provider_name" => 'Singapore Post',
				"ts_slug" => 'singapore-post',
				"provider_url" => 'https://www.singpost.com/track-items',
				"shipping_country" => 'SG',
				"shipping_default" => '1'
			),
			66 => array (
				"provider_name" => 'StarTrack',
				"ts_slug" => 'startrack',
				"provider_url" => 'https://sttrackandtrace.startrack.com.au/%number%',
				"shipping_country" => 'AU',
				"shipping_default" => '1'
			),
			67 => array (
				"provider_name" => 'Ukrposhta',
				"ts_slug" => 'ukrposhta',
				"provider_url" => 'http://ukrposhta.ua/en/vidslidkuvati-forma-poshuku',
				"shipping_country" => 'UA',
				"shipping_default" => '1'
			),
			68 => array (
				"provider_name" => 'UPS i-parcel',
				"ts_slug" => 'ups-i-parcel',
				"provider_url" => 'https://tracking.i-parcel.com/?TrackingNumber=%number%',
				"shipping_country" => 'US',
				"shipping_default" => '1'
			),	
			69 => array (
				"provider_name" => 'DTDC',
				"ts_slug" => 'dtdc',
				"provider_url" => 'http://www.dtdc.in/tracking/tracking_results.asp?Ttype=awb_no&strCnno=%number%&TrkType2=awb_no',
				"shipping_country" => 'IN',
				"shipping_default" => '1'
			),	
			70 => array (
				"provider_name" => 'DHL Parcel',
				"ts_slug" => 'dhl-parcel',
				"provider_url" => 'https://www.logistics.dhl/us-en/home/tracking/tracking-ecommerce.html?tracking-id=%number%',
				"shipping_country" => 'US',
				"shipping_default" => '1'
			),
			71 => array (
				"provider_name" => 'An Post',
				"ts_slug" => 'an-post',
				"provider_url" => 'https://www.anpost.com/Post-Parcels/Track/History?item=%number%',
				"shipping_country" => 'IE',
				"shipping_default" => '1'
			),	
			72 => array (
				"provider_name" => 'Mondial Relay',
				"ts_slug" => 'mondial-relay',
				"provider_url" => 'https://www.mondialrelay.fr/suivi-de-colis?numeroExpedition=%number%',
				"shipping_country" => 'FR',
				"shipping_default" => '1'
			),
			73 => array (
				"provider_name" => 'Swiss Post',
				"ts_slug" => 'swiss-post',
				"provider_url" => 'https://service.post.ch/EasyTrack/submitParcelData.do?p_language=en&formattedParcelCodes=%number%',
				"shipping_country" => 'CH',
				"shipping_default" => '1'
			),
			74 => array (
				"provider_name" => 'S.F Express',
				"ts_slug" => 's-f-express',
				"provider_url" => 'http://www.sf-express.com/cn/en/dynamic_function/waybill/#search/bill-number/%number%',
				"shipping_country" => 'CN',
				"shipping_default" => '1'
			),
			75 => array (
				"provider_name" => 'ePacket',
				"ts_slug" => 'epacket',
				"provider_url" => 'http://www.ems.com.cn/english.html',
				"shipping_country" => 'CN',
				"shipping_default" => '1'
			),
			76 => array (
				"provider_name" => 'DTDC Plus',
				"ts_slug" => 'dtdc-plus',
				"provider_url" => 'http://www.dtdc.in/tracking/tracking_results.asp?Ttype=awb_no&strCnno=&TrkType2=awb_no',
				"shipping_country" => 'IN',
				"shipping_default" => '1'
			),	
			77 => array (
				"provider_name" => 'DHLParcel NL',
				"ts_slug" => 'dhlparcel-nl',
				"provider_url" => 'https://www.logistics.dhl/nl-en/home/tracking/tracking-parcel.html?tracking-id=%number%',
				"shipping_country" => 'NL',
				"shipping_default" => '1'
			),
			78 => array (
				"provider_name" => 'TNT',
				"ts_slug" => 'tnt',
				"provider_url" => 'https://www.tnt.com/?searchType=con&cons=%number%',
				"shipping_country" => 'US',
				"shipping_default" => '1'
			),
			79 => array (
				"provider_name" => 'Australia EMS',
				"ts_slug" => 'australia-ems',
				"provider_url" => 'https://auspost.com.au/mypost/track/#/details/%number%',
				"shipping_country" => 'AU',
				"shipping_default" => '1'
			),
			80 => array (
				"provider_name" => 'Bangladesh EMS',
				"ts_slug" => 'bangladesh-ems',
				"provider_url" => 'http://www.bangladeshpost.gov.bd/tracking.html',
				"shipping_country" => 'BD',
				"shipping_default" => '1'
			),
			81 => array (
				"provider_name" => 'Colombia Post',
				"ts_slug" => 'colombia-post',
				"provider_url" => 'http://www.4-72.com.co/',
				"shipping_country" => 'CO',
				"shipping_default" => '1'
			),
			82 => array (
				"provider_name" => 'Costa Rica Post',
				"ts_slug" => 'costa-rica-post',
				"provider_url" => 'https://www.correos.go.cr/rastreo/consulta_envios/',
				"shipping_country" => 'CR',
				"shipping_default" => '1'
			),
			83 => array (
				"provider_name" => 'Croatia Post',
				"ts_slug" => 'croatia-post',
				"provider_url" => 'https://www.posta.hr/tracktrace.aspx?broj=%number%',
				"shipping_country" => 'HR',
				"shipping_default" => '1'
			),
			84 => array (
				"provider_name" => 'Cyprus Post',
				"ts_slug" => 'cyprus-post',
				"provider_url" => 'https://www.cypruspost.post/en/track-n-trace-results?code=%number%',
				"shipping_country" => 'CY',
				"shipping_default" => '1'
			),
			85 => array (
				"provider_name" => 'Denmark Post',
				"ts_slug" => 'denmark-post',
				"provider_url" => 'https://www.postnord.dk/en/track-and-trace#dynamicloading=true&shipmentid=%number%',
				"shipping_country" => 'DK',
				"shipping_default" => '1'
			),
			86 => array (
				"provider_name" => 'Estonia Post',
				"ts_slug" => 'estonia-post',
				"provider_url" => 'https://www.omniva.ee/private/track_and_trace',
				"shipping_country" => 'EE',
				"shipping_default" => '1'
			),
			87 => array (
				"provider_name" => 'France EMS - Chronopost',
				"ts_slug" => 'france-ems-chronopost',
				"provider_url" => 'https://www.chronopost.fr/tracking-no-cms/suivi-page?listeNumerosLT=%number%',
				"shipping_country" => 'FR',
				"shipping_default" => '1'
			),
			88 => array (
				"provider_name" => 'Ivory Coast EMS',
				"ts_slug" => 'ivory-coast-ems',
				"provider_url" => 'https://laposte.ci.post/tracking-colis?identifiant=%number%',
				"shipping_country" => 'CI',
				"shipping_default" => '1'
			),
			89 => array (
				"provider_name" => 'Korea Post',
				"ts_slug" => 'korea-post',
				"provider_url" => 'https://service.epost.go.kr/trace.RetrieveEmsRigiTraceList.comm?ems_gubun=E&sid1=&POST_CODE=%number%',
				"shipping_country" => 'KR',
				"shipping_default" => '1'
			),
			90 => array (
				"provider_name" => 'Monaco EMS',
				"ts_slug" => 'monaco-ems',
				"provider_url" => 'http://www.lapostemonaco.mc',
				"shipping_country" => 'MC',
				"shipping_default" => '1'
			),				
			91 => array (
				"provider_name" => 'Overseas Territory FR EMS',
				"ts_slug" => 'overseas-territory-fr-ems',
				"provider_url" => 'https://www.chronopost.fr/tracking-no-cms/suivi-page?listeNumerosLT=%number%',
				"shipping_country" => 'FR',
				"shipping_default" => '1'
			),
			92 => array (
				"provider_name" => 'Portugal Post - CTT',
				"ts_slug" => 'portugal-post-ctt',
				"provider_url" => 'http://www.ctt.pt/feapl_2/app/open/objectSearch/objectSearch.jspx',
				"shipping_country" => 'PT',
				"shipping_default" => '1'
			),
			93 => array (
				"provider_name" => 'South African Post Office',
				"ts_slug" => 'south-african-post-office',
				"provider_url" => 'http://www.southafricanpostoffice.post/index.html',
				"shipping_country" => 'ZA',
				"shipping_default" => '1'
			),	
			94 => array (
				"provider_name" => 'Ukraine EMS',
				"ts_slug" => 'ukraine-ems',
				"provider_url" => 'http://dpsz.ua/en/track/ems',
				"shipping_country" => 'UA',
				"shipping_default" => '1'
			),
			95 => array (
				"provider_name" => 'TNT Italy',
				"ts_slug" => 'tnt-italy',
				"provider_url" => 'https://www.tnt.it/tracking/Tracking.do',
				"shipping_country" => 'IT',
				"shipping_default" => '1'
			),
			96 => array (
				"provider_name" => 'TNT France',
				"ts_slug" => 'tnt-france',
				"provider_url" => 'https://www.tnt.fr/public/suivi_colis/recherche/visubontransport.do',
				"shipping_country" => 'FR',
				"shipping_default" => '1'
			),				
			97 => array (
				"provider_name" => 'TNT UK',
				"ts_slug" => 'tnt-uk',
				"provider_url" => 'https://www.tnt.com/?searchType=con&cons=%number%',
				"shipping_country" => 'GB',
				"shipping_default" => '1'
			),
			98 => array (
				"provider_name" => 'Aliexpress Standard Shipping',
				"ts_slug" => 'aliexpress-standard-shipping',
				"provider_url" => 'https://global.cainiao.com/detail.htm?mailNoList=LP00139185155139',
				"shipping_country" => 'Global',
				"shipping_default" => '1'
			),
			99 => array (
				"provider_name" => 'Speedex Courier',
				"ts_slug" => 'speedex-courier',
				"provider_url" => 'http://www.speedexcourier.com/',
				"shipping_country" => 'US',
				"shipping_default" => '1'
			),
			100 => array (
				"provider_name" => 'TNT Reference',
				"ts_slug" => 'tnt-reference',
				"provider_url" => 'https://www.tnt.com/express/en_gb/site/shipping-tools/tracking.html?searchType=con&cons=%number%',
				"shipping_country" => 'GB',
				"shipping_default" => '1'
			),				
			101 => array (
				"provider_name" => 'TNT Click',
				"ts_slug" => 'tnt-click',
				"provider_url" => 'https://www.tnt-click.it/',
				"shipping_country" => 'IT',
				"shipping_default" => '1'
			),	
			102 => array (
				"provider_name" => 'TNT Australia',
				"ts_slug" => 'tnt-australia',
				"provider_url" => 'https://www.tnt.com/express/en_au/site/shipping-tools/tracking.html?respCountry=au&respLang=en&cons=%number%',
				"shipping_country" => 'AU',
				"shipping_default" => '1'
			),
			103 => array (
				"provider_name" => 'DHL Freight',
				"ts_slug" => 'dhl-freight',
				"provider_url" => 'https://www.logistics.dhl/global-en/home/tracking/tracking-freight.html?tracking-id=%number%',
				"shipping_country" => 'Global',
				"shipping_default" => '1'
			),
			104 => array (
				"provider_name" => 'Sendle',
				"ts_slug" => 'sendle',
				"provider_url" => 'https://track.sendle.com/tracking?ref=%number%',
				"shipping_country" => 'AU',
				"shipping_default" => '1'
			),
			105 => array (
				"provider_name" => 'Deppon',
				"ts_slug" => 'deppon',
				"provider_url" => 'https://www.deppon.com/en/toTrack.action',
				"shipping_country" => 'CN',
				"shipping_default" => '1',					
			),	
			106 => array (
				"provider_name" => 'GLS Italy',
				"ts_slug" => 'gls-italy',
				"provider_url" => 'https://www.gls-italy.com/?option=com_gls&view=track_e_trace&mode=search&numero_spedizione=%number%&tipo_codice=nazionale',
				"shipping_country" => 'IT',
				"shipping_default" => '1',					
			),
			107 => array (
				"provider_name" => 'Hermes World',
				"ts_slug" => 'hermes',
				"provider_url" => 'https://new.myhermes.co.uk/track.html#/parcel/%number%/details',
				"shipping_country" => 'Global',
				"shipping_default" => '1',					
			),
			108 => array (
				"provider_name" => 'TCS PK',
				"ts_slug" => 'tcs',
				"provider_url" => 'https://www.tcsexpress.com/tracking?id=%number%',
				"shipping_country" => 'PK',
				"shipping_default" => '1',					
			),	
			109 => array (
				"provider_name" => 'Leopard PK',
				"ts_slug" => 'leopard',
				"provider_url" => 'http://leopardscourier.com/pk/',
				"shipping_country" => 'PK',
				"shipping_default" => '1',					
			),
			110 => array (
				"provider_name" => 'Blue-Ex',
				"ts_slug" => 'blue-ex',
				"provider_url" => ' https://www.blue-ex.com/',
				"shipping_country" => 'PK',
				"shipping_default" => '1',					
			),
			111 => array (
				"provider_name" => 'Call Courier',
				"ts_slug" => 'call-courier',
				"provider_url" => 'https://callcourier.com.pk/tracking/?tc=%number%',
				"shipping_country" => 'PK',
				"shipping_default" => '1',					
			),				
			112 => array (
				"provider_name" => 'M&P',
				"ts_slug" => 'mp',
				"provider_url" => 'http://mulphilog.com/',
				"shipping_country" => 'PK',
				"shipping_default" => '1',					
			),
			113 => array (
				"provider_name" => 'Pakistan Post',
				"ts_slug" => 'pakistan-post',
				"provider_url" => 'http://www.pakpost.gov.pk/',
				"shipping_country" => 'PK',
				"shipping_default" => '1',					
			),
			114 => array (
				"provider_name" => 'Trax Logistics',
				"ts_slug" => 'trax-logistics',
				"provider_url" => 'https://sonic.pk/tracking?tracking_number=%number%',
				"shipping_country" => 'PK',
				"shipping_default" => '1',					
			),
			115 => array (
				"provider_name" => 'Move Express',
				"ts_slug" => 'movex-pk',
				"provider_url" => 'https://tracking.movexpk.com/',
				"shipping_country" => 'PK',
				"shipping_default" => '1',					
			),
			116 => array (
				"provider_name" => 'Store Pickup',
				"ts_slug" => 'store-pickup',
				"provider_url" => '#',
				"shipping_country" => 'PK',
				"shipping_default" => '1',					
			),
			117 => array (
				"provider_name" => 'Store Rider',
				"ts_slug" => 'store-rider',
				"provider_url" => '#',
				"shipping_country" => 'PK',
				"shipping_default" => '1',					
			),
			118 => array (
				"provider_name" => 'Swyft',
				"ts_slug" => 'swyft',
				"provider_url" => 'http://parceltracking.swyftlogistics.com/?%number%',
				"shipping_country" => 'PK',
				"shipping_default" => '1',					
			),
			119 => array (
				"provider_name" => 'Bykea',
				"ts_slug" => 'bykea',
				"provider_url" => 'http://bykea.com',
				"shipping_country" => 'PK',
				"shipping_default" => '1',					
			),
			120 => array (
				"provider_name" => 'Careem',
				"ts_slug" => 'careem',
				"provider_url" => 'http://careem.com',
				"shipping_country" => 'PK',
				"shipping_default" => '1',					
			),
			121 => array (
				"provider_name" => 'DHL',
				"ts_slug" => 'dhl-pk',
				"provider_url" => 'https://www.dhl.com/en/express/tracking.html?AWB=%number%&brand=DHL',
				"shipping_country" => 'PK',
				"shipping_default" => '1',					
			),
			122 => array (
				"provider_name" => 'Fedex',
				"ts_slug" => 'fedex-pk',
				"provider_url" => 'https://www.fedex.com/fedextrack/?tracknumbers=%number%&cntry_code=pk',
				"shipping_country" => 'PK',
				"shipping_default" => '1',					
			),
			123 => array (
				"provider_name" => '00-1',
				"ts_slug" => '00-1',
				"provider_url" => '#',
				"shipping_country" => 'ex',
				"shipping_default" => '0',					
			),
			124 => array (
				"provider_name" => '00-2',
				"ts_slug" => '00-2',
				"provider_url" => '#',
				"shipping_country" => 'ex',
				"shipping_default" => '0',					
			),
			125 => array (
				"provider_name" => '00-3',
				"ts_slug" => '00-3',
				"provider_url" => '#',
				"shipping_country" => 'ex',
				"shipping_default" => '0',					
			),
			126 => array (
				"provider_name" => '00-4',
				"ts_slug" => '00-4',
				"provider_url" => '#',
				"shipping_country" => 'ex',
				"shipping_default" => '0',					
			),
			127 => array (
				"provider_name" => '00-5',
				"ts_slug" => '00-5',
				"provider_url" => '#',
				"shipping_country" => 'ex',
				"shipping_default" => '0',					
			),
			128 => array (
				"provider_name" => '00-6',
				"ts_slug" => '00-6',
				"provider_url" => '#',
				"shipping_country" => 'ex',
				"shipping_default" => '0',					
			),
			129 => array (
				"provider_name" => '00-7',
				"ts_slug" => '00-7',
				"provider_url" => '#',
				"shipping_country" => 'ex',
				"shipping_default" => '0',					
			),
			130 => array (
				"provider_name" => '00-8',
				"ts_slug" => '00-8',
				"provider_url" => '#',
				"shipping_country" => 'ex',
				"shipping_default" => '0',					
			),

		);		
		return $providers;
	}	


}