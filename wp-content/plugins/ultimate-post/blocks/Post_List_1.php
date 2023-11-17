<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Post_List_1{

    public function __construct() {
        add_action('init', array($this, 'register'));
    }

    public function get_attributes() {

        return array(
            'blockId' =>  '',
            'previewImg' =>  '',
            
            /*============================
                General Setting
            ============================*/
            'layout' =>  'layout1',
            'gridStyle' =>  'style1',
            'columns' =>  (object)['lg' =>'2', 'xs' => '2'],
            'columnGridGap' =>  (object)['lg' =>'30', 'xs' => '16', 'unit' =>'px'],
            'rowSpace' =>  (object)['lg'=>'30', 'xs' => '16'],
            'titleShow' =>  true,
            'titleStyle' =>  'none',
            'titleAnimColor' =>  'black',
            'headingShow' =>  true,
            'excerptShow' =>  true,
            'catShow' =>  true,
            'metaShow' =>  true,
            'showImage' =>  true,
            'filterShow' =>  false,
            'paginationShow' =>  true,
            'readMore' =>  true,
            'contentTag' =>  'div',
            'openInTab' =>  false,
            'notFoundMessage' =>  'No Post Found',

            /*============================
                Query Setting
            ============================*/
            'queryQuick' =>  '',
            'queryNumPosts' =>  (object)['lg'=>6],
            'queryNumber' =>  6,
            'queryType' =>  'post',
            'queryTax' =>  'category',
            'queryTaxValue' =>  '[]',
            'queryRelation' =>  'OR',
            'queryOrderBy' =>  'date',
            'metaKey' =>  'custom_meta_key',
            'queryOrder' =>  'desc',
            // Include Remove from Version 2.5.4
            'queryInclude' =>  '',
            'queryExclude' =>  '[]',
            'queryAuthor' =>  '[]',
            'queryOffset' =>  '0',
            'queryExcludeTerm' =>  '[]',
            'queryExcludeAuthor' =>  '[]',
            'querySticky' =>  true,
            'queryUnique' =>  '',
            'queryPosts' =>  '[]',
            'queryCustomPosts' =>  '[]',

            /*============================
                Heading Style
            ============================*/
            'headingText' =>  'Post List #1',
            'headingURL' =>  '',
            'headingBtnText' =>   'View More',
            'headingStyle' =>  'style9',
            'headingTag' =>  'h2',
            'headingAlign' =>   'left',
            'headingTypo' =>   (object)['openTypography' => 1,'size' => (object)['lg' => '20', 'unit' => 'px'], 'height' => (object)['lg' => '', 'unit' => 'px'],'decoration' => 'none', 'transform' => '', 'family'=>'','weight'=>'700'],
            'headingColor' =>   '#0e1523',
            'headingBorderBottomColor' =>   '#0e1523',
            'headingBorderBottomColor2' =>   '#e5e5e5',
            'headingBg' =>   '#037fff',
            'headingBg2' =>   '#e5e5e5',
            'headingBtnTypo' =>   (object)['openTypography' => 1,'size' => (object)['lg' => '14', 'unit' => 'px'], 'height' => (object)['lg' => '', 'unit' => 'px'],'decoration' => 'none','family'=>''],
            'headingBtnColor' =>   '#037fff',
            'headingBtnHoverColor' =>   '#0a31da',
            'headingBorder' =>  '3',
            'headingSpacing' =>  (object)['lg'=>20, 'unit'=>'px'],
            'headingRadius' =>  (object)['lg' =>(object)['top' => '','bottom' => '','left' => '', 'right' => '', 'unit' =>'px']],
            'headingPadding' =>  (object)['lg' =>(object)['unit' =>'px']],
            'subHeadingShow' =>  false,
            'subHeadingText' =>  'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ut sem augue. Sed at felis ut enim dignissim sodales.',
            'subHeadingTypo' =>  (object)['openTypography'=>1,'size'=>(object)['lg'=>'16', 'unit'=>'px'], 'spacing'=>(object)[ 'lg'=>'0', 'unit'=>'px'], 'height'=>(object)[ 'lg'=>'27', 'unit'=>'px'],'decoration'=>'none','transform' => '','family'=>'','weight'=>'500'],
            'subHeadingColor' =>   '#919191',
            'subHeadingSpacing' =>  (object)['lg' =>(object)['top' => '8', 'unit' =>'px']],

            /*============================
                Title Style
            ============================*/
            'titleTag' =>  'h3',
            'titlePosition' =>  true,
            'titleColor' =>  '#141414',
            'titleHoverColor' =>  '#828282',
            'titleTypo' =>  (object)['openTypography'=>1,'size'=>(object)['lg'=>'24', 'unit'=>'px'], 'spacing'=>(object)[ 'lg'=>'0', 'unit'=>'px'], 'height'=>(object)[ 'lg'=>'30', 'unit'=>'px'],'decoration'=>'none','transform' => '','family'=>'','weight'=>'500'],
            'postListTypo' =>   (object)['openTypography' => 1,'size' => (object)['lg' => '28', 'unit' => 'px'], 'height' => (object)['lg' => '36', 'unit' => 'px'],'decoration' => 'none', 'transform' => '', 'family'=>'','weight'=>'500'],
            'titlePadding' =>  (object)['lg'=>(object)['top'=> '10', 'unit'=>'px'], 'xs'=>(object)['top'=> '0', 'unit'=>'px']],
            'titleLength' =>  0,

            /*============================
                Meta Style
            ============================*/
            'metaPosition' =>  'top',
            'metaStyle' =>  'icon',
            'metaSeparator' =>  '',
            'authorLink' =>  true,
            'metaList' =>  '["metaAuthor","metaDate","metaRead"]',
            'metaMinText' =>  'min read',
            'metaAuthorPrefix' =>  'By',
            'metaDateFormat' =>  'M j, Y',
            'metaTypo' =>  (object)['openTypography' => 1,'size' => (object)['lg' =>12, 'unit' =>'px'],'height' => (object)['lg' =>20, 'unit' =>'px'], 'transform' => 'capitalize', 'decoration' => 'none','family'=>''],
            'metaColor' =>  '#919191',
            'metaHoverColor' =>  '#000',
            'metaSpacing' =>  (object)['lg' =>'10', 'unit' =>'px'],
            'metaMargin' =>  (object)['lg' =>(object)['top' => '15','bottom' => '15', 'left'=>'','right'=>'', 'unit' =>'px'], 'xs' =>(object)['top' => '10', 'bottom' => '10', 'unit' =>'px']],
            'metaPadding' =>  (object)['lg' =>(object)['top' => '','bottom' => '', 'left'=>'','right'=>'', 'unit' =>'px']],
            'metaBorder' =>  (object)['openBorder'=>0, 'width' => (object)['top' => 1, 'right' => '0', 'bottom' => '0', 'left' => '0'],'color' => '#009fd4','type' => 'solid'],
            'metaBg' =>  '',

            /*============================
                Category Style
            ============================*/
            'maxTaxonomy'=>  '30',
            'taxonomy' =>  'category',
            'catStyle' =>  'classic',
            'catPosition' =>  'aboveTitle',
            'customCatColor' =>  false,
            'seperatorLink' =>  admin_url( 'edit-tags.php?taxonomy=category' ),
            'onlyCatColor' =>  false,
            'catLineWidth' =>  (object)['lg'=>'20'],
            'catLineSpacing' =>  (object)['lg'=>'30'],
            'catLineColor' =>  '#037fff',
            'catLineHoverColor' =>  '#828282',
            'catTypo' =>  (object)['openTypography' => 1, 'size' => (object)['lg' =>14, 'unit' =>'px'], 'height' => (object)['lg' =>25, 'unit' =>'px'], 'spacing' => (object)['lg' =>0, 'unit' =>'px'], 'transform' => '', 'weight' => '400', 'decoration' => 'none','family'=>'' ],
            'catColor' =>  '#037fff',
            'catBgColor' =>  (object)['openColor' => 1,'type' => 'color', 'color' => ''],
            'catBorder' =>  (object)['openBorder'=>0, 'width' => (object)[ 'top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid' ],
            'catRadius' =>  (object)['lg' =>'', 'unit' =>'px'],
            'catHoverColor' =>  '#828282',
            'catBgHoverColor' =>  (object)['openColor' => 1, 'type' => 'color', 'color' => ''],
            'catHoverBorder' =>  (object)['openBorder'=>0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid'],
            'catSacing' =>  (object)['lg' =>(object)['bottom' => '', 'unit' =>'px']],
            'catPadding' =>  (object)['lg' =>(object)['top' => "0",'bottom' => "0",'left' => "0",'right' => "0", 'unit' =>'px']],

            /*============================
                Image Style
            ============================*/
            'imgCrop' =>  'full',
            'imgCropSmall' =>  (ultimate_post()->get_setting('disable_image_size') == 'yes' ? 'full' : 'ultp_layout_square'),
            'imgWidth' =>  (object)['lg' =>'', 'ulg' =>'%'],
            'imgHeight' =>  (object)['lg' =>'', 'unit' =>'px'],
            'imageScale' =>  'cover',
            'imgAnimation' =>  'none',
            'imgGrayScale' =>  (object)['lg' =>'0', 'ulg' =>'%', 'unit' =>'%'],
            'imgHoverGrayScale' =>  (object)['lg' =>'0', 'unit' =>'%'],
            'imgRadius' =>  (object)['lg' =>'', 'unit' =>'px'],
            'imgHoverRadius' =>  (object)['lg' =>'', 'unit' =>'px'],
            'imgShadow' =>  (object)['openShadow' => 0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4'],
            'imgHoverShadow' =>  (object)['openShadow' => 0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4'],
            'imgSpacing' =>  (object)['lg'=>'0'],
            'imgOverlay' =>  false,
            'imgOverlayType' =>  'default',
            'overlayColor' =>  (object)['openColor' => 1, 'type' => 'color', 'color' => '#0e1523'],
            'imgOpacity' =>  .7,
            'fallbackEnable' =>  true,
            'fallbackImg' =>  '',
            'imgSrcset' =>  false,
            'imgLazy' =>  false,

            /*============================
                Video Style
            ============================*/
            'vidIconEnable' =>  true,
            'popupAutoPlay' =>  true,
            'vidIconPosition' =>  'center',
            'popupIconColor' =>   '#fff',
            'popupHovColor' =>   '#d2d2d2',
            'iconSize' =>  (object)['lg'=>'70', 'sm'=> '50', 'xs'=> '50', 'unit' => 'px'],
            // by default should be off
            'enablePopup' =>  false,
            'popupWidth' =>  (object)['lg'=>'70'],
            'enablePopupTitle' =>  true,
            'popupTitleColor' =>   '#fff',
            'closeIconSep' =>   '#fff',
            'closeIconColor' =>   '#fff',
            'closeHovColor' =>   '#8f8f8f',
            'closeSize' =>  (object)['lg'=>'70', 'unit' => 'px'],

            /*============================
                Content Style
            ============================*/
            'contentAlign' =>  "left",
            'contentWrapBg' =>  '',
            'contentWrapHoverBg' => '',
            'contentWrapBorder' =>  (object)['openBorder'=>0, 'width' => (object)[ 'top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid' ],
            'contentWrapHoverBorder' =>  (object)['openBorder'=>0, 'width' => (object)[ 'top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid' ],
            'contentWrapRadius' =>  (object)['lg' =>(object)['top' => '','bottom' => '', 'unit' =>'px']],
            'contentWrapHoverRadius' =>  (object)['lg' =>(object)['top' => '','bottom' => '', 'unit' =>'px']],
            'contentWrapShadow' =>  (object)['openShadow' => 1, 'width' => (object)['top' => 0, 'right' => 0, 'bottom' => 20, 'left' => 0],'color' => 'rgba(0, 0, 0, 0.06)'],
            'contentWrapHoverShadow' =>  (object)['openShadow' => 0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4'],

            'contentWrapInnerPadding' =>  (object)['lg' =>(object)['top' => '30','bottom' => '40', 'left'=>'30','right'=>'30', 'unit' =>'px'], 'xs' =>(object)['top' => '16','bottom' => '16', 'left'=>'16','right'=>'16', 'unit' =>'px']],
            'contentWrapPadding' =>  (object)['lg' =>(object)['top' => '','bottom' => '', 'left'=>'','right'=>'', 'unit' =>'px']],

            /*============================
                Filter Style
            ============================*/
            'filterBelowTitle' =>  false,
            'filterAlign' =>  (object)['lg' =>''],
            'filterType' =>  'category',
            'filterText' =>  'all',
            'filterValue' =>  '[]',
            'fliterTypo' =>  (object)['openTypography' => 1,'size' => (object)['lg' =>14, 'unit' =>'px'],'height' => (object)['lg' =>22, 'unit' =>'px'], 'decoration' => 'none','family'=>'','weight'=>500],
            'filterColor' =>  '#0e1523',
            'filterHoverColor' =>   '#828282',
            'filterBgColor' => '',
            'filterHoverBgColor' => '',
            'filterBorder' =>  (object)['openBorder'=>0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid'],
            'filterHoverBorder' =>  (object)['openBorder'=>0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid'],
            'filterRadius' =>  (object)['lg' =>'', 'unit' =>'px'],
            'fliterSpacing' =>  (object)['lg' =>(object)['top' => '','bottom' => '', 'right' => '', 'left' => '20', 'unit' =>'px']],
            'fliterPadding' =>  (object)['lg' =>(object)['top' => '','bottom' => '', 'unit' =>'px']],
            'filterDropdownColor' =>   '#0e1523',
            'filterDropdownHoverColor' =>   '#037fff',
            'filterDropdownBg' =>   '#fff',
            'filterDropdownRadius' =>  (object)['lg'=>'0'],
            'filterDropdownPadding' =>  (object)['lg' =>(object)['top' => '15','bottom' => '15','left' => '20','right' => '20', 'unit' =>'px']],
            'filterMobile' =>   true,
            'filterMobileText' =>  'More',
            
            /*============================
                Entry Heading Style
            ============================*/
            'headerWidth' =>  (object)['lg' =>'70', 'xs' =>'85', 'unit' =>'%'],
            'headerWrapBg' =>  '#fff',
            'headerWrapHoverBg' =>  '',
            'headerWrapBorder' =>  (object)['openBorder'=>0, 'width' => (object)[ 'top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid' ],
            'headerWrapHoverBorder' =>  (object)['openBorder'=>0, 'width' => (object)[ 'top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid' ],
            'headerWrapRadius' =>  (object)['lg' =>(object)['top' => '','bottom' => '', 'unit' =>'px']],
            'headerWrapHoverRadius' =>  (object)['lg' =>(object)['top' => '','bottom' => '', 'unit' =>'px']],
            'headerSpaceX' =>  (object)['lg' =>'20', 'unit' =>'px'],
            'headerSpaceY' =>  (object)['lg' =>'30'],
            'headerWrapPadding' =>  (object)['lg' =>(object)['top' => '20','bottom' => '20', 'left'=>'20','right'=>'24', 'unit' =>'px']],

            /*============================
                Pagination Style
            ============================*/
            'paginationType' =>  'pagination',
            'loadMoreText' =>  'Load More',
            'paginationText' =>  'Previous|Next',
            'paginationNav' =>  'textArrow',
            'paginationAjax' =>  true,
            'navPosition' =>  'topRight',
            'pagiAlign' =>   (object)['lg' =>'left'],
            'pagiTypo' =>  (object)['openTypography' => 1,'size' => (object)['lg' =>14, 'unit' =>'px'],'height' => (object)['lg' =>20, 'unit' =>'px'], 'decoration' => 'none','family'=>''],
            'pagiArrowSize' =>  (object)['lg'=>'14'],
            'pagiColor' =>  '#fff',
            'pagiBgColor' =>  (object)['openColor' => 1, 'type' => 'color', 'color' => '#0e1523'],
            'pagiBorder' =>  (object)['openBorder'=>0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid'],
            'pagiShadow' =>  (object)['openShadow' => 0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4'],
            'pagiRadius' =>  (object)['lg' =>(object)['top' => '2','bottom' => '2','left' => '2','right' => '2', 'unit' =>'px']],
            'pagiHoverColor' =>  '#fff',
            'pagiHoverbg' =>  (object)['openColor' => 1, 'type' => 'color', 'color' => '#037fff','replace'=>1],
            'pagiHoverBorder' =>  (object)['openBorder'=>0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid'],
            'pagiHoverShadow' =>  (object)['openShadow' => 0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4'],
            'pagiHoverRadius' =>  (object)['lg' =>(object)['top' => '2','bottom' => '2','left' => '2','right' => '2', 'unit' =>'px']],
            'pagiPadding' =>  (object)['lg' =>(object)['top' => '8','bottom' => '8','left' => '14','right' => '14', 'unit' =>'px']],
            'navMargin' =>  (object)['lg' =>(object)['top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' =>'px']],
            'pagiMargin' =>  (object)['lg' =>(object)['top' => '30', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' =>'px']],

            /*============================
                Excerpt Style
            ============================*/
            'showSeoMeta' => false,
            'showFullExcerpt' =>  false,
            'fullExcerptLg' =>  false,
            'excerptLimit' =>  25,
            'excerptLimitLg' =>  '70',
            'excerptColor' =>  '#4a4a4a',
            'excerptTypo' =>  (object)['openTypography' => 1,'size' => (object)['lg' =>14, 'unit' =>'px'],'height' => (object)['lg' => 21, 'unit' =>'px'], 'decoration' => 'none','family'=>''],
            'excerptPadding' =>  (object)['lg' =>(object)['top' => '20','bottom' => '', 'unit' =>'px'], 'xs' =>(object)['top' => '14','bottom' => '', 'unit' =>'px']],
            'excerptPadddingLg' =>  (object)['lg' =>(object)['top' => '0','bottom' => '', 'unit' =>'px']],
            'responsiveExcerpt' =>  false,
            'excerptLimitLg' =>  '70',

            /*============================
                Read More Style
            ============================*/
            'readMoreText' =>  '',
            'readMoreIcon' =>  '',
            'readMoreTypo' =>  (object)['openTypography' => 1, 'size' => (object)['lg' =>12, 'unit' =>'px'], 'height' => (object)['lg' =>'', 'unit' =>'px'], 'spacing' => (object)['lg' => '', 'unit' =>'px'], 'transform' => 'uppercase', 'weight' => '500', 'decoration' => 'underline','family'=>'' ],
            'readMoreIconSize' =>  (object)['lg' =>'', 'unit' =>'px'],
            'readMoreColor' =>  '#141414',
            'readMoreBgColor' =>  (object)['openColor' => 0,'type' => 'color', 'color' => ''],
            'readMoreBorder' =>  (object)['openBorder'=>0, 'width' => (object)[ 'top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid' ],
            'readMoreRadius' =>  (object)['lg' =>'', 'unit' =>'px'],
            'readMoreHoverColor' =>  '#0c32d8',
            'readMoreBgHoverColor' =>  (object)['openColor' => 0, 'type' => 'color', 'color' => ''],
            'readMoreHoverBorder' =>  (object)['openBorder'=>0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid'],
            'readMoreHoverRadius' =>  (object)['lg' =>'', 'unit' =>'px'],
            'readMoreSacing' =>  (object)['lg' =>(object)['top' => 20, 'bottom' => '','left' => '','right' => '', 'unit' =>'px'], 'xs' =>(object)[ 'top' => 10, 'bottom' => 0, 'unit' =>'px']],
            'readMorePadding' =>  (object)['lg' =>(object)['top' => '','bottom' => '','left' => '','right' => '', 'unit' =>'px']],

            /*============================
                Separator Style
            ============================*/
            'separatorShow' =>  false,
            'septColor' =>  '#c7c7c7',
            'septStyle' =>  'dashed',
            'septSize' =>  (object)['lg'=>'1'],

            /*============================
                Advance Wrapper Style
            ============================*/
            'loadingColor' =>  '#000',
            'wrapBg' =>  (object)['openColor' => 0, 'type' => 'color', 'color' => '#f5f5f5'],
            'wrapBorder' =>  (object)['openBorder'=>0, 'width' =>(object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid'],
            'wrapShadow' =>  (object)['openShadow' => 0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4'],
            'wrapRadius' =>  (object)['lg' =>'', 'unit' =>'px'],
            'wrapHoverBackground' =>  (object)['openColor' => 0, 'type' => 'color', 'color' => '#037fff'],
            'wrapHoverBorder' =>  (object)['openBorder'=>0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid'],
            'wrapHoverRadius' =>  (object)['lg' =>'', 'unit' =>'px'],
            'wrapHoverShadow' =>  (object)['openShadow' => 0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4'],
            'wrapMargin' =>  (object)['lg' =>(object)['top' => '','bottom' => '', 'unit' =>'px']],
            'wrapOuterPadding' =>  (object)['lg' =>(object)['top' => '','bottom' => '','left' => '', 'right' => '', 'unit' =>'px']],
            'wrapInnerPadding' =>  (object)['lg' =>(object)['unit' =>'px']],
            'advanceId' =>  '',
            'advanceZindex' =>  '',
            'hideExtraLarge' =>  false,
            'hideTablet' =>  false,
            'hideMobile' =>  false,
            'advanceCss' =>  '',
            'currentPostId' =>  '',
        );
    }

    public function register() {
        register_block_type( 'ultimate-post/post-list-1',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'render_callback' =>  array($this, 'content')
            )
        );
    }

    public function content($attr, $noAjax) {
        $attr = wp_parse_args($attr, $this->get_attributes());
        $attr['queryUnique'] = isset( $attr['queryUnique']) ? $attr['queryUnique']:'';
        global $unique_ID;
        
        if (!$noAjax) {
            $paged = is_front_page() ? get_query_var('page') : get_query_var('paged');
            $attr['paged'] = $paged ? $paged : 1;
        }
        if($attr['queryUnique'] && isset($attr['savedQueryUnique'])) {
            $unique_ID = $attr['savedQueryUnique'];
        }
        $block_name = 'post-list-1';
        $wraper_before = $wraper_after = $post_loop = '';
        $attr['queryNumber'] = ultimate_post()->get_post_number(6, $attr['queryNumber'], $attr['queryNumPosts']);
        $recent_posts = new \WP_Query( ultimate_post()->get_query( $attr ) );
        $pageNum = ultimate_post()->get_page_number($attr, $recent_posts->found_posts);
        // Dummy Img Url
        $dummy_url = ULTP_URL.'assets/img/ultp-fallback-img.png';
        // Current Post Id For Pagination
        $curr_post_id = '';
        if(is_single()){
            $curr_post_id = get_the_ID();
        }
        // Loadmore and Unique content 
        if($attr['queryUnique'] && isset($attr['loadMoreQueryUnique']) && $attr['paginationShow'] && ($attr['paginationType'] == 'loadMore')) {
            $unique_ID = $attr['loadMoreQueryUnique'];
            $current_unique_posts = $attr['ultp_current_unique_posts'];
        }
    
        if ($recent_posts->have_posts()) {
            $wraper_before .= '<div '.($attr['advanceId']?'id="'.$attr['advanceId'].'" ':'').' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].''.(isset($attr["align"])? ' align' .$attr["align"]:'').''.(isset($attr["className"])?' '.$attr["className"]:'').'">';
                $wraper_before .= '<div class="ultp-block-wrapper">';
                    
                    // Loading
                    $wraper_before .= ultimate_post()->loading();
                    if ($attr['headingShow'] || $attr['filterShow'] || $attr['paginationShow']) {
                        $wraper_before .= '<div class="ultp-heading-filter">';
                            $wraper_before .= '<div class="ultp-heading-filter-in">';
                                
                                // Heading
                                include ULTP_PATH.'blocks/template/heading.php';
                                
                                if ($attr['filterShow'] || $attr['paginationShow']) {
                                    $wraper_before .= '<div class="ultp-filter-navigation">';

                                        // Filter
                                        if($attr['filterShow'] && $attr['queryType'] != 'posts' && $attr['queryType'] != 'customPosts') {
                                            include ULTP_PATH.'blocks/template/filter.php';
                                        }

                                        // Navigation
                                        if ($attr['paginationShow'] && ($attr['paginationType'] == 'navigation') && ($attr['navPosition'] == 'topRight')) {
                                            include ULTP_PATH.'blocks/template/navigation-before.php';
                                        }
                                    $wraper_before .= '</div>';
                                }

                            $wraper_before .= '</div>';
                        $wraper_before .= '</div>';
                    }
             
                    $wraper_before .= '<div class="ultp-block-items-wrap ultp-block-row ultp-block-column-'.json_decode(json_encode($attr['columns']), True)['lg'].' ultp-pl1a-'.$attr['gridStyle'].' ultp-post-list1-'.$attr['layout'].'">';
                        $idx = ($attr['paginationShow'] && ($attr['paginationType'] == 'loadMore')) ? ( $noAjax ? 1 : 0 ) : 0;
                        $index = 0;
                        while ( $recent_posts->have_posts() ): $recent_posts->the_post();
                            include ULTP_PATH.'blocks/template/data.php';

                            include ULTP_PATH.'blocks/template/category.php';

                            if ($attr['queryUnique']) {
                                $unique_ID[$attr['queryUnique']][] = $post_id;
                                $current_unique_posts[] = $post_id;
                            }

                            $post_loop .= '<'.$attr['contentTag'].' class="ultp-block-item post-id-'.$post_id.'">';
                                $post_loop .= '<div class="ultp-block-content-wrap">';
                                    $post_loop .= '<div class="ultp-block-entry-content">';
                                    if(($attr['gridStyle'] == 'style3' && $idx == 0) && (($post_thumb_id || $attr['fallbackEnable']) && $attr['showImage'])) {
                                        $post_loop .= '<div class="ultp-block-image ultp-block-image-'.$attr['imgAnimation'].($attr["imgOverlay"] ? ' ultp-block-image-overlay ultp-block-image-'.$attr["imgOverlayType"].' ultp-block-image-'.$attr["imgOverlayType"].$idx : '' ).'">';
                                            $post_loop .= '<a href="'.$titlelink.'" '.($attr['openInTab'] ? 'target="_blank"' : '').'>';
                                            // Post Image Id
                                            $block_img_id = $post_thumb_id ? $post_thumb_id : ($attr['fallbackEnable'] && isset($attr['fallbackImg']['id']) ? $attr['fallbackImg']['id'] : '');
                                            // Post Image 
                                            if($post_thumb_id || ($attr['fallbackEnable'] && $block_img_id)) {
                                                $post_loop .=  ultimate_post()->get_image($block_img_id, $attr['imgCrop'], '', $title, $attr['imgSrcset'], $attr['imgLazy']);
                                            } else {
                                                $post_loop .= '<img  src="'.$dummy_url.'" alt="dummy-img" />';
                                            }
                                            $post_loop .= '</a>';
                                            if (($attr['catPosition'] != 'aboveTitle') && $attr['catShow'] ) {
                                                $post_loop .= '<div class="ultp-category-img-grid">'.$category.'</div>';
                                            }
                                        $post_loop .= '</div>';
                                    }

                                    $post_loop .= '<div class="ultp-block-entry-heading">';
                                        // Category
                                        if (($attr['catPosition'] == 'aboveTitle') && $attr['catShow']) {
                                            $post_loop .= $category;
                                        }
                                        // Title
                                        if ($title && $attr['titleShow'] && $attr['titlePosition'] == 1) {
                                            include ULTP_PATH.'blocks/template/title.php';
                                        }
                                        // Meta
                                        if ($attr['metaPosition'] =='top' ) {
                                            include ULTP_PATH.'blocks/template/meta.php';
                                        }
                                        // Title
                                        if ($title && $attr['titleShow'] && $attr['titlePosition'] == 0) {
                                            include ULTP_PATH.'blocks/template/title.php';
                                        }
                                    $post_loop .= '</div>'; 

                                    if(($attr['gridStyle'] != 'style3' || $attr['gridStyle'] == 'style3' && $idx != 0 )  && (($post_thumb_id || $attr['fallbackEnable']) && $attr['showImage'])) {
                                        $post_loop .= '<div class="ultp-block-image ultp-block-image-'.$attr['imgAnimation'].($attr["imgOverlay"] ? ' ultp-block-image-overlay ultp-block-image-'.$attr["imgOverlayType"].' ultp-block-image-'.$attr["imgOverlayType"].$idx : '' ).'">';
                                            $post_loop .= '<a href="'.$titlelink.'" '.($attr['openInTab'] ? 'target="_blank"' : '').'>';
                                            // Post Image Size
                                            $imgSize = $attr['gridStyle'] != 'style1' ? $idx == 0 ?  $attr['imgCrop'] : $attr['imgCropSmall'] : $attr['imgCrop'];
                                            // Post Image Id
                                            $block_img_id = $post_thumb_id ? $post_thumb_id : ($attr['fallbackEnable'] && isset($attr['fallbackImg']['id']) ? $attr['fallbackImg']['id'] : '');
                                            // Post Image 
                                            if($post_thumb_id || ($attr['fallbackEnable'] && $block_img_id)) {
                                                    $post_loop .= ultimate_post()->get_image($block_img_id, $imgSize, '', $title, $attr['imgSrcset'], $attr['imgLazy']);
                                            } else {
                                                $post_loop .= '<img  src="'.$dummy_url.'" alt="dummy-img" />';
                                            }
                                            $post_loop .= '</a>';
                                            if($post_video){
                                                $post_loop .= '<div enableAutoPlay="'.$attr['popupAutoPlay'].'" class="ultp-video-icon">'.ultimate_post()->svg_icon('play_line').'</div>';
                                            }
                                            if(($attr['catPosition'] != 'aboveTitle') && $attr['catShow'] ) {
                                                $post_loop .= '<div class="ultp-category-img-grid">'.$category.'</div>';
                                            }
                                        $post_loop .= '</div>';
                                    }

                                    $post_loop .= '</div>';
                                    $post_loop .= '<div class="ultp-block-content">';
                                        // Excerpt
                                        if ($attr['excerptShow']) {
                                            $excerptLim = $index == 0 && $attr['gridStyle'] !=  'style1' ? $attr['excerptLimitLg'] : $attr['excerptLimit'];
                                            $showFullexcerpt = $index == 0 && $attr['gridStyle'] !=  'style1' ? $attr['fullExcerptLg'] : $attr['showFullExcerpt'];
                                            $post_loop .= '<div class="ultp-block-excerpt">'.ultimate_post()->get_excerpt($post_id, $attr['showSeoMeta'], $showFullexcerpt, $excerptLim).'</div>';
                                        }

                                        // Read More
                                        if ($attr['readMore']) {
                                            $post_loop .= '<div class="ultp-block-readmore"><a aria-label="'.$title.'" href="'.$titlelink.'" '.($attr['openInTab'] ? 'target="_blank"' : '').'>'.($attr['readMoreText'] ? $attr['readMoreText'] : esc_html__( "Read More", "ultimate-post" )).ultimate_post()->svg_icon($attr['readMoreIcon']).'</a></div>';
                                        }
                                        // Meta
                                        if ($attr['metaPosition'] =='bottom' ) {
                                            include ULTP_PATH.'blocks/template/meta.php';
                                        }
                                    $post_loop .= '</div>';
                                $post_loop .= '</div>';
                                if($post_video && $attr['enablePopup']) {
                                    include ULTP_PATH.'blocks/template/video_popup.php';
                                }
                            $post_loop .= '</'.$attr['contentTag'].'>'; 
                            $idx ++;
                            $index++;
                        endwhile;
                        if($attr['queryUnique']) {
                            $post_loop .= "<span style='display: none;' class='ultp-current-unique-posts' data-ultp-unique-ids= ".json_encode($unique_ID)." data-current-unique-posts= ".json_encode($current_unique_posts)."> </span>";
                        }
                        if ($attr['paginationShow'] && ($attr['paginationType'] == 'loadMore')) {
                            $wraper_after .= '<span class="ultp-loadmore-insert-before"></span>';
                        }
                    $wraper_after .= '</div>';//ultp-block-items-wrap
                    
                    // Load More
                    if ($attr['paginationShow'] && ($attr['paginationType'] == 'loadMore')) {
                        include ULTP_PATH.'blocks/template/loadmore.php';
                    }

                    // Navigation
                    if ($attr['paginationShow'] && ($attr['paginationType'] == 'navigation') && ($attr['navPosition'] != 'topRight')) {
                        include ULTP_PATH.'blocks/template/navigation-after.php';
                    }

                    // Pagination
                    if ($attr['paginationShow'] && ($attr['paginationType'] == 'pagination')) {
                        include ULTP_PATH.'blocks/template/pagination.php';
                    }

                $wraper_after .= '</div>';
            $wraper_after .= '</div>';

            wp_reset_query();
        }else {
            $wraper_before .= ultimate_post()->get_no_result_found_html( $attr['notFoundMessage'] );
        }

        return $noAjax ? $post_loop : $wraper_before.$post_loop.$wraper_after;
    }

}