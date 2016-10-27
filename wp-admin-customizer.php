<?php
/**
 * Plugin Name: WP Admin Customizer
 * Plugin URI: http://www.wwgate.net
 * Description: This plugin give you some options to customize WordPress Admin Panel to make it your own.
 * Version: 1.0.0
 * Author: Fadi Yousef
 * Author http://www.wwgate.net
 * License: GPL2
 */

if ( !class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/ReduxFramework/ReduxCore/framework.php' ) ) {
    require_once( dirname( __FILE__ ) . '/ReduxFramework/ReduxCore/framework.php' );
}
if ( !isset( $redux_demo ) && file_exists( dirname( __FILE__ ) . '/ReduxFramework/barebones-config.php' ) ) {
    require_once( dirname( __FILE__ ) . '/ReduxFramework/barebones-config.php' );
}

//change login page logo
function wp_admin_customizer_login_page_style() {
	global $wp_admin_customizer_options;
	$style = '<style type="text/css">';
	if($wp_admin_customizer_options['login-page-logo']['url']){
		$style .= 'h1 a {
			background-image: url('.$wp_admin_customizer_options['login-page-logo']['url'].') !important;
			height: '.$wp_admin_customizer_options['login-page-logo']['height'].'px !important;
			width: '.$wp_admin_customizer_options['login-page-logo']['width'].'px !important;
			background-size: '. $wp_admin_customizer_options['login-page-logo']['width'] .'px !important;
			max-width:320px !important;
		}';
	}
	$style .= 'body.login{
		background-color:'.$wp_admin_customizer_options['login-page-background-color'].' !important;
	}
	.login #backtoblog a, .login #nav a{
		color:'.$wp_admin_customizer_options['login-page-links-color'].' !important;
	}
	.login #backtoblog a:hover, .login #nav a:hover{
		color:'.$wp_admin_customizer_options['login-page-links-hover-color'].' !important;
	}
	.login form{
		background-color:'.$wp_admin_customizer_options['login-page-form-background-color'].' !important;
	}
	.login form label{
		color:'.$wp_admin_customizer_options['login-page-form-label-color'].' !important;
	}
	</style>';
	echo $style;
}
add_action('login_head', 'wp_admin_customizer_login_page_style');


//remove wordpress logo from admin bar
function wp_admin_customizer_remove_admin_bar_logo() {
	global $wp_admin_bar;
	global $wp_admin_customizer_options;
	//Remove the WordPress logo...
	if($wp_admin_customizer_options['admin-bar-remove-wp-logo']){
	
		$wp_admin_bar->remove_menu('wp-logo');
	}

	//remove comment from admin bar
	if($wp_admin_customizer_options['admin-bar-remove-comment-icon']){
		$wp_admin_bar->remove_menu('comments');
	}
}
add_action( 'wp_before_admin_bar_render', 'wp_admin_customizer_remove_admin_bar_logo' ); 



//change howdy text in admin bar
add_action( 'admin_bar_menu', 'wp_admin_bar_my_custom_account_menu', 11 );

function wp_admin_bar_my_custom_account_menu( $wp_admin_bar ) {
	global $wp_admin_customizer_options;
	$user_id = get_current_user_id();
	$current_user = wp_get_current_user();
	$profile_url = get_edit_profile_url( $user_id );

	if ( 0 != $user_id ) {
		/* Add the "My Account" menu */
		$avatar = get_avatar( $user_id, 28 );
		$howdy = sprintf( __($wp_admin_customizer_options['admin-bar-howdy-text'].' %1$s'), $current_user->display_name );
		$class = empty( $avatar ) ? '' : 'with-avatar';

		$wp_admin_bar->add_menu( array(
			'id' => 'my-account',
			'parent' => 'top-secondary',
			'title' => $howdy . $avatar,
			'href' => $profile_url,
			'meta' => array(
			'class' => $class,
			),
		) );

	}
}


//remove wordpress version from the footer
function wp_admin_customizer_remove_wp_version() {
	global $wp_admin_customizer_options;
	if($wp_admin_customizer_options['admin-footer-remove-wp-version']){
		remove_filter( 'update_footer', 'core_update_footer' ); 
	}
    
}

add_action( 'admin_menu', 'wp_admin_customizer_remove_wp_version' );



// remove thank you for creating with wordpress from footer


function wp_admin_customizer_remove_footer_text($text){
	global $wp_admin_customizer_options;
	if($wp_admin_customizer_options['admin-footer-remove-thankyou-text']){
		return false;
	}else{
		return $text;
	}
}
add_filter( 'admin_footer_text', 'wp_admin_customizer_remove_footer_text',100 );