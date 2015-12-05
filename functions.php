<?php
/**
 * reddit functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package reddit
 */

if ( ! function_exists( 'reddit_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function reddit_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on reddit, use a find and replace
	 * to change 'reddit' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'reddit', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'reddit' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'reddit_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // reddit_setup
add_action( 'after_setup_theme', 'reddit_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function reddit_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'reddit_content_width', 640 );
}
add_action( 'after_setup_theme', 'reddit_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function reddit_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'reddit' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'reddit_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function reddit_scripts() {
	wp_enqueue_style( 'reddit-font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css', array(), '12314');

	wp_enqueue_style( 'reddit-bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), '121222');

	wp_enqueue_style( 'reddit-main', get_template_directory_uri() . '/css/main.css', array(), '121222');

	wp_enqueue_script( 'reddit-bootstrap-js', get_template_directory_uri() . '/js/bootstrap.min.js', array(), '20120206', true );
	
	wp_enqueue_script( 'reddit-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'reddit-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'reddit_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';



function excerpt($limit) {
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }	
  $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
  return $excerpt;
}
 
function content($limit) {
  $content = explode(' ', get_the_content(), $limit);
  if (count($content)>=$limit) {
    array_pop($content);
    $content = implode(" ",$content).'...';
  } else {
    $content = implode(" ",$content);
  }	
  $content = preg_replace('/\[.+\]/','', $content);
  $content = apply_filters('the_content', $content); 
  $content = str_replace(']]>', ']]&gt;', $content);
  return $content;
}



#Custom paging
if (!function_exists('get_theme_pagination')) {
    function get_theme_pagination($type = "")
    {
        if ($type == "query2") {
            global $paged, $wp_query2;
            $wp_query = $wp_query2;
        } else {
            global $paged, $wp_query;
        }
        $range = 2;
		$showitems = $range;

        if (empty($paged)) {
            $paged = (get_query_var('page')) ? get_query_var('page') : 1;
        }

        $max_page = $wp_query->max_num_pages;
        if ($max_page > 1) {
            echo '<ul class="pagerblock nolist">';
        }
		if($paged > 1) echo '<li class="newer_posts"><a href="' .get_pagenum_link($paged - 1) . ' "><i class="fa fa-angle-left"></i>' . __('Newer Posts','theme_localization') . '</a></li>';
        if ($max_page > 1) {
            if (!$paged) {
                $paged = 1;
            }
            if ($max_page > $range) {
                if ($paged < $range) {
                    for ($i = 1; $i <= ($range + 1); $i++) {
                        echo "<li><a href='" . get_pagenum_link($i) . "'";
                        if ($i == $paged) echo " class='current'";
                        echo ">$i</a></li>";
                    }
                } elseif ($paged >= ($max_page - ceil(($range / 2)))) {
                    for ($i = $max_page - $range; $i <= $max_page; $i++) {
                        echo "<li><a href='" . get_pagenum_link($i) . "'";
                        if ($i == $paged) echo " class='current'";
                        echo ">$i</a></li>";
                    }
                } elseif ($paged >= $range && $paged < ($max_page - ceil(($range / 2)))) {
                    for ($i = ($paged - ceil($range / 2)); $i <= ($paged + ceil(($range / 2))); $i++) {
                        echo "<li><a href='" . get_pagenum_link($i) . "'";
                        if ($i == $paged) echo " class='current'";
                        echo ">$i</a></li>";
                    }
                }
            } else {
                for ($i = 1; $i <= $max_page; $i++) {
                    echo "<li><a href='" . get_pagenum_link($i) . "'";
                    if ($i == $paged) echo " class='current'";
                    echo ">$i</a></li>";
                }
            }
        }
		if ($paged < $max_page) echo '<li class="older_posts"><a href="' . get_pagenum_link($paged + 1) . '">' . __('Older Post','theme_localization') . '<i class="fa fa-angle-right"></i></a></li>';
        if ($max_page > 1) {
            echo '</ul>';
        }
    }
}


if (!function_exists('theme_comment')) {
    function theme_comment($comment, $args, $depth)
    {
        $max_depth_comment = ($args['max_depth'] > 4 ? 4 : $args['max_depth']);

        $GLOBALS['comment'] = $comment; ?>
        <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
        <div id="comment-<?php comment_ID(); ?>" class="stand_comment">
            <div class="commentava">
                <?php echo get_avatar($comment->comment_author_email, 53); ?>
            </div>
            <div class="thiscommentbody">
                <div class="comment_info">
                    <span class="author_name"><b><?php printf('%s', get_comment_author_link()) ?></b><?php edit_comment_link('(Edit)', '  ', '') ?></span>
                    <span class="date"><?php printf('%1$s', get_comment_date("F d, Y")) ?></span>
                    <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'reply_text' => __('Reply', 'theme_localization'), 'max_depth' => $max_depth_comment))) ?>
                </div>
                <?php if ($comment->comment_approved == '0') : ?>
                    <p><em><?php _e('Your comment is awaiting moderation.', 'theme_localization'); ?></em></p>
                <?php endif; ?>
                <?php comment_text(); ?>
            </div>
            <div class="clear"></div>
        </div>
        <?php
    }
}
