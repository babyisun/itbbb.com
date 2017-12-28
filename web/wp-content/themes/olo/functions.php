<?php 

	define('IsMobile', wp_is_mobile());
	define('TPLDIR', get_template_directory_uri());

add_action( 'after_setup_theme', 'olo_setup' );
function olo_setup(){
	// for /languages/
	load_theme_textdomain( 'olo', get_template_directory() . '/languages' );

	//set content width for video
	global $content_width;
	if ( ! isset( $content_width ) )
		$content_width = 700;

	//Add background for theme
	add_theme_support('custom-background');
	
	//post-thumbnails
	add_theme_support( 'post-thumbnails' );
	add_image_size('index', 70, 50);
	
	//editor style
	add_editor_style('css/editor.css');
	
	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );
	
	//olo Title Tag
	add_filter( 'wp_title', 'olo_wp_title', 10, 2 );
	
	// Enqueue style-file, if it exists.
	add_action('wp_enqueue_scripts', 'olo_script');
	
	//copyright below single
	add_filter('the_content', 'olo_copyright');
	
	// Add sidebar
	add_action( 'widgets_init', 'olo_widgets' );
	
	//Add custom-header for logo
	$olo_logo = array(
		'default-image'          => TPLDIR.'/images/logo.gif',
		'random-default'         => false,
		'width'                  => 100,
		'height'                 => 100,
		'header-text'            => false,
		'uploads'                => true,
	);
	add_theme_support( 'custom-header', $olo_logo );
	
	// Add menu
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation','olo'),
		'mobile' => __( 'Mobile Navigation', 'olo'),
	) );
};

//Custom wp_list_pages
function olo_wp_list_pages(){
	echo "<ul>";
	echo wp_list_pages('title_li=&depth=1');
	echo "</ul>";
}

//olo Title Tag
function olo_wp_title($title, $sep) {
	global $paged, $page;
	if ( is_feed() ) {
		return $title;
	}
	// Add the site name.
	$title .= get_bloginfo( 'name' );
	// Add a page number if necessary:
	if ( is_paged() ){
		echo sprintf( __( 'Page %s', 'olo' ), max( $paged, $page ) )."$sep";
	}
	// Add the blog name.
	if(is_home() && is_front_page()) {
		return $title;
	}
	return $title;
}

// Enqueue style-file, if it exists.
function olo_script() {
	wp_enqueue_script( 'jquerycdn','//code.jquery.com/jquery.min.js', '', array(), '20140224', true);

	//if( !IsMobile ){
		wp_enqueue_style( 'olo', get_stylesheet_uri(),  array(), '20140224', false);
	//}else{
		//wp_enqueue_style('mobile', TPLDIR . '/css/mobile.css', array(), '20140224', false);
	//};
		
	wp_enqueue_script( 'olo', TPLDIR . '/js/sky.js', array(), '20140224', true);
	/*wp_enqueue_script( 'comments-ajax', TPLDIR . '/js/comments-ajax.js', array(), '20140224', true);
	wp_localize_script('comments-ajax', 'ajaxL10n', array(
		'edt1' => __('Before Refresh, you can','olo'),
		'edt2' => __('Edit','olo'),
		'cancel_edit' => __('Cancel Editing','olo'),
		'txt1' => __('Wait a moment...','olo'),
		'txt3' => __('Good Comment','olo')
	));*/	

	if ( is_singular() && comments_open() ) wp_enqueue_script( 'comment-reply' );
			
	if( is_page('archives') ){
		wp_enqueue_script( 'archives', TPLDIR . '/js/archives.js', array(), '20140224', false);
		wp_enqueue_style( 'archives', TPLDIR . '/css/archives.css', array(), '20140224', 'screen');
	};
	if(is_404()){
		wp_enqueue_style( '4041', 'http://fonts.googleapis.com/css?family=Press+Start+2P', array(), '20140224', 'screen');
		wp_enqueue_style( '4042', 'http://fonts.googleapis.com/css?family=Oxygen:700', array(), '20140224', 'screen');
		wp_enqueue_style( '4043', TPLDIR . '/css/404.css', array(), '20140224', 'screen');
	}
}

//par_pagenavi	
function olo_pagenavi(){
	$args = array(
	'base'         => '%_%',
	'format'       => '?page=%#%',
	'total'        => 1,
	'current'      => 0,
	'show_all'     => False,
	'end_size'     => 1,
	'mid_size'     => 2,
	'prev_next'    => True,
	'prev_text'    => __('<< Previous', 'olo'),
	'next_text'    => __('Next >>', 'olo'),
	'type'         => 'plain',
	'add_args'     => False,
	'add_fragment' => ''
	);
	echo paginate_links( $args );
}

//copyright below single
function olo_copyright($content) {
	if( is_single() ){
		$content.= '<p>--'.__('CopyRights','olo').': <a href="'.get_bloginfo('url').'">'.get_bloginfo('name').'</a> &raquo; <a href="'.get_permalink().'">'.get_the_title().'</a></p>';
	}
	return $content;
}

// Add sidebar
function olo_widgets(){
    register_sidebar(array(
		'name' =>''.__('Home', 'olo').'',
		'id' => 'home',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h2><span class="star">',
        'after_title' => '</span></h2>',
    ));
    register_sidebar(array(
		'name'=>''.__('Single', 'olo').'',
		'id' => 'single',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h2><span class="star">',
        'after_title' => '</span></h2>',
    ));
	register_sidebar(array(
		'name'=>''.__('Other', 'olo').'',
		'id' => 'other',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h2><span class="star">',
        'after_title' => '</span></h2>',
    ));
}

//Load Custom parts
 require( dirname( __FILE__ ) . '/inc/theme_inc.php' );
 require( dirname( __FILE__ ) . '/inc/oloComment.php' );
 $olo_theme_options = get_option('olo_theme_options');
?>