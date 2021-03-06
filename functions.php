<?php
/**
 * The functions.php file is used to initialize everything in the theme. It controls how the theme is loaded and 
 * sets up the supported features, default actions, and default filters. If making customizations, users 
 * should create a child theme and make changes to its functions.php file (not this one).
 *
 * @package     Kultalusikka
 * @subpackage  Functions
 * @version     0.1.0
 * @author      Sami Keijonen <sami.keijonen@foxnet.fi>
 * @copyright   Copyright (c) 2012, Sami Keijonen
 * @link        https://foxnet-themes.fi
 * @license     http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
 
/* Load Hybrid Core theme framework. */
require_once( trailingslashit( get_template_directory() ) . 'library/hybrid.php' );
new Hybrid();

/* Theme setup function using 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'kultalusikka_theme_setup' );

/**
 * Theme setup function. This function adds support for theme features and defines the default theme
 * actions and filters.
 *
 * @since 0.1.0
 */
function kultalusikka_theme_setup() {

	/* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix();
	
	/* Add theme settings. */
	if ( is_admin() )
	    require_once( trailingslashit ( get_template_directory() ) . 'admin/functions-admin.php' );

	/* Add theme support for core framework features. */
	add_theme_support( 'hybrid-core-menus', array( 'primary', 'subsidiary' ) );
	add_theme_support( 'hybrid-core-sidebars', array( 'primary', 'subsidiary' ) );
	add_theme_support( 'hybrid-core-widgets' );
	add_theme_support( 'hybrid-core-shortcodes' );
	add_theme_support( 'hybrid-core-theme-settings', array( 'about', 'footer' ) );
	add_theme_support( 'hybrid-core-scripts', array( 'drop-downs' ) );
	add_theme_support( 'hybrid-core-styles', array( 'parent', 'style' ) );
	add_theme_support( 'hybrid-core-template-hierarchy' );
	
	/* Add theme support for framework extensions. */
	add_theme_support( 'theme-layouts', array( '1c', '2c-l', '2c-r' ), array( 'default' => '2c-l' ) );
	add_theme_support( 'post-stylesheets' );
	add_theme_support( 'loop-pagination' );
	add_theme_support( 'get-the-image' );
	add_theme_support( 'breadcrumb-trail' );
	add_theme_support( 'cleaner-gallery' );
	add_theme_support( 'cleaner-caption' );
	
	/* Add theme support for media grabber. */
	add_theme_support( 'hybrid-core-media-grabber' );
	
	/* Add theme support for WordPress features. */
	
	/* Add content editor styles. */
	add_editor_style( 'css/editor-style.css' );
	
	/* Add support for auto-feed links. */
	add_theme_support( 'automatic-feed-links' );

	/* Add theme support for post formats. */
	add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'image', 'gallery', 'link', 'quote', 'status', 'video' ) );
	
	/* Add custom background feature. */
	add_theme_support( 
		'custom-background',
		array(
			'default-color' => 'ffffff', // Background default color
			'default-image' => trailingslashit( get_template_directory_uri() ) . 'images/kultalusikka_background.png' // Background image default
		)
	);
	
	/* Add support for flexible headers. This means logo in this theme, not header image. @link http://make.wordpress.org/themes/2012/04/06/updating-custom-backgrounds-and-custom-headers-for-wordpress-3-4/ */
	$kultalusikka_header_args = array(
		'flex-height' => true,
		'height' => 79,
		'flex-width' => true,
		'width' => 300,
		'default-image' => trailingslashit( get_template_directory_uri() ) . 'images/logo_kultalusikka.png',
		'header-text' => false,
	);
	
	add_theme_support( 'custom-header', $kultalusikka_header_args );
	
	/* Set up Licence key for this theme. URL: https://easydigitaldownloads.com/docs/activating-license-keys-in-wp-plugins-and-themes */
 
	// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed.
	define( 'KULTALUSIKKA_SL_STORE_URL', 'http://foxnet-themes.fi' ); // add your own unique prefix to prevent conflicts

	// the name of your product. This should match the download name in EDD exactly.
	define( 'KULTALUSIKKA_SL_THEME_NAME', 'Kultalusikka' ); // add your own unique prefix to prevent conflicts
	
	/* Define current version of Kultalusikka. Get it from parent theme style.css. */
	$kultalusikka_theme = wp_get_theme( 'kultalusikka' );
	if ( $kultalusikka_theme->exists() ) {
		define( 'KULTALUSIKKA_VERSION', $kultalusikka_theme->Version ); // Get parent theme Kultalusikka version
	}
	
	/* Setup updater. */
	add_action( 'admin_init', 'kultalusikka_theme_updater' );
	
	/* Set content width. */
	hybrid_set_content_width( 604 );
	add_filter( 'embed_defaults', 'kultalusikka_embed_defaults' );
	
	/* Add respond.js and  html5shiv.js for unsupported browsers. */
	add_action( 'wp_head', 'kultalusikka_respond_html5shiv' );
	
	/* Add custom image sizes. */
	add_action( 'init', 'kultalusikka_add_image_sizes' );
	
	/* Enqueue scripts. */
	add_action( 'wp_enqueue_scripts', 'kultalusikka_scripts_styles' );
	
	/* Disable primary sidebar widgets when layout is one column. */
	add_filter( 'sidebars_widgets', 'kultalusikka_disable_sidebars' );
	add_action( 'template_redirect', 'kultalusikka_one_column' );
	
	/* Add number of subsidiary and front page widgets to body_class. */
	add_filter( 'body_class', 'kultalusikka_subsidiary_classes' );
	add_filter( 'body_class', 'kultalusikka_front_page_classes' );
	
	/* Set customizer transport. */
	add_action( 'customize_register', 'kultalusikka_customize_register' );
	
	/* Add js to customize. */
	add_action( 'customize_preview_init', 'kultalusikka_customize_preview_js' );
	
	/* Add css to customize. */
	add_action( 'wp_enqueue_scripts', 'kultalusikka_customize_preview_css' );
	
	/* Use same taxonomy template. */
	add_filter( 'taxonomy_template', 'kultalusikka_taxonomy_template', 11 );
	
	/* Register additional sidebar to 'front page' page template. */
	add_action( 'widgets_init', 'kultalusikka_register_sidebars' );
	
	/* Add own button style to EDD Plugin (Settings >> Style). */
	add_filter( 'edd_button_colors', 'kultalusikka_add_button_color' );
	
	/* Change EDD Download info feature image size. */
	add_filter( 'edd_download_info_feature_image_size', 'kultalusikka_edd_download_info_feature_size' );
	
	/* Disable bbPress breadcrumb. */
	add_filter( 'bbp_no_breadcrumb', '__return_true' );
	
	/* Display topics (bbPress) by last activity. */
	add_action( 'pre_get_posts', 'kultalusikka_filter_topic' );
	
	/* Display forums (bbPress) by title. */
	add_action( 'pre_get_posts', 'kultalusikka_filter_forum' );
	
	/* Change user profile gravatar size. */
	add_filter( 'bbp_single_user_details_avatar_size', 'kultalusikka_user_details_avatar_size' );
	
	/* Add menu-item-parent class to parent menu items.  */
	add_filter( 'wp_nav_menu_objects', 'kultalusikka_add_menu_parent_class' );

}

