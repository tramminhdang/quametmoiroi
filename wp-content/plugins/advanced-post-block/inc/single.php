<?php
namespace APB\Inc;

class Single{
	static function featureImage( $attributes, $post ) {
		extract( $attributes );
		extract( $post );

		$spaceCats = $categories['space'];
		$thumbUrl = $thumbnail['url'];
		$thumbAlt = str_replace( [ "'", '"' ], '', $thumbnail['alt'] );
		$tab = $isLinkNewTab ? '_blank' : '_self';
		$imgEl = $isFImgLink ? "<a href='$link' target='$tab' rel='noreferrer' aria-label='$thumbAlt'><img src='$thumbUrl' alt='$thumbAlt' /></a>" : "<img src='$thumbUrl' alt='$thumbAlt' />";

		if( $isFImg && $thumbUrl ){
			ob_start(); ?>
			<figure class='apbThumb'>
				<?php echo wp_kses_post( $imgEl ); ?>

				<?php echo ( $isMeta && $isMetaCategory && 'image' === $metaCategoryIn && $spaceCats ) ? wp_kses_post( "<div class='apbThumbCats'>$spaceCats</div>" ) : ''; ?>
			</figure>
		<?php return ob_get_clean();
		}else{
			return '';
		}
	} // Feature Image

	static function titleMeta( $attributes, $post ) {
		extract( $attributes );
		$elementsSort = $elementsSort ?? [ 'title', 'meta' ];
	
		$output = '';
		foreach ( $elementsSort as $index => $el ) {
			if ( 'title' === $el ) {
				$output .= self::title( $attributes, $post );
			} else {
				$output .= self::meta( $attributes, $post );
			}
		}
	
		return $output;
	}

	static function title( $attributes, $post ) {
		extract( $attributes );
		extract( $post );

		$tab = $isLinkNewTab ? '_blank' : '_self';
		$titleEl = $isTitleLink ? "<a href='$link' target='$tab' rel='noreferrer' aria-label='$title'>$title</a>" : $title;

		if ( $isTitle ) {
			ob_start(); ?>
			<h3 class='apbTitle'>
				<?php echo wp_kses_post( $titleEl ); ?>
			</h3>
			<?php return ob_get_clean();
		} else {
			return '';
		}
	} // Title

	static function meta( $attributes, $post ) {
		extract( $attributes );

		if ( $isMeta ) {
			ob_start(); ?>
			<div class='apbMeta'>
				<?php echo self::metaAuthor( $attributes, $post ) . self::metaDate( $attributes, $post ) . self::metaCategories( $attributes, $post ) . self::metaReadTime( $attributes, $post ) . self::metaComment( $attributes, $post ); ?>
			</div>
			<?php return ob_get_clean();
		} else {
			return '';
		}
	} // Meta Data

	static function metaAuthor( $attributes, $post ) {
		extract( $attributes );
		extract( $post );

		$isMetaAuthorLink = $isMetaAuthorLink ?? true;
		$aUrl = $author['link'];
		$aName = $author['name'];

		if ( $isMetaAuthor && $aName ) {
			$iconEl = $metaAuthorIcon ? "<img src='$metaAuthorIcon' alt='Author' />" : "<span class='dashicon dashicons dashicons-admin-users'></span>";
			$authorEl = !$isMetaAuthorLink ? "<span>$aName</span>" : "<a href='$aUrl' rel='author' aria-label='$aName'>$aName</a>";

			$metaAuthorEl = "<span>$iconEl $authorEl</span>";

			ob_start();
			echo wp_kses_post( $metaAuthorEl );
			return ob_get_clean();
		} else {
			return '';
		}
	} // Meta Author

	static function metaDate( $attributes, $post ) {
		extract( $attributes );

		$metaDateFormat = $metaDateFormat ?? 'M j, Y';
		$date = get_the_date( $metaDateFormat, $post['id'] );

		if ( $isMetaDate && $date ) {
			$iconEl = $metaDateIcon ? "<img src='$metaDateIcon' alt='Date' />" : "<span class='dashicon dashicons dashicons-calendar'></span>";

			$metaDateEl = "<span>$iconEl <span>$date</span></span>";

			ob_start();
			echo wp_kses_post( $metaDateEl );
			return ob_get_clean();
		} else {
			return '';
		}
	} // Meta Date

