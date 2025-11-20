<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'admin_menu', function() {
    add_options_page(
        'ExtraLogin for HP',
        'ExtraLogin for HP',
        'manage_options',
        'elhp-settings',
        'elhp_settings_page'
    );
});

function elhp_settings_page() {
    if ( ! current_user_can('manage_options') ) return;

    if ( isset($_POST['elhp_save']) ) {
        check_admin_referer('elhp_save_action','elhp_save_nonce');
        elhp_set_opt( 'google_client_id', sanitize_text_field($_POST['google_client_id'] ?? '') );
        elhp_set_opt( 'google_client_secret', sanitize_text_field($_POST['google_client_secret'] ?? '') );
        echo '<div class="updated"><p>Settings saved.</p></div>';
    }

    $client_id = esc_attr( elhp_get_opt('google_client_id') );
    $client_secret = esc_attr( elhp_get_opt('google_client_secret') );
    $redirect_uri = esc_url_raw( add_query_arg( 'elhp_google_callback', '1', site_url('/') ) );
    ?>
    <div class="wrap">
        <h1>ExtraLogin for HP — Settings</h1>
        <form method="post">
            <?php wp_nonce_field('elhp_save_action','elhp_save_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Google Client ID</th>
                    <td><input type="text" name="google_client_id" value="<?php echo $client_id; ?>" style="width:420px;"></td>
                </tr>
                <tr>
                    <th scope="row">Google Client Secret</th>
                    <td><input type="text" name="google_client_secret" value="<?php echo $client_secret; ?>" style="width:420px;"></td>
                </tr>
                <tr>
                    <th scope="row">Redirect URI</th>
                    <td>
                        <code><?php echo esc_html( $redirect_uri ); ?></code><br>
                        Copy this exact URL to your Google Cloud Console "Authorized redirect URIs".
                    </td>
                </tr>
            </table>
            <p><input type="submit" name="elhp_save" class="button button-primary" value="Save"></p>
        </form>
        <h2>Notes</h2>
        <p>Make sure the Redirect URI above is added to your Google credentials. Use the Authorization Code flow (server-side).</p>
    </div>
    <?php
}
