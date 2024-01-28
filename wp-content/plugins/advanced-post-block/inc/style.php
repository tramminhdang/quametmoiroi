<?php
namespace APB\Inc;

require_once plugin_dir_path( __FILE__ ) . '/getCSS.php';

// Generate Styles
class APBlockStyleGenerator {
	public $styles = [];
	public function addStyle( $selector, $styles ){
		if( array_key_exists( $selector, $this->styles ) ){
			$this->styles[$selector] = wp_parse_args( $this->styles[$selector], $styles );
		}else { $this->styles[$selector] = $styles; }
	}
	public function renderStyle(){
		$output = '';
		foreach( $this->styles as $selector => $style ){
			$new = '';
			foreach( $style as $property => $value ){
				if( $value == '' ){ $new .= $property; }else { $new .= " $property: $value;"; }
			}
			$output .= "$selector { $new }";
		}
		return $output;
	}
}

class Style{
	static function generatedStyle( $attributes ) {
		extract( $attributes );

		// Generate Styles
		$apbStyles = new APBlockStyleGenerator();
		$apbMobileStyles = new APBlockStyleGenerator();

		$mainSl = "#apbAdvancedPosts-$cId";
		$postSl = "$mainSl .apbPost";
		$sliderPostsSl = "$mainSl .apbSliderPosts";
		$postReadMoreSl = "$postSl .apbReadMore";
		$postTitleSl = "$postSl .apbTitle";
		$postMetaSl = "$postSl .apbMeta";
		$paginationSl = "$mainSl .apbPagination";

		$apbStyles->addStyle( "$paginationSl .apbPagePrev, $paginationSl .apbPageNumber, $paginationSl .apbPageNext", [
			'font-size' => '15px',
			GetCSS::getColorsCSS( $paginationColors ) => '',
			'padding' => GetCSS::getSpaceCSS( $paginationPadding ),
			'margin' => "0 calc( $paginationSpacing / 2 )"
		] );
		$apbStyles->addStyle( "$paginationSl .apbPagePrev:hover, $paginationSl .apbPageNumber:hover, $paginationSl .apbPageNext:hover, $paginationSl .apbActivePage", [
			GetCSS::getColorsCSS( $paginationHovColors ) => ''
		] );

		$apbStyles->addStyle("$postSl", [
			GetCSS::getBorderCSS( $border ) => ''
		] );
		$apbStyles->addStyle("$mainSl .apbDefault, $mainSl .apbSideImage", [
			'text-align' => $contentAlign,
			GetCSS::getBackgroundCSS( $contentBG ) => ''
		] );

		$apbStyles->addStyle("$postSl .apbText", [
			'padding' => GetCSS::getSpaceCSS( $contentPadding )
		] );
		$apbStyles->addStyle("$mainSl .apbOverlay .apbText", [
			GetCSS::getBackgroundCSS( $contentBG ) => '',
			'align-items' => 'left' === $contentAlign ? 'flex-start' : ( 'right' === $contentAlign ? 'flex-end' : ( 'center' === $contentAlign ? 'center' : 'stretch' ) )
		] );
		$apbStyles->addStyle("$postTitleSl, $postTitleSl a", [
			'text-align' => $contentAlign,
			'color' => $titleColor
		] );
		$apbStyles->addStyle("$postTitleSl", [
			'margin' => GetCSS::getSpaceCSS( $titleMargin )
		] );
		$apbStyles->addStyle("$postMetaSl", [
			'justify-content' => 'left' === $contentAlign ? 'flex-start' : ( 'right' === $contentAlign ? 'flex-end' : 'center' ),
			'color' => $metaTextColor,
			'margin' => GetCSS::getSpaceCSS( $metaMargin )
		] );
		$apbStyles->addStyle("$postMetaSl a", [
			'color' => $metaLinkColor
		] );
		$apbStyles->addStyle("$postMetaSl .dashicons", [
			'color' => $metaIconColor
		] );
		$apbStyles->addStyle("$postSl .apbThumb img, $postSl.apbOverlay img", [
			'object-fit' => $fImgFitting ?? 'cover'
		] );
		$apbStyles->addStyle("$postSl .apbThumbCats a", [
			GetCSS::getColorsCSS( $metaColorsOnImage ) => ''
		] );
		$apbStyles->addStyle("$postSl .apbExcerpt", [
			'text-align' => $excerptAlign,
			'color' => $excerptColor,
			'margin' => GetCSS::getSpaceCSS( $excerptMargin )
		] );
		$apbStyles->addStyle("$postReadMoreSl", [
			'text-align' => $readMoreAlign
		] );
		$apbStyles->addStyle("$postReadMoreSl a", [
			GetCSS::getColorsCSS( $readMoreColors ) => '',
			'padding' => GetCSS::getSpaceCSS( $readMorePadding ),
			GetCSS::getBorderCSS( $readMoreBorder ) => ''
		] );
		$apbStyles->addStyle("$postReadMoreSl a:hover", [
			GetCSS::getColorsCSS( $readMoreHovColors ) => ''
		] );

		$apbStyles->addStyle("$mainSl .apbGridPosts, $mainSl .apbGrid1Posts", [
			'grid-gap' => $rowGap .'px '. $columnGap .'px',
			'align-items' => false === $isContentEqualHight ? 'start' : 'initial'
		] );
		$apbStyles->addStyle("$sliderPostsSl, $sliderPostsSl .swiper-slide", [
			'min-height' => $sliderHeight
		] );
		$apbStyles->addStyle("$sliderPostsSl .swiper-pagination .swiper-pagination-bullet", [
			'background' => $sliderPageColor,
			'width' => $sliderPageWidth,
			'height' => $sliderPageHeight,
			GetCSS::getBorderCSS( $sliderPageBorder ) => ''
		] );
		$apbStyles->addStyle("$sliderPostsSl .swiper-button-prev, $sliderPostsSl .swiper-button-next", [
			'color' => $sliderPrevNextColor
		] );

		$apbMobileStyles->addStyle( "$paginationSl .apbPagePrev, $paginationSl .apbPageNumber, $paginationSl .apbPageNext", [
			'font-size' => '12px',
			'padding' => implode( ' ', array_map( function ( $value ) { return "calc( $value / 2 )"; }, explode( ' ', GetCSS::getSpaceCSS( $paginationPadding ) ) ) ),
			'margin' => "0 calc( $paginationSpacing / 4 )"
		] );

		ob_start();
			echo GetCSS::getTypoCSS( '', $titleTypo )['googleFontLink'];
			echo GetCSS::getTypoCSS( '', $metaTypo )['googleFontLink'];
			echo GetCSS::getTypoCSS( '', $excerptTypo )['googleFontLink'];
			echo GetCSS::getTypoCSS( '', $readMoreTypo )['googleFontLink'];
			echo GetCSS::getTypoCSS( "$postTitleSl, $postTitleSl a", $titleTypo )['styles'];
			echo GetCSS::getTypoCSS( "$postMetaSl, $postMetaSl *, $postSl .apbThumbCats", $metaTypo )['styles'];
			echo GetCSS::getTypoCSS( "$postSl .apbExcerpt", $excerptTypo )['styles'];
			echo GetCSS::getTypoCSS( "$postReadMoreSl a", $readMoreTypo )['styles'];
			echo wp_kses( $apbStyles->renderStyle(), [] );
			echo '@media (max-width: 576px) {'. wp_kses( $apbMobileStyles->renderStyle(), [] ) .'}';

			// Empty styles
			$apbStyles->styles = [];
			$apbMobileStyles->styles = [];

		return ob_get_clean();
	}
}