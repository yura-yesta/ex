<?php

require WLD_Theme::$child_path . 'inc/remove-menu-from-posts.php';

WLD_My_Account::init();
WLD_Filter::init();
WLD_Sticky_Posts_In_Category::init();
WLD_Guide_Popup::init();
WLD_CPT::add(
	'guide',
	array(
		'supports' => array('title', 'revisions', 'editor', 'excerpt', 'thumbnail'),
		'public' => true,
	)
);

// Specify styles for .btn as in file styles.css.
// todo: Adjust the main color of the button.
WLD_TinyMCE::add_editor_styles('.btn', 'background-color:;color:#fff;');

// Specify styles for login page.
// todo: Customize the login to the admin panel according to the theme colors.
WLD_Login_Style::set('bg_color', '');
WLD_Login_Style::set('links_color', '');
WLD_Login_Style::set('btn_bg', '#ef454e');
WLD_Login_Style::set('btn_color', '');


// Add menus.
// todo: Delete unused menus.
WLD_Nav::add('Header Main');
WLD_Nav::add('Header Second');
WLD_Nav::add('Footer Main');
WLD_Nav::add('Footer Links');

// Add image sizes
// todo: Be sure to specify all the necessary dimensions, and sort them by increasing the width.
//WLD_Images::add_size( '500x0' );
WLD_Images::add_size('0x231');
WLD_Images::add_size('40x0');
WLD_Images::add_size('60x0');
WLD_Images::add_size('300x0');
WLD_Images::add_size('570x0');

// todo: If you have completed all the todos, delete them. :)

add_filter(
	'theme_get_styles_section_globs',
	static function (array $globs, string $row_layout): array {
		if ('filter' === $row_layout) {
			return array(
				WLD_Theme::$child_path . 'dist/css/styles-section-inner-page-banner-*.css',
				WLD_Theme::$child_path . 'dist/css/styles-section-data-*.css',
				WLD_Theme::$child_path . 'dist/css/styles-section-info-tabs-*.css',
				WLD_Theme::$child_path . 'dist/css/styles-section-search-page-*.css',
				WLD_Theme::$child_path . 'dist/css/styles-section-search-page-results-*.css',
			);
		}

		return $globs;
	},
	10,
	2
);

add_filter(
	'theme_mutable_flexible_layouts',
	static function (array $mutable_flexible_layouts): array {
		$mutable_flexible_layouts['my-account'] = true;

		return $mutable_flexible_layouts;
	}
);

/** Change menu if auth */
add_filter('nav_menu_css_class', 'hide_menu_field', 10, 1);
function hide_menu_field($classes)
{
	$menu_name = is_user_logged_in() ? 'sign-in' : 'my-account';
	foreach ($classes as $value) {
		if ($value === $menu_name) {
			$classes[] = 'hidden';
		}
	}

	return $classes;
}

/** routes need to change */

function my_update_cookie($logged_in_cookie)
{
	$_COOKIE[LOGGED_IN_COOKIE] = $logged_in_cookie;
}

add_action('set_logged_in_cookie', 'my_update_cookie');

// todo: If you have completed all the todos, delete them. :)

add_action(
	'wp_enqueue_scripts',
	static function () {
		WLD_Assets::enqueue_script('src/app-vue/dist/js/chunk-vendors.js', array('dom_ready' => true));
		WLD_Assets::enqueue_script('src/app-vue/dist/js/app.js', array('dom_ready' => true));
	}
);




