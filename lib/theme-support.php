<?php
add_action('after_setup_theme', 'theme_support');

function theme_support()
{

	/*********   GUTENBERG EDITOR  ************
	 *°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°*/

	/**
	 * Add support for custom color palettes in Gutenberg.
	 * @link https://wordpress.org/gutenberg/handbook/designers-developers/developers/themes/theme-support/
	 */
	// add_theme_support(
	// 	'editor-color-palette', array(
	// 		array( 'name' => 'primary', 'slug'  => 'primary', 'color' => '#C4161C' ),
	// 		array( 'name' => 'secondary', 'slug'  => 'secondary', 'color' => '#000000' ),
	// 		array( 'name' => 'white', 'slug'  => 'white', 'color' => '#FFFFFF' ),
	// 		array( 'name' => 'gris', 'slug'  => 'gris', 'color' => '#C4161C' ),
	// 		array( 'name' => 'vert', 'slug'  => 'vert', 'color' => '#009270' ),
	// 		array( 'name' => 'bleu', 'slug'  => 'bleu', 'color' => '#263683' ),
	// 		array( 'name' => 'jaune', 'slug'  => 'jaune', 'color' => '#D3AA2A' ),
	// 		array( 'name' => 'orange', 'slug'  => 'orange', 'color' => '#C96120' ),
	// 		array(
	// 			'name'  => 'Transparent' ,
	// 			'slug'  => 'transparent',
	// 			'color' => '#FF0000',
	// 			)
	// 	)
	// );

	/**
	 * Disable some Gutenberg functions.
	 * @link https://wordpress.org/gutenberg/handbook/designers-developers/developers/themes/theme-support/
	 */
	// add_theme_support( 'disable-custom-colors' );
	// add_theme_support('disable-custom-font-sizes');


	/************ THEMING ************
	 *________________________________*/

	/** Custom logo.*/
	// add_theme_support( 'custom-logo', array(
	// 	'height'      => 250,
	// 	'width'       => 250,
	// 	'flex-width'  => true,
	// 	'flex-height' => true,
	// ) );

	/** HTML 5
	 * Switch default core markup for search form, comment form, and comments to output valid HTML5.
	 */
	add_theme_support('html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	));


	// POST FORMATS
	// add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' ) );
	// add_theme_support( 'post-formats', array( 'audio', 'link') );


	/**  Complete list :
	 * @link https://developer.wordpress.org/reference/functions/add_theme_support/
	 */
	add_theme_support('align-wide');
	add_theme_support('automatic-feed-links');
	add_theme_support('core-block-patterns');
	// add_theme_support( 'custom-background');
	// add_theme_support( 'custom-header');
	// add_theme_support( 'custom-line-height');
	// add_theme_support( 'custom-logo');
	add_theme_support('customize-selective-refresh-widgets');
	// add_theme_support( 'custom-spacing' );
	add_theme_support('custom-units');
	// add_theme_support( 'dark-editor-style' );
	// add_theme_support( 'disable-custom-colors' );
	// add_theme_support( 'disable-custom-font-sizes' );
	// add_theme_support( 'editor-color-palette' );
	// add_theme_support( 'editor-gradient-presets' );
	// add_theme_support( 'editor-font-sizes' );
	// add_theme_support( 'editor-styles' );
	// add_theme_support( 'featured-content' );
	// add_theme_support( 'html5' );
	add_theme_support('menus');
	// add_theme_support( 'block-template-parts' );
	// add_theme_support( 'post-formats' );
	add_theme_support('post-thumbnails');
	add_theme_support('responsive-embeds');
	// add_theme_support( 'starter-content' );
	add_theme_support('title-tag');
	add_theme_support('wp-block-styles');
	add_theme_support('widgets');
	add_theme_support('widgets-block-editor');
	add_post_type_support('page', 'excerpt');
}



function register_my_menus() {
	register_nav_menus(
	  array(
		'header-menu' => __( 'Header Menu' ),
		'header-features' => __( 'Header Features' ),
		'footer-menu' => __( 'Footer Menu' )
	  )
	);
  }
  add_action( 'init', 'register_my_menus' );
  



// Désactiver les commentaires pour les articles
function disable_comments_post_types_support()
{
	$post_types = get_post_types();
	foreach ($post_types as $post_type) {
		if (post_type_supports($post_type, 'comments')) {
			remove_post_type_support($post_type, 'comments');
			remove_post_type_support($post_type, 'trackbacks');
		}
	}
}
add_action('admin_init', 'disable_comments_post_types_support');

// Cacher le menu des commentaires dans le tableau de bord
function hide_comments_menu()
{
	remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'hide_comments_menu');

// Désactiver l'affichage des commentaires dans l'interface d'administration
function disable_comments_admin_menu()
{
	$current_screen = get_current_screen();
	if ($current_screen->post_type == 'post') {
		remove_menu_page('edit-comments.php');
	}
}
add_action('current_screen', 'disable_comments_admin_menu');

