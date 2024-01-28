<?php
/**
 * Plugin Name: Advanced Post Block
 * Description: Advanced Post Block - Display posts in a beautiful way!
 * Version: 1.11.4
 * Author: bPlugins LLC
 * Author URI: http://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: advanced-post-block
 */

// ABS PATH
if ( !defined( 'ABSPATH' ) ) { exit; }

// Constant
define( 'APB_VERSION', isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '1.11.4' );
define( 'APB_PATH', plugin_dir_path( __FILE__ ) );
define( 'APB_DIR', plugin_dir_url( __FILE__ ) );

if( !function_exists( 'apb_init' ) ) {
	function apb_init() {
		global $apb_bs;
		require_once( APB_PATH . 'bplugins_sdk/init.php' );
		$apb_bs = new BPlugins_SDK( __FILE__ );
	}
	apb_init();
}else {
	$apb_bs->uninstall_plugin( __FILE__ );
}

require_once APB_PATH . '/inc/functions.php';
require_once APB_PATH . '/inc/style.php';
require_once APB_PATH . '/inc/layout.php';

// Advanced Post Block
class APBAdvancedPostBlock{
	function __construct(){
		add_action( 'enqueue_block_assets', [$this, 'enqueueBlockAssets'] );
		add_action( 'wp_loaded', [$this, 'onLoaded'] );

		add_action( 'wp_ajax_apbPosts', [$this, 'apbPosts'] );
		add_action( 'wp_ajax_nopriv_apbPosts', [$this, 'apbPosts'] );

		register_activation_hook( __FILE__, [$this, 'onPluginActivate'] );

		if ( version_compare( $GLOBALS['wp_version'], '5.8-alpha-1', '<' ) ) {
			add_filter( 'block_categories', [$this, 'blockCategories'] );
		} else { add_filter( 'block_categories_all', [$this, 'blockCategories'] ); }
	}

	function onPluginActivate(){
		if ( is_plugin_active( 'advanced-post-block-pro/plugin.php' ) ){
			deactivate_plugins( 'advanced-post-block-pro/plugin.php' );
		}
	}

	function enqueueBlockAssets(){
		wp_register_script( 'swiper', APB_DIR . 'assets/js/swiper-bundle.min.js', [], '7.0.3', true );
		wp_register_script( 'easyTicker', APB_DIR . 'assets/js/easy-ticker.min.js', [], '3.2.1', true );

		wp_set_script_translations( 'ap-block-posts-script', 'advanced-post-block', APB_PATH . 'languages' );
	}

	function blockCategories( $categories ){
		return array_merge( [ [
			'slug'	=> 'APBlock',
			'title'	=> 'Advanced Post Block'
		] ], $categories );
	}

	function query( $attributes ){
		extract( $attributes );
		global $apb_bs;

		$termsQuery = ['relation' => 'AND'];
		foreach ( $selectedTaxonomies as $taxonomy => $terms ){
			if( count( $terms ) ){
				$termsQuery[] = [
					'taxonomy'	=> $taxonomy,
					'field'		=> 'term_id',
					'terms'		=> $terms,
				];
			}
		}

		$defaultPostQuery = 'post' === $postType ? [
			'category__in'	=> $selectedCategories,
			'tag__in'		=> $selectedTags ?? []
		] : [];

		$postsInclude = APB\Inc\Functions::filterNaN( $postsInclude ?? [] );
		$post__in = !empty( $postsInclude ) ? [ 'post__in' => $postsInclude ] : [];
		$postsExclude = APB\Inc\Functions::filterNaN( $postsExclude ?? [] );

		$query = array_merge( [
			'post_type'			=> $postType,
			'posts_per_page'	=> $isPostsPerPageAll ? -1 : $postsPerPage,
			'orderby'			=> $postsOrderBy,
			'order'				=> $postsOrder,
			'tax_query'			=> $termsQuery,
			'offset'			=> $isPostsPerPageAll ? 0 : $postsOffset,
			'post__not_in'		=> $isExcludeCurrent ? array_merge( [ get_the_ID() ], $postsExclude ) : $postsExclude
		], $post__in, $defaultPostQuery );

		if( $apb_bs->can_use_premium_feature() ) {
			$query = apply_filters( 'apb_query', $query );
		}

		return $query;
	}

	function apbPosts(){
		$attributes = $_POST['queryAttr'];
		$pageNumber = (int)$_POST['pageNumber'];
		extract( $attributes );

		$attributes['isPostsPerPageAll'] = 'true' === $isPostsPerPageAll;
		$attributes['isExcludeCurrent'] = 'true' === $isExcludeCurrent;

		$newArgs = wp_parse_args( [ 'offset' => ( $postsPerPage * ( $pageNumber - 1 ) ) + $postsOffset ], $this->query( $attributes ) );
		$posts = APB\Inc\Functions::arrangedPosts(
			get_posts( $newArgs ),
			$fImgSize,
			$metaDateFormat
		);

		wp_send_json_success( $posts );
	}

	function onLoaded(){
		wp_register_style( 'ap-block-posts-style', plugins_url( 'dist/style.css', __FILE__ ), [ 'dashicons' ], APB_VERSION ); // Style

		wp_register_style( 'ap-block-posts-editor-style', plugins_url( 'dist/editor.css', __FILE__ ), [ 'ap-block-posts-style' ], APB_VERSION ); // Backend Style

		register_block_type( __DIR__, [
			'editor_style'		=> 'ap-block-posts-editor-style',
			'style'				=> 'ap-block-posts-style',
			'render_callback'	=> [$this, 'render']
		] ); // Register Block

		wp_set_script_translations( 'ap-block-posts-editor-script', 'advanced-post-block', APB_PATH . 'languages' );

		remove_action( 'wp_head', 'print_emoji_detection_script', 7 ); // Disable emoji load as image
	} // Register

	function render( $attributes ) {
		extract( $attributes );
		global $apb_bs;

		wp_set_script_translations( 'ap-block-posts-script', 'advanced-post-block', APB_PATH . 'languages' );

		$className = $className ?? '';
		$extraClass = $apb_bs->can_use_premium_feature() ? 'premium' : 'free';
		$blockClassName = "wp-block-ap-block-posts $extraClass $className align$align";

		$allPosts = get_posts( array_merge( $this->query( $attributes ), [ 'posts_per_page' => -1 ] ) );
		$posts = APB\Inc\Functions::arrangedPosts( get_posts( $this->query( $attributes ) ), $fImgSize, $metaDateFormat );

		ob_start(); ?>
		<div
			class='<?php echo esc_attr( $blockClassName ); ?>'
			id='apbAdvancedPosts-<?php echo esc_attr( $cId ); ?>'
			data-nonce='<?php echo esc_attr( wp_json_encode( wp_create_nonce( 'wp_ajax' ) ) ); ?>'
			data-attributes='<?php echo esc_attr( wp_json_encode( $attributes ) ); ?>'
			data-extra='<?php echo esc_attr( wp_json_encode( [ 'totalPosts' => count( $allPosts ) ] ) ); ?>'
		>
			<style>
				<?php echo wp_kses( APB\Inc\Style::generatedStyle( $attributes ), [] ); ?>
			</style>

			<div class='apbPostsMainPH'>
				<?php echo APB\Inc\Layout::layoutToggle( $attributes, $posts ); ?>
			</div>

			<div class='apbPostsMain'></div>
		</div>
		<?php return ob_get_clean();
	} // Render
}
new APBAdvancedPostBlock;

// Require Files
global $apb_bs;

if( $apb_bs->can_use_premium_feature() ) {
	require_once APB_PATH . '/inc/custom-post.php';
}