/**
 * Setup theme updater. @link https://gist.github.com/pippinsplugins/3ab7c0a01d5a9d8005ed
 *
 * @since  0.1.6
 */
function kultalusikka_theme_updater() {

	/* If there is no valid license key status, don't let updates. */
	if( get_option( 'kultalusikka_license_key_status' ) != 'valid' )
		return;

	/* load our custom theme updater. */
	if( !class_exists( 'EDD_SL_Theme_Updater' ) )
		require_once( trailingslashit( get_template_directory() ) . 'includes/EDD_SL_Theme_Updater.php' );
		
	/* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix();
	
	/* Get license key from database. */
	$kultalusikka_get_license = get_option( $prefix . '_theme_settings' ); // This is array.
	$kultalusikka_license = isset( $kultalusikka_get_license['kultalusikka_license_key'] ) ? $kultalusikka_get_license['kultalusikka_license_key'] : '';

	$edd_updater = new EDD_SL_Theme_Updater( array( 
		'remote_api_url' 	=> KULTALUSIKKA_SL_STORE_URL, 	// our store URL that is running EDD
		'version' 			=> KULTALUSIKKA_VERSION, 		// the current theme version we are running
		'license' 			=> $kultalusikka_license, 		// the license key (used get_option above to retrieve from DB)
		'item_name' 		=> KULTALUSIKKA_SL_THEME_NAME,	// the name of this theme
		'author'			=> 'Sami Keijonen'	            // the author's name
		)
	);

}

