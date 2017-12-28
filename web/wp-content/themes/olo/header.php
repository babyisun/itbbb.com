<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<meta name="keywords" content="北北报,北北,IT北北报,IT圈,传播互联网意识形态,JSFunction,javascript常用函数,js扩展函数,我是程序员,bootstrap .net 架构,前端工程师博客,IT书籍,学什么技术有前途,互联网产品,web前端工程师,.net技术总结,前端实用技术"/>
    	<meta name="description" content="IT北北报，传播互联网意识形态，经常混迹在IT圈。推荐软件工程师学习什么技术，互联网产品经理可以关注哪些方向，作为IT人应该读什么书，做什么事等等；关注IT行业，分享IT动态，让更多的同学们融入的互联网时代浪潮中。"/>

<title><?php wp_title( '|', true, 'right' ); ?></title>


<?php wp_head();?>

<link rel="icon" type="image/x-icon" href="http://itbbb.com/favicon.ico"/>
<link rel="shortcut icon" type="image/x-icon" href="http://itbbb.com/favicon.ico" />
<link rel="Bookmark" type="image/x-icon" href="http://itbbb.com/favicon.ico" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php if (is_archive() && ($paged > 1)&& ($paged < $wp_query->max_num_pages)) { ?>
<link rel="prefetch" href="<?php echo get_next_posts_page_link(); ?>">
<link rel="prerender" href="<?php echo get_next_posts_page_link(); ?>">
<?php } ?>
<link href="http://www.itbbb.com/jsfunction/highlight/highlight.min.css" rel="stylesheet">
<!--[if lte IE 9]>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
<![endif]-->
</head>
<body <?php body_class(); ?> id="olo">
	<header id="oloLogo">
		<div id="title">
	  <a class="home-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">

	   <?php $header_image = get_header_image();
		if ( !empty( $header_image ) ) : ?>
			<img
				class="logo"
				src="<?php esc_url(header_image()); ?>" 
				height="<?php echo esc_attr(get_custom_header()->height); ?>" 
				width="<?php echo esc_attr(get_custom_header()->width); ?>" 
				alt="<?php bloginfo('name'); ?>" 
			/>
	   <?php endif; ?>

	    <h1 class="site-title"><?php bloginfo('name'); ?></h1>
	    <h2 class="site-description"><?php bloginfo('description'); ?></h2>
	</a>
	  
	</div>
		<nav id="oloMenu">
		<?php if(!IsMobile) { ?>
		<?php wp_nav_menu( array( 'theme_location' => 'primary', 'fallback_cb' => 'olo_wp_list_pages', 'container' => false ) ); ?>
		<?php }else{ ?>
		<?php wp_nav_menu( array( 'theme_location' => 'mobile', 'fallback_cb' => 'olo_wp_list_pages', 'container' => false ) ); ?>
		<?php } ?>
		</nav>
	</header>
	<div class="clear"></div>
	