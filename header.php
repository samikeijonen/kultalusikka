<?php
/**
 * Header Template
 *
 * The header template is generally used on every page of your site. Nearly all other templates call it 
 * somewhere near the top of the file. It is used mostly as an opening wrapper, which is closed with the 
 * footer.php file. It also executes key functions needed by the theme, child themes, and plugins.
 *
 * @package    Kultalusikka
 * @subpackage Template
 * @author     Sami Keijonen <sami.keijonen@foxnet.fi>
 * @since      0.1.0
 */
?>
<!DOCTYPE html>
<!--[if IE 7 ]> <html class="ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]> <html class="ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<title><?php hybrid_document_title(); ?></title>

<!-- Mobile viewport optimized. -->
<meta name="viewport" content="width=device-width,initial-scale=1" />

<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php wp_head(); // wp_head ?>

</head>

<body class="<?php hybrid_body_class(); ?>">

	<?php do_atomic( 'open_body' ); // kultalusikka_open_body ?>

	<div id="container">

		<?php do_atomic( 'before_header' ); // kultalusikka_before_header ?>

		<header id="header">

			<?php do_atomic( 'open_header' ); // kultalusikka_open_header ?>

			<div class="wrap">
				
					<?php $kultalusikka_header_image = get_header_image();
					
					if ( ! empty( $kultalusikka_header_image ) ) { /* if header image is set use it as logo. */ ?>
						
						<h1 id="site-title"><a href="<?php echo esc_url( home_url() ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"><img src="<?php echo esc_url( $kultalusikka_header_image ); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" /></a></h1>
					
					<?php } else { ?>
					
						<h1 id="site-title"><a href="<?php echo esc_url( home_url() ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
					
					<?php }  ?>

				<?php do_atomic( 'header' ); // kultalusikka_header ?>
				
				<?php get_template_part( 'menu', 'primary' ); // Loads the menu-primary.php template. ?>
				
				<?php get_template_part( 'menu', 'primary-mobile' ); // Loads the menu-primary-mobile.php template. ?>

			</div><!-- .wrap -->

			<?php do_atomic( 'close_header' ); // kultalusikka_close_header ?>
		
		</header><!-- #header -->
		
		<?php do_atomic( 'after_header' ); // kultalusikka_after_header ?>
		
		<h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
		
		<?php do_atomic( 'before_main' ); // kultalusikka_before_main ?>

		<div id="main">

			<div class="wrap">

			<?php do_atomic( 'open_main' ); // kultalusikka_open_main ?>

			<?php if ( current_theme_supports( 'breadcrumb-trail' ) ) breadcrumb_trail( array( 'container' => 'nav', 'separator'  => __( '&#8764;', 'kultalusikka' ), 'show_on_front' => false ) ); ?>