/**
 * Overwrites the default widths for embeds. This function overwrites what the $content_width variable handles
 * with context-based widths.
 *
 * @since  0.1.0
 * @param  array  $args
 * @return array
 */
function kultalusikka_embed_defaults( $args ) {

	if ( current_theme_supports( 'theme-layouts' ) && '1c' == get_theme_mod( 'theme_layout' ) )
		$args['width'] = 944;

	return $args;
}

/**
 * Function for help to unsupported browsers understand mediaqueries and html5.
 * @link: https://github.com/scottjehl/Respond
 * @link: http://code.google.com/p/html5shiv/
 * @since 0.1.0
 */
function kultalusikka_respond_html5shiv() {
	?>
	
	<!-- Enables media queries and html5 in some unsupported browsers. -->
	<!--[if (lt IE 9) & (!IEMobile)]>
	<script type="text/javascript" src="<?php echo trailingslashit( get_template_directory_uri() ); ?>js/respond/respond.min.js"></script>
	<script type="text/javascript" src="<?php echo trailingslashit( get_template_directory_uri() ); ?>js/html5shiv/html5shiv.js"></script>
	<![endif]-->
	
	<?php
}


/**
 *  Adds custom image sizes for thumbnail images.
 * 
 * @since 0.1.0
 */
function kultalusikka_add_image_sizes() {

	add_image_size( 'kultalusikka-thumbnail-download', 428, 265, true );
	add_image_size( 'kultalusikka-thumbnail-gallery', 256, 158, true );
	
}

/**
 * Enqueues scripts and styles.
 *
 * @since 0.1.0
 */
function kultalusikka_scripts_styles() {

	if ( !is_admin() ) {
	
		/* Adds JavaScript for handling the navigation menu hide-and-show behavior. */
		wp_enqueue_script( 'kultalusikka-navigation',  trailingslashit( get_template_directory_uri() ) . 'js/navigation.js', array(), '20121711', true );
	
		/* Enqueue FitVids. */
		wp_enqueue_script( 'kultalusikka-fitvids', trailingslashit( get_template_directory_uri() ) . 'js/fitvids/jquery.fitvids.js', array( 'jquery' ), '20121117', true );
		wp_enqueue_script( 'kultalusikka-fitvids-settings', trailingslashit( get_template_directory_uri() ) . 'js/fitvids/fitvids.js', array( 'kultalusikka-fitvids' ), '20121117', true );
		
	}
}

/**
 * Disables sidebars if viewing a one-column page.
 *
 * @since 0.1.0
 * @param array $sidebars_widgets A multidimensional array of sidebars and widgets.
 * @return array $sidebars_widgets
 */
function kultalusikka_disable_sidebars( $sidebars_widgets ) {
	global $wp_query, $wp_customize;

	if ( current_theme_supports( 'theme-layouts' ) && !is_admin() ) {
		if ( ! isset( $wp_customize ) ) {
			if ( 'layout-1c' == theme_layouts_get_layout() ) {
				$sidebars_widgets['primary'] = false;
			}
		}
	}

	return $sidebars_widgets;
}

/**
 * Function for deciding which pages should have a one-column layout.
 *
 * @since 0.1.0
 */
function kultalusikka_one_column() {

	if ( !is_active_sidebar( 'primary' ) )
		add_filter( 'theme_mod_theme_layout', 'kultalusikka_theme_layout_one_column' );
		
	elseif ( is_post_type_archive( 'download' ) )
		add_filter( 'theme_mod_theme_layout', 'kultalusikka_theme_layout_one_column' );
		
	elseif ( is_tax( 'download_category' ) || is_tax( 'download_tag' ) )
		add_filter( 'theme_mod_theme_layout', 'kultalusikka_theme_layout_one_column' );
		
	elseif ( is_tax( 'edd_download_info_feature' ) )
		add_filter( 'theme_mod_theme_layout', 'kultalusikka_theme_layout_one_column' );
	
	elseif ( is_attachment() && 'layout-default' == theme_layouts_get_layout() )
		add_filter( 'theme_mod_theme_layout', 'kultalusikka_theme_layout_one_column' );

	elseif ( is_page_template( 'page-templates/front-page.php' ) )
		add_filter( 'theme_mod_theme_layout', 'kultalusikka_theme_layout_one_column' );

}


