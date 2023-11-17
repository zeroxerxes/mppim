<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Post_Breadcrumb {
    public function __construct() {
        add_action('init', array($this, 'register'));
    }
    public function get_attributes() {

        return array(
            'blockId' => '',

            /*============================
                Breadcrumb Settings
            ============================*/
            'bcrumbSeparator' => true,
            'breadcrumbColor' => '',
            'breadcrumbLinkColor' => '',
            'bcrumbLinkHoverColor' => '',
            'bcrumbTypo' => (object)['openTypography' => 0,'size' => (object)['lg' => '', 'unit' =>'px']],
            'bcrumbSpace' => 12,
            'bcrumbAlign' => (object)[],
            'bcrumbName' => true,

            /*============================
                Separator Style
            ============================*/
            'bcrumbSeparatorIcon' => 'dash',
            'bcrumbSeparatorColor' => '#a9a9a9',
            'bcrumbSeparatorSize' => '',

            /*============================
                Advance Settings
            ============================*/
            'advanceId' => '',
            'advanceZindex' => '',
            'wrapMargin' => (object)['lg' =>(object)['top' => '','bottom' => '', 'unit' =>'px']],
            'wrapOuterPadding' => (object)['lg' =>(object)['top' => '','bottom' => '','left' => '', 'right' => '', 'unit' =>'px']],
            'wrapBg' => (object)['openColor' => 0, 'type' => 'color', 'color' => '#f5f5f5'],
            'wrapBorder' => (object)['openBorder'=>0, 'width' =>(object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid'],
            'wrapShadow' => (object)['openShadow' => 0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4'],
            'wrapRadius' => (object)['lg' =>'', 'unit' =>'px'],
            'wrapHoverBackground' => (object)['openColor' => 0, 'type' => 'color', 'color' => '#037fff'],
            'wrapHoverBorder' => (object)['openBorder'=>0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid'],
            'wrapHoverRadius' => (object)['lg' =>'', 'unit' =>'px'],
            'wrapHoverShadow' => (object)['openShadow' => 0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4'],
            'hideExtraLarge' => false,
            'hideDesktop' => false,
            'hideTablet' => false,
            'hideMobile' => false,
            'advanceCss' => '',
        );
    }

    public function register() {
        register_block_type( 'ultimate-post/post-breadcrumb',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'render_callback' => array($this, 'content')
            )
        );
    }

    public function content($attr, $noAjax) {
        $attr = wp_parse_args($attr, $this->get_attributes());
        global $post;
        $block_name = 'post-breadcrumb';
        $wrapper_before = $wrapper_after = $content = '';

        $wrapper_before .= '<div '.($attr['advanceId']?'id="'.$attr['advanceId'].'" ':'').' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].(isset($attr["className"])?' '.$attr["className"]:'').''.(isset($attr["align"])? ' align' .$attr["align"]:'').'">';
            $wrapper_before .= '<div class="ultp-block-wrapper">';
                $content .= '<ul class="ultp-builder-breadcrumb ultp-breadcrumb-'.$attr['bcrumbSeparatorIcon'].'">';
                    $seperator = '';
                    if ($attr['bcrumbSeparator']) {
                        $seperator .= '<li class="ultp-breadcrumb-separator"></li>';
                    }
                    $content .= '<li><a href="'.esc_url(home_url('/')).'">'.__('Home', 'ultimate-post').'</a></li>';
                    if (is_category() || is_single() || is_tag() || is_author()) {
                        if (is_category()) {
                            $cat = get_queried_object();
                            $parent_cat_id = $cat->parent;
                            if($parent_cat_id) {
                                $content .= $seperator.'<li><a href="'.get_term_link( $parent_cat_id).'">'.get_the_category_by_ID($parent_cat_id).'</a></li>';
                            }
                            $content .= $seperator.'<li>'.single_cat_title('', false).'</li>';
                        }
                        if (is_tag()) {
                            $content .= $seperator.'<li>'.single_tag_title('', false).'</li>';
                        }
                        if (is_author()) {
                            $content .= $seperator.'<li>'.get_the_author_meta('display_name', false).'</li>';
                        }
                        if (is_single()) {
                            $cat = get_the_category();
                            if (isset($cat[0])) {
                                $content .= $seperator.'<li><a href="'.get_term_link($cat[0]->term_id).'">'.$cat[0]->name.'</a></li>';
                            }
                            if ($attr['bcrumbName']) {
                                $content .= $seperator.'<li>'.get_the_title().'</li>';
                            }
                        }
                    } elseif (is_page()) {
                        if ($post->post_parent) {
                            $ancestor = get_post_ancestors($post->post_parent);
                            if (isset($ancestor[0])) {
                                $content = $seperator.'<li><a href="'.get_permalink($ancestor[0]).'">'.get_the_title($ancestor[0]).'</a></li>';
                            }
                        }
                        if ($attr['bcrumbName']) {
                            $content .= $seperator.'<li>'.get_the_title().'</li>';
                        }
                    } elseif (is_search()) {
                        $content .= $seperator.'<li>'.get_search_query().'</li>';
                    }
                $content .= '</ul>';
            $wrapper_after .= '</div>';
        $wrapper_after .= '</div>';

        return $wrapper_before.$content.$wrapper_after;
    }
}