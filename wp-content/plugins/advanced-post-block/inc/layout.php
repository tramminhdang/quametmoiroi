<?php
namespace APB\Inc;

require_once plugin_dir_path( __FILE__ ) . '/single.php';

class Layout{
	static function layoutToggle( $attributes, $posts ){
		extract( $attributes );

		$colD = $columns['desktop'];
		$colT = $columns['tablet'];
		$colM = $columns['mobile'];
		$gridClass = "apbGridPosts columns-$colD columns-tablet-$colT columns-mobile-$colM";
		$masonryClass = "apbMasonryPosts cols-$colD cols-tablet-$colT cols-mobile-$colM";

		ob_start();
		switch ( $layout ) {
			case 'grid':
			case 'masonry': ?>
				<div class='<?php echo esc_attr( $gridClass ); ?>'>
					<?php echo Layout::mapPosts( $attributes, $posts ); ?>
				</div>
			<?php break;

			case 'grid1': ?>
				<div class='apbGrid1Posts'>
					<?php echo Layout::mapPosts( $attributes, $posts ); ?>
				</div>
			<?php break;

			case 'slider': ?>
				<div class='apbSliderPosts'>
					<div class='swiper-wrapper'>
						<?php Layout::mapPosts( $attributes, $posts ); ?>
					</div>

					<?php echo $sliderIsPage ? "<div class='swiper-pagination'></div>" : ''; ?>
					<?php echo $sliderIsPrevNext ? "<div class='swiper-button-prev'></div><div class='swiper-button-next'></div>" : ''; ?>
				</div>
			<?php break;

			case 'ticker': ?>
				<div class='apbTickerPosts'>
					<div>
						<?php Layout::mapPosts( $attributes, $posts ); ?>
					</div>
				</div>
			<?php break;

			default:
				echo '';
				break;
		}
		return ob_get_clean();
	} // Layout Toggle

	static function mapPosts( $attributes, $posts ){
		extract( $attributes );
		
		foreach ( $posts as $post ) {
			extract( $post );

			$postClass = Functions::classNames( 'apbPost', "apbPost-$id", $attributes['content']['height'] . 'ContentHeight', [
				'hasThumbnail' => $thumbnail['url'],
				'swiper-slide' => 'slider' === $layout
			] );

			switch ( $subLayout ) {
				case 'default':
				case 'title-meta':
					echo self::default( $attributes, $post, $postClass );
					break;
				case 'left-image':
				case 'right-image':
					echo self::sideImage( $attributes, $post, $postClass );
					break;
				case 'overlay-content':
				case 'overlay-content-hover':
				case 'overlay-box':
				case 'overlay-content-box':
				case 'overlay-half-content':
					echo self::overlay( $attributes, $post, $postClass );
					break;
				default:
					echo '<p>' . __( 'Please, select a sub layout', 'advanced-post-block' ) . '</p>';
					break;
			}
		}
	} // Map Posts

	static function default( $attributes, $post, $postClass ) {
		extract( $attributes );

		$titleMetaFilter = 'title-meta' !== $subLayout ? Single::excerpt( $attributes, $post ) . Single::readMore( $attributes, $post ) : '';

		$defaultClass = Functions::classNames( $postClass, 'apbDefault' );

		ob_start(); ?>
		<article class='<?php echo esc_attr( $defaultClass ); ?>'>
			<?php echo Single::featureImage( $attributes, $post ); ?>

			<div class='apbText'>
				<?php echo Single::titleMeta( $attributes, $post ) . $titleMetaFilter; ?>
			</div>
		</article>
		<?php return ob_get_clean();
	} // Default layout

	static function sideImage( $attributes, $post, $postClass ) {
		extract( $attributes );
		extract( $post );

		$isLeftImg = 'left-image' === $subLayout;
		$isRightImg = 'right-image' === $subLayout;

		$sideImageClass = Functions::classNames( $postClass, 'apbSideImage', [
			'grid' => $isFImg && $thumbnail['url'],
			'leftImage' => $isLeftImg,
			'rightImage' => $isRightImg
		] );
		
		ob_start(); ?>
		<article class='<?php echo esc_attr( $sideImageClass ); ?>'>
			<?php echo $isLeftImg ? Single::featureImage( $attributes, $post ) : ''; ?>

			<div class='apbText'>
				<?php echo Single::titleMeta( $attributes, $post ) . Single::excerpt( $attributes, $post ) . Single::readMore( $attributes, $post ); ?>
			</div>

			<?php echo $isRightImg ? Single::featureImage( $attributes, $post ) : ''; ?>
		</article>
		<?php return ob_get_clean();
	} // Side Image layout

	static function overlay( $attributes, $post, $postClass ) {
		extract( $attributes );
		extract( $post );

		$thumbUrl = $thumbnail['url'];
		$thumbAlt = $thumbnail['alt'];
		$imgEl = $thumbUrl ? "<img src='$thumbUrl' alt='$thumbAlt' />" : '';

		$overlayClass = Functions::classNames( $postClass, 'apbOverlay', [
			'apbOverlayHover' => 'overlay-content-hover' === $subLayout && $thumbUrl,
			'apbOverlayBox' => 'overlay-box' === $subLayout || 'overlay-content-box' === $subLayout,
			'apbOverlayHalfContent' => 'overlay-half-content' === $subLayout
		] );

		ob_start(); ?>
		<article class='<?php echo esc_attr( $overlayClass ); ?>'>
			<?php echo wp_kses_post( $imgEl ); ?>

			<div class='apbText'>
				<?php echo Single::titleMeta( $attributes, $post ); ?>

				<?php echo 'overlay-box' !== $subLayout && 'overlay-half-content' !== $subLayout ? Single::excerpt( $attributes, $post ) . Single::readMore( $attributes, $post ) : ''; ?>
			</div>
		</article>
		<?php return ob_get_clean();
	} // Overlay layout
}