/**
 * Filters 'get_theme_layout' by returning 'layout-1c'.
 *
 * @since 0.1.0
 * @param string $layout The layout of the current page.
 * @return string
 */
function kultalusikka_theme_layout_one_column( $layout ) {
	return '1c';
}

/**
 * Counts widgets number in subsidiary sidebar and ads css class (.sidebar-subsidiary-$number) to body_class.
 * Used to increase / decrease widget size according to number of widgets.
 * Example: if there's one widget in subsidiary sidebar - widget width is 100%, if two widgets, 50% each...
 * @author Sinisa Nikolic
 * @copyright Copyright (c) 2012
 * @link http://themehybrid.com/themes/sukelius-magazine
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @since 0.1.0
 */
function kultalusikka_subsidiary_classes( $classes ) {
    
	if ( is_active_sidebar( 'subsidiary' ) ) {
		
		$the_sidebars = wp_get_sidebars_widgets();
		$num = count( $the_sidebars['subsidiary'] );
		$classes[] = 'sidebar-subsidiary-'. $num;
		
    }
    
    return $classes;
	
}

/**
 * Counts widgets number in front-page sidebar and ads css class (.sidebar-front-page-$number) to body_class.
 * @author Sinisa Nikolic
 * @copyright Copyright (c) 2012
 * @link http://themehybrid.com/themes/sukelius-magazine
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @since 0.1.0
 */
