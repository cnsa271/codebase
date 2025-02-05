<?php 
if ( ! defined( 'ABSPATH' ) ) {
    die( 'You are not allowed to call this page directly.' );
}

/**
 * School District API Functions
 * @package SD58
 * @version 1.0.0
 */
class SDFormAPIFrontend
{ 
    // SOAP Environment
    private $environment;

    // SOAP END POINT
    private $soap_endpoint;
    
    // Business Partner ID
    private $BusinessPartnerID;

    // Business Partner Type
    private $BusinessPartnerType;

    // Business Partner Token
    private $BusinessPartnerToken;  

    // IP Address
    private $IpAddress;

    /**
     * Autoload method
     * @return void
     */
    public function __construct() {    
        // Hook to enqueue the JavaScript to the page
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) ); 
        add_action( 'init', array( $this, 'initializeAPIValues' ) );
        add_action( 'wp_ajax_retrieve_inc_status', array( $this, 'retrieve_inc_status' ) ); 
        add_action( 'wp_ajax_nopriv_retrieve_inc_status', array( $this, 'retrieve_inc_status' ) );
    }

    /*
     * Enqueue Frontend JS
     */
    public function enqueue_scripts() {
        wp_enqueue_script( 'form_api_js', get_template_directory_uri() . '/assets/js/form-api.js', array( 'jquery' ), '1.2', true ); 
        wp_localize_script( 'api_ajax_js', 'frontend_ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) ); 
    }

    /*
     * Initializing the API Values
     */
    public function initializeAPIValues() {
        // Use get_field() after WordPress is fully initialized
        $this->environment = get_field( 'choose_the_environment', 'option' );

        if ( $this->environment === 'Production' ) {
            $this->soap_endpoint      = get_field( 'pro_api_end_point_url', 'option' );
            $this->BusinessPartnerID   = get_field( 'pro_biz_partner_id', 'option' );
            $this->BusinessPartnerType = get_field( 'pro_biz_partner_type', 'option' );
            $this->BusinessPartnerToken = get_field( 'pro_biz_partner_token', 'option' );
            $this->IpAddress          = get_field( 'pro_ip_address', 'option' );
        } else {
            $this->soap_endpoint      = get_field( 'test_api_end_point_url', 'option' );
            $this->BusinessPartnerID   = get_field( 'test_biz_partner_id', 'option' );
            $this->BusinessPartnerType = get_field( 'test_biz_partner_type', 'option' );
            $this->BusinessPartnerToken = get_field( 'test_biz_partner_token', 'option' );
            $this->IpAddress          = get_field( 'test_ip_address', 'option' );
        }
    }

    /**
     * Retrieve Incident Status via API
     * @return response status
     * @access limited
     */
    public function retrieve_inc_status( $employerIdentifier ) {
        header( 'Content-Type: application/json' );

        if ( ! empty( $_REQUEST['employerIdentifier'] ) ) {
            $employerIdentifier = sanitize_text_field( wp_unslash( $_REQUEST['employerIdentifier'] ) ); 

            $xml_data = '<?xml version="1.0" encoding="utf-8"?>
            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
                <soapenv:Header>
                    <wcbSecurityHeader xmlns="http://B2B.online.worksafebc.com/wsSecurity">
                        <BusinessPartnerID>' . $this->BusinessPartnerID . '</BusinessPartnerID>
                        <BusinessPartnerType>' . $this->BusinessPartnerType . '</BusinessPartnerType>
                        <BusinessPartnerToken>' . $this->BusinessPartnerToken . '</BusinessPartnerToken>
                    </wcbSecurityHeader>
                </soapenv:Header>
                <soapenv:Body>
                    <SubmissionStatus xmlns="http://localhost/ISSV3">
                        &lt;?xml version="1.0" encoding="UTF-8"?&gt;
                        &lt;SubmissionStatus xmlns="http://B2B.online.worksafebc.com/ISSV3" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" Version="1.0"&gt;
                            &lt;TransactionIdentifier&gt;' . $employerIdentifier . '&lt;/TransactionIdentifier&gt;
                        &lt;/SubmissionStatus&gt;
                    </SubmissionStatus>
                </soapenv:Body>
            </soapenv:Envelope>';          

            // Define SOAP headers
            $headers = array(
                'Content-Type: text/xml; charset=utf-8',
                'SOAPAction: "RetrieveIncidentStatus"',
            );       

            // Initialize cURL session
            $ch = curl_init( $this->soap_endpoint );

            // Set cURL options
            curl_setopt( $ch, CURLOPT_POST, true );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $xml_data );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

            // Execute cURL session
            $response = curl_exec( $ch );

            // Check for errors
            if ( $response === false ) {
                $error = curl_error( $ch );
                $api_msg['msg'] = $error;
            } else {
                $api_msg['msg'] = "success";

                // Load XML string
                $xml = simplexml_load_string( $response );

                // Register SOAP namespace
                $xml->registerXPathNamespace( 'soap', 'http://schemas.xmlsoap.org/soap/envelope/' );

                // Extract SOAP body content
                $bodyContent = $xml->xpath( '//soap:Body' )[0];

                // Extract inner XML from escaped content
                $innerXmlString = html_entity_decode( (string) $bodyContent->SubmissionStatusResponse );

                // Load inner XML as new SimpleXMLElement
                $innerXml = simplexml_load_string( $innerXmlString );

                // Extract fields and their values
                $fields = [];
                foreach ( $innerXml->Status->children() as $child ) {
                    $fieldName  = $child->getName();
                    $fieldValue = (string) $child;
                    $fields[$fieldName] = $fieldValue;
                }

                $msgBody = '<h3 class="cahead">Retrieve Incident Status</h3><div class="form_scroll doc_form_scroll"><div class="scrollbar-div" id="my-scrollbar"><div class="scroll-content"><div class="status_info">';
                
                // Print field names and values
                foreach ( $fields as $fieldName => $fieldValue ) {
                    $msgBody .= '<div class="status_row"><div class="status_column left">' . $fieldName . '</div><div class="status_column right">' . $fieldValue . '</div></div>';
                }

                $msgBody .= '</div></div></div></div>';
                $api_msg['msg_html'] = $msgBody;
            }

            // Close cURL session
            curl_close( $ch );
        }

        echo json_encode( $api_msg );
        exit();
    }
}

$api_frontend = new SDFormAPIFrontend();