	static function metaCategories( $attributes, $post ) {
		extract( $attributes );
		extract( $post );

		$comaCats = $categories['coma'];

		if ( $isMetaCategory && 'content' === $metaCategoryIn && $comaCats ) {
			$iconEl = $metaCategoryIcon ? "<img src='$metaCategoryIcon' alt='Category' />" : "<span class='dashicon dashicons dashicons-category'></span>";

			$metaCategoryEl = "<span>$iconEl <span>$comaCats</span></span>";

			ob_start();
			echo wp_kses_post( $metaCategoryEl );
			return ob_get_clean();
		} else {
			return '';
		}
	} // Meta Categories

	static function metaReadTime( $attributes, $post ) {
		extract( $attributes );
		extract( $post );

		if ( $isMetaReadTime ) {
			$readTimeText = $isMetaReadTimeSec ? $readTime['min'] .':'. $readTime['sec'] : $readTime['min'];

			$iconEl = $metaReadTimeIcon ? "<img src='$metaReadTimeIcon' alt='Reding Time' />" : "<span class='dashicon dashicons dashicons-clock'></span>";
			$readTimeContent = $readTimeText .' '. $metaReadTimeLabel;

			$metaReadTimeEl = "<span>$iconEl <span>$readTimeContent</span></span>";

			ob_start();
			echo wp_kses_post( $metaReadTimeEl );
			return ob_get_clean();
		} else {
			return '';
		}
	} // Meta Categories

	static function metaComment( $attributes, $post ) {
		extract( $attributes );
		extract( $post );

		if ( $isMetaComment && 'open' === $commentStatus ) {
			$commentUrl = esc_url( $link ) . '/#comments';
			
			$iconEl = $metaCommentIcon ? "<img src='$metaCommentIcon' alt='Comment' />" : "<span class='dashicon dashicons dashicons-admin-comments'></span>";

			$metaCommentEl = "<span>$iconEl <a href='$commentUrl' target='_blank' rel='noreferrer' aria-label='Comments of $title'>$commentCount</a></span>";

			ob_start();
			echo wp_kses_post( $metaCommentEl );
			return ob_get_clean();
		} else {
			return '';
		}
	} // Meta Comment

	static function excerpt( $attributes, $post ) {
		extract( $attributes );
		extract( $post );

		$isEllipsisOnExcerpt = $isEllipsisOnExcerpt ?? false;
		$finalExcerpt = ( !$isExcerptFromContent && $excerpt ) ? $excerpt : $content;
		$ellipsis = ( $isEllipsisOnExcerpt && ( Functions::strLength( $finalExcerpt ) > $excerptLength ) ) ? '...' : '';

		if ( $isExcerpt && $finalExcerpt ) {
			ob_start(); ?>
			<div class='apbExcerpt apbInnerContent'>
				<?php echo wp_kses( Functions::truncate( $finalExcerpt, $excerptLength ) . $ellipsis, [] ); ?>

				<p class='read-more'>
					<a href='<?php echo esc_url( $link ); ?>' aria-label='Read More'><?php echo __( 'Read More &raquo;', 'advanced-post-block' ); ?></a>
				</p>
			</div>
			<?php return ob_get_clean();
		} else {
			return '';
		}
	} // Excerpt

	static function readMore( $attributes, $post ) {
		extract( $attributes );
		extract( $post );

		$tab = $isLinkNewTab ? '_blank' : '_self';
		$readMoreEl = "<a href='$link' target='$tab' rel='noreferrer' aria-label='$readMoreLabel'>$readMoreLabel</a>";

		if ( $isReadMore ) {
			ob_start(); ?>
			<div class='apbReadMore <?php echo esc_attr( $readMorePosition ?? 'auto' ); ?>'>
				<?php echo wp_kses_post( $readMoreEl ); ?>
			</div>
			<?php return ob_get_clean();
		} else {
			return '';
		}
	} // Read More
}