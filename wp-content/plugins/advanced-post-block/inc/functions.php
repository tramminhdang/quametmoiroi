<?php
namespace APB\Inc;

class Functions{	
	static function truncate( $str, $nu_words ) {
		return $str ? implode( ' ', array_slice( explode( ' ', $str ), 0, $nu_words ) ) : '';
	}

	static function filterNaN( $array ) {
		return array_filter( $array, function( $id ) {
			return $id && is_numeric( $id );
		});
	}

	static function strLength( $str ) {
		return $str ? count( explode( ' ', $str ) ) : 0;
	}

	static function wordCount( $content ) {
		return $content ? count( preg_split( '/[\s]+/', strip_tags( $content ) ) ) : 0;
	}

	static function arrangedPosts ( $posts, $fImgSize = 'full', $metaDateFormat = 'M j, Y' ) {
		$arranged = [];

		foreach( $posts as $post ){
			$id = $post->ID;
			$content = preg_replace( '/(<([^>]+)>)/i', '', $post->post_content ); // Can use strip_tags also
			$contentWords = self::wordCount( $content );

			$thumbnail = [
				'url' => get_the_post_thumbnail_url( $post, $fImgSize ),
				'alt' => get_post_meta( get_post_thumbnail_id( $id ), '_wp_attachment_image_alt', true )
			];

			$arranged[] = [
				'id' => $id,
				'link' => get_permalink( $post ),
				'name' => $post->post_name,
				'thumbnail' => $thumbnail,
				'title' => $post->post_title,
				'excerpt' => $post->post_excerpt,
				'content' => $content,
				'author' => [
					'name' => get_the_author_meta( 'display_name', $post->post_author ),
					'link' => get_author_posts_url( $post->post_author )
				],
				'date' => $post->post_date,
				'date' => get_the_date( $metaDateFormat, $id ),
				'dateGMT' => $post->post_date_gmt,
				'modifiedDate' => $post->post_modified,
				'modifiedDateGMT' => $post->post_modified_gmt,
				'commentCount' => $post->comment_count,
				'commentStatus' => $post->comment_status,
				'categories' => [
					'coma' => get_the_category_list( esc_html__( ', ' ), '', $id ),
					'space' => get_the_category_list( esc_html__( ' ' ), '', $id )
				],
				'readTime' => [
					'min' => floor( $contentWords / 200 ),
					'sec' => floor( $contentWords % 200 / ( 200 / 60 ) )
				],
				'status' => $post->post_status
			];
		}

		return $arranged;
	}

	static function classNames( ...$args ) {
		$classes = array_reduce( $args, function( $acc, $arg ) {
			if ( is_string( $arg ) ) {
				$acc[] = $arg;
			} else if ( is_array( $arg ) ) {
				foreach ( $arg as $key => $value ) {
					if ( $value ) {
						$acc[] = $key;
					}
				}
			}
		  return $acc;
		}, [] );

		return implode( ' ', $classes );
	}
}