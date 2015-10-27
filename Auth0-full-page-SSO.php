<?php
/**
* Plugin Name: Auth0 full page SSO
* Plugin URI: https://github.com/AllFestivals/Auth0-full-page-SSO
* Description: Getting namespace and api key from conf of Aut0 plugin. If user is not logged, will add javascript with SSO determine to header. If this one get response with exist SSO, this will redirect of user to login.
* Version: 1.0.0
* Author: marek
* License: GPL2
*/

add_action('init', 'ajax_auth_init');
function ajax_auth_init() {
	if (is_user_logged_in()) return;

	add_action('wp_head', 'sso_determine_script_to_head');
	function sso_determine_script_to_head() {
		$options = get_option('wp_auth0_settings');
		$client_id = $options[client_id];
		$domain = $options[domain];
		$cdn = $options[cdn_url];
		?>
		<script id="auth0" src=" <?php echo $cdn; ?> "></script>
		<script type='text/javascript'>
			document.addEventListener("DOMContentLoaded", function(event) {
				var lock = new Auth0Lock('<?php echo $client_id; ?>', '<?php echo $domain; ?>');
				lock.$auth0.getSSOData(function(err, data) {
					if (!err && data.sso) {
						<?php echo 'window.location = "' . wp_login_url( get_site_url() . $_SERVER['REQUEST_URI'] ) . '&reauth=1"'; ?>
					}
				});
			});
		</script>
		<?php
	}
}
