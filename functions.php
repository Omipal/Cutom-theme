<?php

/**
 * Custom functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Custom
 */

if (!defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '1.0.0');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function custom_setup()
{
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Custom, use a find and replace
		* to change 'custom' to the name of your theme in all the template files.
		*/
	load_theme_textdomain('custom', get_template_directory() . '/languages');

	// Add default posts and comments RSS feed links to head.
	add_theme_support('automatic-feed-links');

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support('title-tag');

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support('post-thumbnails');

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__('Primary', 'custom'),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'custom_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets');

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action('after_setup_theme', 'custom_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function custom_content_width()
{
	$GLOBALS['content_width'] = apply_filters('custom_content_width', 640);
}
add_action('after_setup_theme', 'custom_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function custom_widgets_init()
{
	register_sidebar(
		array(
			'name'          => esc_html__('Sidebar', 'custom'),
			'id'            => 'sidebar-1',
			'description'   => esc_html__('Add widgets here.', 'custom'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action('widgets_init', 'custom_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function custom_scripts()
{
	// Enqueue styles
	wp_enqueue_style('bootstrapcdn', '//maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');
	wp_enqueue_style('styletheme', get_template_directory_uri() . '/css/style.css');
	wp_enqueue_style('font_awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
	wp_enqueue_style('custom_font', '//fonts.googleapis.com/css?family=Poppins:100,300,500,600');
	wp_enqueue_style('main_style', get_stylesheet_uri());


	// Enqueue scripts

	wp_enqueue_script('jQuerycdn', '//ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js', false, false, true);
	wp_enqueue_script('bootstrap_js', '//maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', false, '', true);
	wp_enqueue_script('popper_js', '//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js', false, '', true);
	wp_enqueue_script('isotop', '//cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js', array('jquery'), false, true);
	wp_enqueue_script('imagemin', get_template_directory_uri() . '/js/image.min.js', 'jquery', null, true);
	wp_enqueue_script('main_js', get_template_directory_uri() . '/js/main.js', false, '', true);
	wp_enqueue_script('jquery');
	wp_enqueue_script('custom-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'custom_scripts');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
	require get_template_directory() . '/inc/jetpack.php';
}

function filter_posts()
{
	$args = array(
		'post_type' => 'post',
		'posts_per_page' => -1,
		'order_by' => 'menu_order',
		'order' => 'desc'
	);
	if (isset($_POST['category'])) {
		$args['cat'] = $_POST['category'];
	}

	$ajaxposts = new WP_Query($args);
	if ($ajaxposts->have_posts()) :
		while ($ajaxposts->have_posts()) : $ajaxposts->the_post();
?>
			<div class="grid-item">
				<h4><?php the_title(); ?></h4>
				<span class="post-cats">
					<?php
					the_terms(get_the_ID(), 'category', 'Categories: ', ' / ');
					?>
				</span>
				<p class="contents"><?php wp_trim_words(the_content(), 55); ?></p>
			</div>
		<?php

		endwhile;
		wp_reset_postdata();
	else :
		echo "No Post Found";
	endif;
	wp_die();
}

add_action('wp_ajax_myfilter', 'filter_posts');
add_action('wp_ajax_nopriv_myfilter', 'filter_posts');

/**
 * port folio custum post type.
 */

function create_portfolio_function()
{
	$labels = array(
		'name' => _x('Portfolios', 'post type general name', 'custom'),
		'Singular_name' => _x('Portfolio', 'post type singular name', 'custom'),
		'add_new' => _x('Add Portfolio', 'custom'),
		'add_new_item' => __('Add New Portfolio', 'custom'),
		'edit_item' => __('Edit Portfolio', 'custom'),
		'new_item' => __('New Portfolio', 'custom'),
		'all_items' => __('All Portfolios', 'custom'),
		'view_item' => __('View Portfolio', 'custom'),
		'search_item' => __('Search Portfolio', 'custom'),
		'not_found' => __('No Portfolio Found', 'custom'),
		'not_found_in_trash' => __('No Portfolio on Trash', 'custom'),
		'parent_item_colon' => '',
		'menu_name' => __('Portfolios', 'custom')
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => array('slug' => 'portfolio'),
		'capability_type' => 'page',
		'has_archive' => true,
		'hierarchical' => true,
		'menu_position' => null,
		'menu_icon' => 'dashicons-format-audio',
		'supports' => array('title', 'thumbnail')
	);
	$labels = array(
		'name' => __('Category'),
		'singular_name' => __('Category'),
		'search_items' => __('Search'),
		'popular_items' => __('More Used'),
		'all_items' => __('All Categories'),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_items' => __('Add new'),
		'update_item' => __('Update'),
		'add_new_item' => __('Add new Category'),
		'new_item_name' => __('New'),

	);
	register_taxonomy('portfolio_category', array('portfolio'), array(
		'hierarchical' => true,
		'labels' => $labels,
		'singular_labels' => 'portfolio_category',
		'all_items' => 'Category',
		'query_var' => true,
		'rewrite' => array('slug' => 'cat')
	));
	register_post_type('portfolio', $args);
	flush_rewrite_rules();
}

add_action('init', 'create_portfolio_function');

// Load More Function By Ajax

add_action('wp_ajax_load_posts_by_ajax', 'load_posts_by_ajax-callback');
add_action('wp_ajax_nopriv_load_posts_by_ajax', 'load_posts_by_ajax-callback');

function load_posts_by_ajax_callback()
{
	check_ajax_referer('load_more_post', 'security');
	$paged = $_POST['page'];

	$args = array(
		'post_type' => 'portfolio',
		'post_status' => 'publish',
		'posts_per_page' => 4,
		'paged' => 	$paged,
	);

	$my_posts = new WP_Query($args);

	if ($my_posts->have_posts()) { ?>
		<?php while ($my_posts->have_posts()) {
			$my_posts->the_post();

			$termsArray = get_the_terms($post->ID, 'portfolio_category');

			$termsSlug = "";
			foreach ($termsArray as $term) {
				$termsSlug .= $term->slug . " ";
			}
		?>
			<div class="single-content <?php echo $termsSlug; ?> grid-item">
				<?php if (get_the_post_thumbnail()) { ?>
					<img class="p2" src="<?php the_post_thumbnail_url(); ?>" alt="Video">
				<?php  } ?>
			</div>
		<?php	} ?>
<?php }

	wp_die();
}
