<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class ELHP_Google_Button {
    
    public function __construct() {
        // Add button under login form
        add_filter( 'hivepress/v1/templates/user_login_form', [ $this, 'inject_button' ], 20 );
        // Add button under register form
        add_filter( 'hivepress/v1/templates/user_register_form', [ $this, 'inject_button' ], 20 );
        // Login page template
        add_filter( 'hivepress/v1/templates/page_user_login', [ $this, 'inject_button_page' ], 20 );
        // Register page template
        add_filter( 'hivepress/v1/templates/page_user_register', [ $this, 'inject_button_page' ], 20 );
        
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        
        // Debug hook
        add_action( 'wp_footer', [ $this, 'debug_info' ], 999 );
    }
    
    public function enqueue_assets() {
        // FIXED: Correct CSS path
        wp_enqueue_style( 'elhp-style', ELHP_URL . 'assets/css/google-button.css', [], '1.0.0' );
    }
    
    /**
     * Get button HTML using template file
     */
    private function get_button_html() {
        $client_id = elhp_get_opt('google_client_id');
        
        // Don't show button if not configured
        if ( empty( $client_id ) ) {
            elhp_log( 'Google button not shown: Client ID not configured' );
            return '';
        }
        
        $google_url = site_url( '/?elhp_google_login=1' );
        
        // Start output buffering
        ob_start();
        
        // Add separator
        echo '<div class="elhp-separator" style="text-align: center; margin: 15px 0; color: #999;">— OR —</div>';
        
        // Include template file
        include ELHP_PATH . 'templates/google-button.php';
        
        elhp_log( 'Google button HTML generated from template' );
        
        return ob_get_clean();
    }
    
    /* Inject into form templates (modal + blocks) */
    public function inject_button( $template ) {
        $button_html = $this->get_button_html();
        
        if ( empty( $button_html ) ) {
            return $template;
        }
        
        $template['blocks']['elhp_google_button'] = [
            'type'     => 'html',
            'content'  => $button_html,
            'priority' => 200,
        ];
        
        elhp_log( 'Google button injected into form template' );
        
        return $template;
    }
    
    /* Inject into page templates (/account/login & /account/register) */
    public function inject_button_page( $template ) {
        $button_html = $this->get_button_html();
        
        if ( empty( $button_html ) ) {
            return $template;
        }
        
        $template['blocks']['elhp_google_button_page'] = [
            'type'     => 'html',
            'content'  => '<div style="margin-top:20px;">' . $button_html . '</div>',
            'priority' => 200,
        ];
        
        elhp_log( 'Google button injected into page template' );
        
        return $template;
    }
    
    /* Debug info for troubleshooting */
    public function debug_info() {
        if ( ! defined('WP_DEBUG') || ! WP_DEBUG || ! current_user_can('manage_options') ) {
            return;
        }
        
        $client_id = elhp_get_opt('google_client_id');
        ?>
        <!-- ExtraLogin HP Debug -->
        <script>
        console.log('=== ExtraLogin HP Debug ===');
        console.log('Client ID configured:', <?php echo $client_id ? 'true' : 'false'; ?>);
        console.log('CSS loaded:', document.querySelector('link[href*="google-button.css"]') !== null);
        console.log('Button container found:', document.querySelector('.elhp-google-wrap') !== null);
        console.log('Button element found:', document.querySelector('.elhp-google-btn') !== null);
        if (document.querySelector('.elhp-google-btn')) {
            console.log('Button href:', document.querySelector('.elhp-google-btn').href);
        }
        </script>
        <?php
    }
}

new ELHP_Google_Button();
