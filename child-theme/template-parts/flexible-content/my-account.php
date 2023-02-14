<?php
if ( ! empty( $_GET['logout'] ) || ! is_user_logged_in() ) {
	if ( ! empty( $_GET['logout'] ) ) {
		wp_logout();
	}

	wp_redirect( '/' );
}

$current_user        = wp_get_current_user();
$my_account          = [];
$my_account['login'] = $current_user->user_firstname ? $current_user->user_firstname : $current_user->user_email;
$my_account['first'] = $current_user->user_firstname ? 'First name: <strong>' . $current_user->user_firstname . '</strong>' : '';
$my_account['last']  = $current_user->user_lastname ? 'Last name:: <strong>' . $current_user->user_lastname . '</strong>' : '';
$my_account['email'] = $current_user->user_email ? 'Email: <strong>' . $current_user->user_email . '</strong>' : '';
$my_account          = json_encode( $my_account );
?>
<script>
	const my_account = JSON.parse(' <?php echo $my_account; ?>')
</script>
<div id="vue_my_account"></div>

