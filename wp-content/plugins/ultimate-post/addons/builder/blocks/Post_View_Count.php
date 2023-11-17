<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Post_View_Count {
    public function __construct() {
        add_action('init', array($this, 'register'));
    }
    public function get_attributes() {
        
        return array(
            'blockId' =>  '',

            /*============================
                Post View Settings
            ============================*/
            'viewLabel' =>  true,
            'viewIconShow' =>  true,
            'viewColor' =>  '',
            'viewTypo' =>  (object)['openTypography' => 0,'size' => (object)['lg' => '', 'unit' =>'px']],
            'viewCountAlign' =>  [],
             /*============================
                Post View Label Style 
            ============================*/
            
            'viewLabelText' =>  'View',
            "viewLabelAlign" =>  "after",
            /*============================
                Post View Icon Style
            ============================*/
            'viewIconColor' =>  '#656565',
            'viewIconStyle' =>  'viewCount1',
            'iconColor' =>  '#656565',
            'viewIconSize' =>  (object)['lg' =>'15', 'unit' =>'px'],
            'viewSpace' =>  (object)['lg' =>'8', 'unit' =>'px'],

            //--------------------------
            //  Advanced Settings
            //--------------------------
            'advanceId' =>  '',
            'advanceZindex' =>  '',
            'wrapMargin' =>  (object)['lg' =>(object)['top' => '','bottom' => '', 'unit' =>'px']],
            'wrapOuterPadding' =>  (object)['lg' =>(object)['top' => '','bottom' => '','left' => '', 'right' => '', 'unit' =>'px']],
            'wrapBg' =>  (object)['openColor' => 0, 'type' => 'color', 'color' => '#f5f5f5'],
            'wrapBorder' =>  (object)['openBorder'=>0, 'width' =>(object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid'],
            'wrapShadow' =>  (object)['openShadow' => 0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4'],
            'wrapRadius' =>  (object)['lg' =>'', 'unit' =>'px'],
            'wrapHoverBackground' =>  (object)['openColor' => 0, 'type' => 'color', 'color' => '#037fff'],
            'wrapHoverBorder' =>  (object)['openBorder'=>0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid'],
            'wrapHoverRadius' =>  (object)['lg' =>'', 'unit' =>'px'],
            'wrapHoverShadow' =>  (object)['openShadow' => 0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4'],
            'hideExtraLarge' =>  false,
            'hideDesktop' =>  false,
            'hideTablet' =>  false,
            'hideMobile' =>  false,
            'advanceCss' =>  '',
        );
    }

    public function register() {
        register_block_type( 'ultimate-post/post-view-count',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'render_callback' =>  array($this, 'content')
            )
        );
    }

    public function content($attr, $noAjax) {
        $attr = wp_parse_args($attr, $this->get_attributes());
        $block_name = 'post-view-count';
        $wrapper_before = $wrapper_after = $content = '';

        $count = get_post_meta( get_the_ID(), '__post_views_count', true );

        $wrapper_before .= '<div '.($attr['advanceId']?'id="'.$attr['advanceId'].'" ':'').' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].(isset($attr["className"])?' '.$attr["className"]:'').''.(isset($attr["align"])? ' align' .$attr["align"]:'').'">';
            $wrapper_before .= '<div class="ultp-block-wrapper">';     
                $content .= '<span class="ultp-view-count">';
                    if ($attr["viewIconShow"] && $attr["viewIconStyle"]) {
                        $content .= ultimate_post()->svg_icon($attr["viewIconStyle"]); 
                    }
                    $content .= '<span class="ultp-view-count-number">';
                        $content .= $count ? $count : 0;
                    $content .= '</span>';
                    if ($attr["viewLabel"]) {
                        $content .= '<span class="ultp-view-label"> '.$attr["viewLabelText"].'</span>';
                    }
                $content .= '</span>';
            $wrapper_after .= '</div>';
        $wrapper_after .= '</div>';

        return $wrapper_before.$content.$wrapper_after;
    }
}