function kultalusikka_front_page_classes( $classes ) {
	
	if ( is_active_sidebar( 'front-page' ) && is_page_template( 'page-templates/front-page.php' ) ) {
		
		$the_sidebars = wp_get_sidebars_widgets();
		$num = count( $the_sidebars['front-page'] );
		$classes[] = 'sidebar-front-page-'. $num;
		
    }
    
    return $classes;
	
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 * @since 0.1.0
 * @note: credit goes to TwentyTwelwe theme.
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function kultalusikka_customize_register( $wp_customize ) {
	
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	
}

/**
 * This make Theme Customizer live preview reload changes asynchronously.
 * Used with blogname and blogdescription.
 * @note: credit goes to TwentyTwelwe theme.
 * @since 0.1.0
 */
function kultalusikka_customize_preview_js() {

	wp_enqueue_script( 'kultalusikka-customizer', trailingslashit( get_template_directory_uri() ) . 'js/customize/kultalusikka-customizer.js', array( 'customize-preview' ), '20121019', true );
	
}

/**
 * This make Theme Customizer live preview work with 1 column layout.
 * Used with Primary and Secondary sidebars.
 * 
 * @since 0.1.0
 */
function kultalusikka_customize_preview_css() {
	global $wp_customize;

	if ( isset( $wp_customize ) )
		wp_enqueue_style( 'kultalusikka-customizer-stylesheet', trailingslashit( get_template_directory_uri() ) . 'css/customize/kultalusikka-customizer.css', false, '20121019', 'all' );
}

/**
 * Use template 'archive-download.php' in taxonomies 'edd_download_info_feature' and 'download_category' so that we don't need to duplicate same template content.
 * 
 * @since 0.1.0
 */
function kultalusikka_taxonomy_template( $template  ) {

	if ( is_tax( 'download_category' ) || is_tax( 'download_tag' ) || is_tax( 'edd_download_info_feature' ) )
		$template = locate_template( array( 'archive-download.php' ) );

	return $template;
	
}

/**
 * Register additional sidebar to 'front page' page template.
 * 
 * @since 0.1.0
 */
function kultalusikka_register_sidebars() {

	/* Register the 'front-page' sidebar. */
	register_sidebar(
		array(
			'id' => 'front-page',
			'name' => _x( 'Front Page', 'Front Page Widget', 'kultalusikka' ),
			'description' => __( 'Front Page widget area.', 'kultalusikka' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s widget-%2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);
	
	/* Register the 'front-page-callout' sidebar. */
	register_sidebar(
		array(
			'id' => 'front-page-callout',
			'name' => __( 'Front Page Callout', 'kultalusikka' ),
			'description' => __( 'Front Page Callout widget area. This is meant to used for short text area.', 'kultalusikka' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s widget-%2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);
	
	/* Register the 'after-singular-download' sidebar. But register only if EDD is installed. */
	if ( class_exists( 'Easy_Digital_Downloads' ) ) {
		register_sidebar(
			array(
				'id' => 'after-singular-download',
				'name' => __( 'After Singular Download', 'kultalusikka' ),
				'description' => __( 'After singular download widget area. This only works with Easy Digital Download plugin.', 'kultalusikka' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s widget-%2$s">',
				'after_widget' => '</section>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			)
		);
	}

}

/**
 * Add button style to EDD Plugin.
 * 
 * @since 0.1.0
 */
function kultalusikka_add_button_color( $button_style  ) {
	
	if ( function_exists( 'edd_get_button_colors' ) ) {
		$button_style['theme-green'] = array( 
			'label' => __( 'Theme Green', 'kultalusikka' ),
			'hex'   => ''
		);
	}

	return $button_style;
	
}

/**
 * Change feature image size.
 * 
 * @since 0.1.0
 */
function kultalusikka_edd_download_info_feature_size( $feature_size ) {
	
	if ( function_exists( 'edd_download_info_register_widgets' ) ) 
		$feature_size = 'kultalusikka-thumbnail-download';

	return $feature_size;
	
}


/**
 * Display topics (bbPress) by last activity.
 * 
 * @since 0.1.0
 */
function kultalusikka_filter_topic( $query ) {

	/* Get topic post type. */
	if( function_exists( 'bbp_get_topic_post_type' ) )
		$kultalusikka_topic = bbp_get_topic_post_type();
	else
		$kultalusikka_topic = 'topic';
	
	/* Set query to show topics by latest activity. */
	if( $query->is_main_query() && is_post_type_archive( $kultalusikka_topic ) ) {
	
		$query->set( 'meta_key', '_bbp_last_active_time' );
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'order', 'DESC' );
 
	}
	
}

/**
 * Display forums (bbPress) by title.
 * 
 * @since 0.1.0
 */
function kultalusikka_filter_forum( $query ) {

	/* Get forum post type. */
	if( function_exists( 'bbp_get_forum_post_type' ) )
		$kultalusikka_forum = bbp_get_forum_post_type();
	else
		$kultalusikka_forum = 'forum';
	
	/* Set query to show forums by title. */
	if( $query->is_main_query() && is_post_type_archive( $kultalusikka_forum ) ) {
	
		$query->set( 'orderby', 'title' );
		$query->set( 'order', 'ASC' );
 
	}
	
}
/**
 * Change user profile gravatar size.
 * 
 * @since 0.1.0
 */
function kultalusikka_user_details_avatar_size() {
	
	return 80;
	
}

/**
 * Add menu-item-parent class to parent menu items. Thanks to Chip Bennett.
 *
 * @since 0.2
 */
function kultalusikka_add_menu_parent_class( $items ) {

	$parents = array();

	foreach ( $items as $item ) {

		if ( $item->menu_item_parent && $item->menu_item_parent > 0 )
			$parents[] = $item->menu_item_parent;
		
	}

	foreach ( $items as $item ) {

		if ( in_array( $item->ID, $parents ) )
			$item->classes[] = 'menu-item-parent';

	}

	return $items;    

}

/**
 * Returns the URL from the post.
 *
 * @uses get_the_post_format_url() to get the URL in the post meta (if it exists) or
 * the first link found in the post content.
 *
 * Falls back to the post permalink if no URL is found in the post.
 * @note This idea is taken from Twenty Thirteen theme.
 * @author wordpressdotorg
 * @copyright Copyright (c) 2011, wordpressdotorg
 *
 * @since 0.2.0
 */
function kultalusikka_get_link_url() {

	$kultalusikka_content = get_the_content();

	$kultalusikka_url = get_url_in_content( $kultalusikka_content );

	return ( $kultalusikka_url ) ? $kultalusikka_url : apply_filters( 'the_permalink', get_permalink() );

}

?>