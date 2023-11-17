<?php
/**
 * Initialization Action.
 * 
 * @package ULTP\Notice
 * @since v.1.1.0
 */
namespace ULTP;

use FluentCrm\App\Services\Sanitize;

defined('ABSPATH') || exit;

/**
 * Initialization class.
 */
class ULTP_Initialization{

    private $all_blocks;

    /**
	 * Setup class.
	 *
	 * @since v.1.1.0
	 */
    public function __construct() {
        $this->compatibility_check();
        $this->requires(); // Include Necessary Files
        $this->blocks(); // Include Blocks
        $this->include_addons(); // Include Addons

        add_action('wp',                            array($this, 'popular_posts_tracker_callback'));
        add_filter('block_categories_all',          array($this, 'register_category_callback'), 999999999, 2); // Block Category Register
        add_action('after_setup_theme',             array($this, 'add_image_size'));

        add_action('enqueue_block_editor_assets',   array($this, 'register_scripts_back_callback')); // Only editor
        add_action('admin_enqueue_scripts',         array($this, 'register_scripts_option_panel_callback')); // Option Panel
        add_action('wp_enqueue_scripts',            array($this, 'register_scripts_front_callback')); // Both frontend
        register_activation_hook(ULTP_PATH.'ultimate-post.php', array($this, 'install_hook'));
        add_action( 'activated_plugin',             array($this, 'activation_redirect'));

        add_action('wp_ajax_ultp_next_prev',        array($this, 'ultp_next_prev_callback')); // Next Previous AJAX Call
        add_action('wp_ajax_nopriv_ultp_next_prev', array($this, 'ultp_next_prev_callback')); // Next Previous AJAX Call Logout User
        add_action('wp_ajax_ultp_filter',           array($this, 'ultp_filter_callback')); // Next Previous AJAX Call
        add_action('wp_ajax_nopriv_ultp_filter',    array($this, 'ultp_filter_callback')); // Next Previous AJAX Call Logout User
        add_action('wp_ajax_ultp_pagination',       array($this, 'ultp_pagination_callback')); // Page Number AJAX Call
        add_action('wp_ajax_nopriv_ultp_pagination',array($this, 'ultp_pagination_callback')); // Page Number AJAX Call Logout User
        add_action('wp_ajax_ultp_share_count',      array($this, 'ultp_shareCount_callback')); // share Count save

        add_action('admin_init',                    array($this, 'check_theme_compatibility'));
        add_action( 'after_switch_theme',           array($this, 'wpxpo_swithch_thememe'));

        add_action( 'in_plugin_update_message-'.ULTP_BASE, array( $this, 'in_plugin_settings_update_message' ) );

        add_action( 'upgrader_process_complete', array($this, 'plugin_upgrade_completed'), 10, 2 );

        add_action('wp_ajax_ultp_getsearch_result',        array($this, 'ultp_get_search_result')); // Search Result Showing
    }

    /**
	 * Theme Switch Callback
     * 
     * @since v.1.1.0
	 * @return NULL
	 */
    public function wpxpo_swithch_thememe () {
        $this->check_theme_compatibility();   
    }

    
    /**
	 * Theme Compatibility Action
     * 
     * @since v.1.1.0
	 * @return NULL
	 */
    public function check_theme_compatibility() {
        $licence = apply_filters( 'ultp_theme_integration' , FALSE);
        $theme = get_transient( 'ulpt_theme_enable' );

        if ($licence ) {
            if ($theme != 'integration' ) {
                $themes = wp_get_theme();
                $api_params = array(
                    'wpxpo_theme_action' => 'theme_license',
                    'slug'      => $themes->get('TextDomain'),
                    'author'    => $themes->get('Author'),
                    'item_id'    => 181,
                    'url'        => home_url()
                );
                
                $response = wp_remote_post( 'https://www.wpxpo.com', array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

                if (!is_wp_error( $response ) || 200 === wp_remote_retrieve_response_code( $response ) ) {
                    $license_data = json_decode( wp_remote_retrieve_body( $response ) );
                    if (isset($license_data->license)) {
                        if ($license_data->license == 'valid' ) {
                            set_transient( 'ulpt_theme_enable', 'integration', 2592000 ); // 30 days time
                        }
                    }
                }
            }
        } else {
            if ($theme == 'integration' ) {
                delete_transient('ulpt_theme_enable');
            }
        }
    }


    /**
	 * Check Compatibility
     * 
     * @since v.1.0.0
	 * @return NULL
	 */
    public function compatibility_check() {
        require_once ULTP_PATH.'classes/Compatibility.php';
        new \ULTP\Compatibility();
    }


    /**
	 * Option Panel CSS and JS Scripts
     * 
     * @since v.1.0.0
	 * @return NULL
	 */
    public function register_scripts_option_panel_callback() {
        $is_active = ultimate_post()->is_lc_active();
        $license_key = get_option('edd_ultp_license_key');

        // Custom Font Support Added
        $font_settings = ultimate_post()->get_setting('ultp_custom_font');
        $custom_fonts = array();
	    if ($font_settings == 'true') {
            wp_enqueue_media();
            $args = array(
                'post_type'              => 'ultp_custom_font',
                'post_status'            => 'publish',
                'numberposts'            => 333,
                'order'                  => 'ASC'
            );
            $posts = get_posts( $args );
            if ($posts) {
                foreach( $posts as $post) {
                    setup_postdata( $post );
                    $font = get_post_meta($post->ID , '__font_settings', true);

                    if ( $font ) {
                        array_push( $custom_fonts, array(
                            'title' => $post->post_title,
                            'font' => $font
                        ));
                    }
                }
                wp_reset_postdata();
            }
        }
        
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_script('ultp-option-script', ULTP_URL.'assets/js/ultp-option.js', array('jquery'), ULTP_VER, true);
        wp_enqueue_style('ultp-option-style', ULTP_URL.'assets/css/ultp-option.css', array(), ULTP_VER);

        wp_localize_script('ultp-option-script', 'ultp_option_panel', array(
            'url' => ULTP_URL,
            'active' => $is_active,
            'security' => wp_create_nonce('ultp-nonce'),
            'ajax' => admin_url('admin-ajax.php'),
            'license' => $license_key,
            'settings' => ultimate_post()->get_setting(),
            'addons' => ultimate_post()->all_addons(),
            'premium_link' => ultimate_post()->get_premium_link(),
            'affiliate_id' => apply_filters( 'ultp_affiliate_id', false ),
            'custom_fonts' => $custom_fonts,
            'addons_settings' => apply_filters('ultp_settings', []),
            'post_type' => get_post_type(),
            'saved_template_url' => admin_url('admin.php?page=ultp-settings#saved-templates'),
        ));
        
        /* === Installation Wizard === */
        $_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
        if ($_page == 'ultp-initial-setup-wizard') { 
            wp_enqueue_script('ultp-initial-setup-script', ULTP_URL.'assets/js/ultp_initial_setup_min.js', array('wp-i18n', 'wp-api-fetch', 'wp-api-request'), ULTP_VER, true);
            wp_set_script_translations( 'ultp-initial-setup-script', 'ultimate-post', ULTP_PATH . 'languages/' );
        }

        /* === Builder And Setting Pannel === */
        // if ($_page == 'ultp-settings' || get_post_type(get_the_ID()) == 'ultp_builder') {
        if (get_post_type(get_the_ID()) == 'ultp_builder') {
            wp_enqueue_script('ultp-conditions-script', ULTP_URL.'addons/builder/assets/js/conditions.js', array('wp-i18n', 'wp-api-fetch','wp-components','wp-i18n','wp-blocks'), ULTP_VER, true);
            wp_localize_script('ultp-conditions-script', 'ultp_condition', array(
                'url' => ULTP_URL,
                'active' => $is_active,
                'premium_link' => ultimate_post()->get_premium_link(),
                'license' => $license_key,
                'builder_url' => admin_url('admin.php?page=ultp-settings#builder'),
                'affiliate_id' => apply_filters( 'ultp_affiliate_id', FALSE )
            ));
            wp_set_script_translations( 'ultp-conditions-script', 'ultimate-post', ULTP_PATH . 'languages/' );
        }
        
        /* === Dashboard === */
        $_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
        if ($_page == 'ultp-settings') {
            wp_enqueue_script('ultp-dashboard-script', ULTP_URL.'assets/js/ultp_dashboard_min.js', array('wp-i18n', 'wp-api-fetch', 'wp-api-request', 'wp-components','wp-blocks'), ULTP_VER, true);
            wp_localize_script('ultp-dashboard-script', 'ultp_dashboard_pannel', array(
                'url' => ULTP_URL,
                'active' => $is_active,
                'license' => $license_key,
                'settings' => ultimate_post()->get_setting(),
                'addons' => ultimate_post()->all_addons(),
                'addons_settings' => apply_filters('ultp_settings', []),
                'premium_link' => ultimate_post()->get_premium_link(),
                'builder_url' => admin_url('admin.php?page=ultp-settings#builder'),
                'affiliate_id' => apply_filters( 'ultp_affiliate_id', false ),
                'version' => ULTP_VER,
                'setup_wizard_link' => admin_url('admin.php?page=ultp-initial-setup-wizard'),
                'helloBar' => get_transient('ultp_helloBar'),
                'status' => get_option( 'edd_ultp_license_status' ),
                'expire' => get_option( 'edd_ultp_license_expire' )
            ));
            wp_set_script_translations( 'ultp-dashboard-script', 'ultimate-post', ULTP_PATH . 'languages/' );
        }
        
    }


    /**
	 * Only Frontend CSS and JS Scripts
     * 
     * @since v.1.0.0
	 * @return NULL
	 */
    public function register_scripts_front_callback() {
        $call_common = false;
        if (isset($_GET['preview_id']) && isset($_GET['preview_nonce'])) {
            $call_common = true;
            ultimate_post()->register_scripts_common();
        } else if ('yes' == get_post_meta(ultimate_post()->get_ID(), '_ultp_active', true)) {
            $call_common = true;
            ultimate_post()->register_scripts_common();
        } else if (ultimate_post()->is_builder()) {
            $call_common = true;
            ultimate_post()->register_scripts_common();
        } else if (apply_filters('postx_common_script', false)) {
            $call_common = true;
            ultimate_post()->register_scripts_common();
        } else if (isset($_GET['et_fb'])) { // Divi Backend Builder
            $call_common = true;
            ultimate_post()->register_scripts_common();
        }

        // For WidgetWidget
        $has_block = false;
        $widget_blocks = array();
        global $wp_registered_sidebars, $sidebars_widgets;
        foreach ($wp_registered_sidebars as $key => $value) {
            if (is_active_sidebar($key)) {
                foreach ($sidebars_widgets[$key] as $val) {
                    if (strpos($val, 'block-') !== false) {
                        if (empty($widget_blocks)) { 
                            $widget_blocks = get_option( 'widget_block' );
                        }
                        foreach ( (array) $widget_blocks as $block ) {
                            if (isset( $block['content'] ) && strpos($block['content'], 'wp:ultimate-post') !== false ) {
                                $has_block = true;
                                break;
                            }
                        }
                        if ($has_block) {
                            break;
                        }
                    }
                }
            }
        }
        if ($has_block) {
            if (!$call_common) {
                ultimate_post()->register_scripts_common();
            }
            $css = get_option('ultp-widget', true);
            if ($css) {
                wp_register_style('ultp-post-widget', false );
                wp_enqueue_style('ultp-post-widget' );
                wp_add_inline_style('ultp-post-widget', $css);
            }
        }
    }


    /**
	 * Only Backend CSS and JS Scripts
     * 
     * @since v.1.0.0
	 * @return NULL
	 */
    public function register_scripts_back_callback() {
        ultimate_post()->register_scripts_common();
        global $pagenow;
        $depends = 'wp-editor';
        if ($pagenow === 'widgets.php' ) {
            $depends = 'wp-edit-widgets';
        }
        wp_enqueue_script('ultp-blocks-editor-script', ULTP_URL.'assets/js/editor.blocks.js', array('wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', $depends ), ULTP_VER, true);
        wp_enqueue_style('ultp-blocks-editor-css', ULTP_URL.'assets/css/blocks.editor.css', array(), ULTP_VER);
        if (is_rtl()) { 
            wp_enqueue_style('ultp-blocks-editor-rtl-css', ULTP_URL.'assets/css/rtl.css', array(), ULTP_VER); 
        }
        $is_active = ultimate_post()->is_lc_active();
        $post_type = get_post_type();
        wp_localize_script('ultp-blocks-editor-script', 'ultp_data', array(
            'url' => ULTP_URL,
            'ajax' => admin_url('admin-ajax.php'),
            'security' => wp_create_nonce('ultp-nonce'),
            'hide_import_btn' => ultimate_post()->get_setting('hide_import_btn'),
            'upload' => wp_upload_dir()['basedir'] . '/ultp',
            'premium_link' => ultimate_post()->get_premium_link(),
            'license' => $is_active ? get_option('edd_ultp_license_key') : '',
            'active' => $is_active,
            'archive' => ultimate_post()->is_archive_builder(),
            'settings' => ultimate_post()->get_setting(),
            'post_type' => $post_type == 'premade' ? 'ultp_builder' : $post_type, // premade used for ultp.wpxpo.com
            'date_format' => get_option('date_format'),
            'time_format' => get_option('time_format'),
            'blog' => get_current_blog_id(),
            'archive_child' => ultimate_post()->is_archive_child_builder(),
            'affiliate_id' => apply_filters( 'ultp_affiliate_id', FALSE ),
            'category_url' =>admin_url( 'edit-tags.php?taxonomy=category' ),
            'disable_image_size' => ultimate_post()->get_setting('disable_image_size')
        ));
        wp_set_script_translations( 'ultp-blocks-editor-script', 'ultimate-post', ULTP_PATH . 'languages/' );
    }


    /**
	 * Fire When Plugin First Install
     * 
     * @since v.1.0.0
	 * @return NULL
	 */
    public function install_hook() {
        if (!get_option('ultp_options')) {
            ultimate_post()->init_set_data();
        }
        if (!get_transient('wpxpo_installation_date')) {
            set_transient( 'wpxpo_installation_date', 'yes', 5 * DAY_IN_SECONDS ); // 5 Days Notice
        }
    }


    /**
	 * Redirect After Active Plugin
     * 
     * @since v.1.0.0
     * @param STRING | Plugin Path
	 * @return NULL
	 */
    public function activation_redirect($plugin) {
        if (wp_doing_ajax()) {
            return;
        }
        
        if ($plugin == 'ultimate-post/ultimate-post.php' ) {
            if (ultimate_post()->get_setting('init_setup') != 'yes') {
                exit(wp_safe_redirect(admin_url('admin.php?page=ultp-initial-setup-wizard'))); //phpcs:ignore
            } else {
                exit(wp_safe_redirect(admin_url('admin.php?page=ultp-settings#home'))); //phpcs:ignore
            }
        }
    }


    /**
	 * Require Blocks 
     * 
     * @since v.1.0.0
	 * @return NULL
	 */
    public function blocks() {
        global $unique_ID;
        $setting = ultimate_post()->get_setting();
        require_once ULTP_PATH.'blocks/Post_List_1.php';
        require_once ULTP_PATH.'blocks/Post_List_2.php';
        require_once ULTP_PATH.'blocks/Post_List_3.php';
        require_once ULTP_PATH.'blocks/Post_List_4.php';
        require_once ULTP_PATH.'blocks/Post_Grid_1.php';
        require_once ULTP_PATH.'blocks/Post_Grid_2.php';
        require_once ULTP_PATH.'blocks/Post_Grid_3.php';
        require_once ULTP_PATH.'blocks/Post_Grid_4.php';
        require_once ULTP_PATH.'blocks/Post_Grid_5.php';
        require_once ULTP_PATH.'blocks/Post_Grid_6.php';
        require_once ULTP_PATH.'blocks/Post_Grid_7.php';
        require_once ULTP_PATH.'blocks/Post_Slider_1.php';
        require_once ULTP_PATH.'blocks/Post_Slider_2.php';
        require_once ULTP_PATH.'blocks/Post_Module_1.php';
        require_once ULTP_PATH.'blocks/Post_Module_2.php';
        require_once ULTP_PATH.'blocks/Heading.php';
        require_once ULTP_PATH.'blocks/Image.php';
        require_once ULTP_PATH.'blocks/Taxonomy.php';
        require_once ULTP_PATH.'blocks/News_Ticker.php';
        require_once ULTP_PATH.'blocks/Advanced_Search.php';
        
        $this->all_blocks['ultimate-post_post-list-1'] = new \ULTP\blocks\Post_List_1();
        $this->all_blocks['ultimate-post_post-list-2'] = new \ULTP\blocks\Post_List_2();
        $this->all_blocks['ultimate-post_post-list-3'] = new \ULTP\blocks\Post_List_3();
        $this->all_blocks['ultimate-post_post-list-4'] = new \ULTP\blocks\Post_List_4();
        $this->all_blocks['ultimate-post_post-grid-1'] = new \ULTP\blocks\Post_Grid_1();
        $this->all_blocks['ultimate-post_post-grid-2'] = new \ULTP\blocks\Post_Grid_2();
        $this->all_blocks['ultimate-post_post-grid-3'] = new \ULTP\blocks\Post_Grid_3();
        $this->all_blocks['ultimate-post_post-grid-4'] = new \ULTP\blocks\Post_Grid_4();
        $this->all_blocks['ultimate-post_post-grid-5'] = new \ULTP\blocks\Post_Grid_5();
        $this->all_blocks['ultimate-post_post-grid-6'] = new \ULTP\blocks\Post_Grid_6();
        $this->all_blocks['ultimate-post_post-grid-7'] = new \ULTP\blocks\Post_Grid_7();
        $this->all_blocks['ultimate-post_post-slider-1'] = new \ULTP\blocks\Post_Slider_1();
        $this->all_blocks['ultimate-post_post-slider-2'] = new \ULTP\blocks\Post_Slider_2();
        $this->all_blocks['ultimate-post_post-module-1'] = new \ULTP\blocks\Post_Module_1();
        $this->all_blocks['ultimate-post_post-module-2'] = new \ULTP\blocks\Post_Module_2();
        $this->all_blocks['ultimate-post_heading'] = new \ULTP\blocks\Heading();
        $this->all_blocks['ultimate-post_image'] = new \ULTP\blocks\Image();
        $this->all_blocks['ultimate-post_ultp-taxonomy'] = new \ULTP\blocks\Taxonomy();
        $this->all_blocks['ultimate-post_news-ticker'] = new \ULTP\blocks\News_Ticker();
        $this->all_blocks['ultimate-post_news-ticker'] = new \ULTP\blocks\Advanced_Search();

        if (ultimate_post()->get_setting('ultp_builder') == 'true') {
            require_once ULTP_PATH.'addons/builder/blocks/Archive_Title.php';
            require_once ULTP_PATH.'addons/builder/blocks/Post_Title.php';
            require_once ULTP_PATH.'addons/builder/blocks/Post_Content.php';
            require_once ULTP_PATH.'addons/builder/blocks/Post_Featured_Image.php';
            require_once ULTP_PATH.'addons/builder/blocks/Post_Breadcrumb.php';
            require_once ULTP_PATH.'addons/builder/blocks/Post_Tag.php';
            require_once ULTP_PATH.'addons/builder/blocks/Post_Category.php';
            require_once ULTP_PATH.'addons/builder/blocks/Next_Previous.php';
            require_once ULTP_PATH.'addons/builder/blocks/Post_Excerpt.php';
            require_once ULTP_PATH.'addons/builder/blocks/Author_Box.php';
            require_once ULTP_PATH.'addons/builder/blocks/Post_Comments.php';
            require_once ULTP_PATH.'addons/builder/blocks/Post_View_Count.php';
            require_once ULTP_PATH.'addons/builder/blocks/Post_Reading_Time.php';
            require_once ULTP_PATH.'addons/builder/blocks/Post_Comment_Count.php';
            require_once ULTP_PATH.'addons/builder/blocks/Post_Author_Meta.php';
            require_once ULTP_PATH.'addons/builder/blocks/Post_Date_Meta.php';
            // require_once ULTP_PATH.'addons/builder/blocks/Related_Posts.php';
            require_once ULTP_PATH.'addons/builder/blocks/Post_Social_Share.php';
            require_once ULTP_PATH.'addons/builder/blocks/Advance_Post_Meta.php';
    
            new \ULTP\blocks\Archive_Title();
            new \ULTP\blocks\Post_Title();
            new \ULTP\blocks\Post_Content();
            new \ULTP\blocks\Post_Featured_Image();
            new \ULTP\blocks\Post_Breadcrumb();
            new \ULTP\blocks\Post_Tag();
            new \ULTP\blocks\Post_Category();
            new \ULTP\blocks\Next_Previous();
            new \ULTP\blocks\Post_Excerpt();
            new \ULTP\blocks\Author_Box();
            new \ULTP\blocks\Post_Comments();
            new \ULTP\blocks\Post_View_Count();
            new \ULTP\blocks\Post_Reading_Time();
            new \ULTP\blocks\Post_Comment_Count();
            new \ULTP\blocks\Post_Author_Meta();
            new \ULTP\blocks\Post_Date_Meta();
            // new \ULTP\blocks\Related_Posts();
            new \ULTP\blocks\Post_Social_Share();
            new \ULTP\blocks\Advance_Post_Meta();
        }
    }


    /**
	 * Necessary Requires Class 
     * 
     * @since v.1.0.0
	 * @return NULL
	 */
    public function requires() {
        require_once ULTP_PATH.'classes/Notice.php';
        require_once ULTP_PATH.'classes/Styles.php';
        require_once ULTP_PATH.'classes/Options.php';
        require_once ULTP_PATH.'classes/REST_API.php';
        require_once ULTP_PATH.'classes/Caches.php';
        new \ULTP\REST_API();
        new \ULTP\Options();
        new \ULTP\Caches();
        new \ULTP\Styles();
        new \ULTP\Notice();

        require_once ULTP_PATH.'classes/Deactive.php';
        new \ULTP\Deactive();
    }


    /**
	 * Block Categories Initialization
     * 
     * @since v.1.0.0
     * @param $categories(ARRAY) | $post (ARRAY)
	 * @return NULL
	 */
    public function register_category_callback( $categories, $post ) {
        $attr = array(
            array(
                'slug' => 'ultimate-post',
                'title' => __('PostX - Gutenberg Post Blocks', 'ultimate-post')
            ),
            array(
                'slug' => 'postx-site-builder',
                'title' => __('PostX Site Builder', 'ultimate-post')
            )
        );
        return array_merge($attr, $categories);
    }

    
    /**
	 * Post View Counter for Every Post
     * 
     * @since v.1.0.0
     * @param NUMBER | Post ID
	 * @return NULL
	 */
    public function popular_posts_tracker_callback($post_id) {
        if (!is_single()) { return; }
        global $post;
        $post_id = isset($post->ID) ? $post->ID : '';
        $isEnable = apply_filters('ultp_view_cookies', true);
        // add_filter( 'ultp_view_cookies', '__return_false' ); 
        $cookies_disable = ultimate_post()->get_setting('disable_view_cookies');
        if ($post_id && $isEnable && $cookies_disable != 'yes') {
            $has_cookie = isset( $_COOKIE['ultp_view_'.$post_id] ) ? sanitize_text_field($_COOKIE['ultp_view_'.$post_id]) : false;
            if (!$has_cookie) {
                $count = (int)get_post_meta( $post_id, '__post_views_count', true );
                update_post_meta($post_id, '__post_views_count', $count ? (int)$count + 1 : 1 );
                setcookie( 'ultp_view_'.$post_id, 1, time() + 86400, COOKIEPATH ); // 1 days cookies
            }
        }
    }


    /**
	 * Set Image Size
     * 
     * @since v.1.0.0
	 * @return NULL
	 */
    public function add_image_size() {
        $size_disable = ultimate_post()->get_setting('disable_image_size');
        if ($size_disable != 'yes') {
            add_image_size('ultp_layout_landscape_large', 1200, 800, true);
            add_image_size('ultp_layout_landscape', 870, 570, true);
            add_image_size('ultp_layout_portrait', 600, 900, true);
            add_image_size('ultp_layout_square', 600, 600, true);
        }
    }


    /**
	 * Include Addons Directory
     * 
     * @since v.1.0.0
	 * @return NULL
	 */
	public function include_addons() {
		$addons_dir = array_filter(glob(ULTP_PATH.'addons/*'), 'is_dir');
		if (count($addons_dir) > 0) {
			foreach( $addons_dir as $key => $value ) {
				$addon_dir_name = str_replace(dirname($value).'/', '', $value);
				$file_name = ULTP_PATH . 'addons/'.$addon_dir_name.'/init.php';
				if (file_exists($file_name) ) {
					include_once $file_name;
				}
			}
		}
    }
    

    /**
	 * Next Preview Callback of the Blocks
     * 
     * @since v.1.0.0
	 * @return STRING
	 */
    public function ultp_next_prev_callback() {
        if (! (isset($_REQUEST['wpnonce']) && wp_verify_nonce(sanitize_key(wp_unslash($_REQUEST['wpnonce'])), 'ultp-nonce'))) {
            return ;
        }

        $paged      = isset($_POST['paged'])?sanitize_text_field($_POST['paged']):'';
        $blockId    = isset($_POST['blockId'])? sanitize_text_field($_POST['blockId']):'';
        $postId     = isset($_POST['postId'])? sanitize_text_field($_POST['postId']):'';
        $blockRaw   = isset($_POST['blockName'])? sanitize_text_field($_POST['blockName']):'';
        $builder    = isset($_POST['builder']) ? sanitize_text_field($_POST['builder']) : '';
        $blockName  = str_replace('_','/', $blockRaw);
        $widgetBlockId  = isset($_POST['widgetBlockId'])?sanitize_text_field($_POST['widgetBlockId']):'';
        $filterValue = isset($_POST['filterValue'])?sanitize_text_field($_POST['filterValue']):'';
        $filterType = isset($_POST['filterType'])?sanitize_text_field($_POST['filterType']):'';
        $exclude_post_id = isset($_POST['exclude']) ? sanitize_text_field($_POST['exclude']) : '';

        $ultp_uniqueIds = isset($_POST['ultpUniqueIds']) ? json_decode(stripslashes(sanitize_text_field($_POST['ultpUniqueIds'])), true) : [];
        $ultp_current_unique_posts = isset($_POST['ultpCurrentUniquePosts']) ? json_decode(stripslashes(sanitize_text_field($_POST['ultpCurrentUniquePosts'])), true) : [];

        if($widgetBlockId) {
            $blocks = parse_blocks(get_option('widget_block')[$widgetBlockId]['content']);
            $this->block_return($blocks, $paged, $blockId, $blockRaw, $blockName, $builder, '', $filterValue, $filterType, $ultp_uniqueIds, $ultp_current_unique_posts, $widgetBlockId, '');
        }elseif ($paged && $blockId && $postId && $blockName ) {
            $post = get_post($postId); 
            if(has_blocks($post->post_content)) {
                $blocks = parse_blocks($post->post_content);
                $this->block_return($blocks, $paged, $blockId, $blockRaw, $blockName, $builder, $postId, $filterValue, $filterType, $ultp_uniqueIds, $ultp_current_unique_posts, '', $exclude_post_id);
            }
        }
    }

    /**
	 * Pagination for filter cal'back
     * 
     * @since v.2.8.9
	 * @return STRING
	 */
    public function pagination_for_filter( $attr, $postId, $blockRaw, $filter_attributes ) {
        $attr['queryNumber'] = ultimate_post()->get_post_number(4, $attr['queryNumber'], $attr['queryNumPosts']);
        $recent_posts = new \WP_Query( ultimate_post()->get_query( $attr ) );
        $pageNum = ultimate_post()->get_page_number($attr, $recent_posts->found_posts);

        $data_filter_value = 'data-filter-value=" ' . $filter_attributes['queryTaxValue']. '"';
        $data_filter_type = 'data-filter-type=" ' . $filter_attributes['queryTax']. '"';
        $wraper_after = '';
        $style = $pageNum == 1 ? 'style="display:none"' : '';

        if($attr['paginationType'] == 'loadMore') {
            $wraper_after .= '<div '.$style.' class="ultp-loadmore "'. $data_filter_value . $data_filter_type .'>';
                $wraper_after .= '<span class="ultp-loadmore-action" tabindex="0" role="button" data-pages="'.$pageNum.'" data-pagenum="1" data-blockid="'.$attr['blockId'].'" data-blockname="'.$blockRaw.'" data-postid="'.$postId.'" '.ultimate_post()->get_builder_attr($attr['queryType']).'>'.( isset($attr['loadMoreText']) ? $attr['loadMoreText'] : 'Load More' ).' <span class="ultp-spin">'.ultimate_post()->svg_icon('refresh').'</span></span>';
            $wraper_after .= '</div>';
        }
        else if($attr['paginationType'] == 'navigation') {
            $wraper_after .= '<div '.$style.'  class="ultp-next-prev-wrap" data-pages="'.$pageNum.'" data-pagenum="1" data-blockid="'.$attr['blockId'].'" data-blockname="'.$blockRaw.'" data-postid="'.$postId.'" '.ultimate_post()->get_builder_attr($attr['queryType']). $data_filter_value . $data_filter_type .'>';
                $wraper_after .= ultimate_post()->next_prev();
            $wraper_after .= '</div>';
        }
        else if($attr['paginationType'] == 'pagination') {
            $wraper_after .= '<div class="ultp-pagination-wrap'.($attr["paginationAjax"] ? " ultp-pagination-ajax-action" : "").'" data-paged="1" data-blockid="'.$attr['blockId'].'" data-postid="'.$postId.'" data-pages="'.$pageNum.'" data-blockname="'.$blockRaw.'" '.ultimate_post()->get_builder_attr($attr['queryType']). $data_filter_value . $data_filter_type .'>';
                $wraper_after .= ultimate_post()->pagination($pageNum, $attr['paginationNav'], $attr['paginationText'], $attr["paginationAjax"], isset($_SERVER['HTTP_REFERER'])?esc_url_raw($_SERVER['HTTP_REFERER']):'');
            $wraper_after .= '</div>';
        }
        wp_reset_query();

        return $wraper_after;
    }

    /**
	 * Filter Callback of the Blocks
     * 
     * @since v.1.0.0
	 * @return STRING
	 */
    public function filter_block_return($blocks, $blockId, $blockRaw, $blockName, $taxtype, $taxonomy, $postId, &$toReturn, $widgetBlockId='') {
        foreach ($blocks as $key => $value) {
            if ($blockName == $value['blockName']) {
                if ($value['attrs']['blockId'] == $blockId) {
                    // if(!$value['attrs']['currentPostId']) {
                    //     $this->update_block_attr( $postId, $blocks, $key, $widgetBlockId );
                    // }
                    $attr = $this->all_blocks[$blockRaw]->get_attributes(true);
                    if ($taxonomy) {
                        $value['attrs']['queryTaxValue'] = json_encode(array($taxonomy));
                        $value['attrs']['queryTax'] = $taxtype;
                        $value['attrs']['ajaxCall'] = true;
                    }
                    if (isset($value['attrs']['queryNumber'])) {
                        $value['attrs']['queryNumber'] = $value['attrs']['queryNumber'];
                    }
                    $attr = array_merge($attr, $value['attrs']);

                    $filter_attributes = [];
                    $filter_attributes['queryTaxValue'] = $taxonomy;
                    $filter_attributes['queryTax'] = $taxtype;

                    $toReturn = [
                        'blocks' => $this->all_blocks[$blockRaw]->content($attr, true),
                        'pagination' => $this->pagination_for_filter($attr, $postId, $blockRaw, $filter_attributes),
                        'paginationType' => $attr['paginationType'],
                        'paginationShow' => $attr['paginationShow']
                    ];
                }
            }
            if (!empty($value['innerBlocks'])) {
                $this->filter_block_return($value['innerBlocks'], $blockId, $blockRaw, $blockName, $taxtype, $taxonomy, $postId, $toReturn, $widgetBlockId);
            }
        }
        return $toReturn;
    }


    /**
	 * Filter Callback of the Blocks
     * 
     * @since v.1.0.0
	 * @return STRING
	 */
    public function ultp_filter_callback() {
        if (! (isset($_REQUEST['wpnonce']) && wp_verify_nonce(sanitize_key(wp_unslash($_REQUEST['wpnonce'])), 'ultp-nonce'))) {
            return ;
        }
     
        $taxtype    = isset($_POST['taxtype'])? sanitize_text_field($_POST['taxtype']):'';
        if ($taxtype ) {
            $blockId    = isset($_POST['blockId'])? sanitize_text_field($_POST['blockId']):'';
            $postId     = isset($_POST['postId'])?sanitize_text_field($_POST['postId']):'';
            $taxonomy   = isset($_POST['taxonomy'])?sanitize_text_field($_POST['taxonomy']):'';
            $blockRaw   = isset($_POST['blockName'])?sanitize_text_field($_POST['blockName']):'';
            $blockName  = str_replace('_','/', $blockRaw);
            $post = get_post($postId); 
            $widgetBlockId  = isset($_POST['widgetBlockId'])? sanitize_text_field($_POST['widgetBlockId']):'';
            $toReturn = [];
            if($widgetBlockId) {
                $blocks = parse_blocks(get_option('widget_block')[$widgetBlockId]['content']);
                $data = $this->filter_block_return($blocks, $blockId, $blockRaw, $blockName, $taxtype, $taxonomy, $postId, $toReturn, $widgetBlockId);
            }elseif (has_blocks($post->post_content)) {
                $blocks = parse_blocks($post->post_content);
                $data = $this->filter_block_return($blocks, $blockId, $blockRaw, $blockName, $taxtype, $taxonomy, $postId, $toReturn, '');
            }
            return wp_send_json_success( [
                'filteredData' => $data
            ] );
        }
    }


    /**
	 * Pagination of the Blocks
     * 
     * @since v.1.0.0
	 * @return STRING
	 */
    public function ultp_pagination_callback() {
        if (! (isset($_REQUEST['wpnonce']) && wp_verify_nonce(sanitize_key(wp_unslash($_REQUEST['wpnonce'])), 'ultp-nonce'))) {
            return ;
        }

        $paged      = isset($_POST['paged'])? sanitize_text_field($_POST['paged']):'';
        if ($paged) {
            $blockId    = isset($_POST['blockId'])? sanitize_text_field($_POST['blockId']):'';
            $postId     = isset($_POST['postId'])?sanitize_text_field($_POST['postId']):'';
            $blockRaw   = isset($_POST['blockName'])?sanitize_text_field($_POST['blockName']):'';
            $builder    = isset($_POST['builder']) ? sanitize_text_field($_POST['builder']) : '';
            $blockName  = str_replace('_','/', $blockRaw);
            $post = get_post($postId);
            $widgetBlockId  = isset($_POST['widgetBlockId'])? sanitize_text_field($_POST['widgetBlockId']):'';
            $filterValue = isset($_POST['filterValue'])? sanitize_text_field($_POST['filterValue']):'';
            $filterType = isset($_POST['filterType'])? sanitize_text_field($_POST['filterType']):'';
            $exclude_post_id = isset($_POST['exclude']) ? sanitize_text_field($_POST['exclude']) : '';
            $ultp_uniqueIds = isset($_POST['ultpUniqueIds']) ? json_decode(stripslashes(sanitize_text_field($_POST['ultpUniqueIds'])), true) : [];
            $ultp_current_unique_posts = isset($_POST['ultpCurrentUniquePosts']) ? json_decode(stripslashes(sanitize_text_field($_POST['ultpCurrentUniquePosts'])), true) : [];
            
            if($widgetBlockId) {
                $blocks = parse_blocks(get_option('widget_block')[$widgetBlockId]['content']);
                $this->block_return($blocks, $paged, $blockId, $blockRaw, $blockName, $builder, '', $filterValue, $filterType, $ultp_uniqueIds, $ultp_current_unique_posts, $widgetBlockId, '');
            }elseif(has_blocks($post->post_content)) {
                $blocks = parse_blocks($post->post_content);
                $this->block_return($blocks, $paged, $blockId, $blockRaw, $blockName, $builder, $postId, $filterValue, $filterType, $ultp_uniqueIds, $ultp_current_unique_posts, '', $exclude_post_id);
            }
        }
    }

    /**
	 * share Count callback
     * 
     * @since v.1.0.0
	 * @return STRING
	 */
    public function ultp_shareCount_callback() {
        if (! (isset($_REQUEST['wpnonce']) && wp_verify_nonce(sanitize_key(wp_unslash($_REQUEST['wpnonce'])), 'ultp-nonce'))) {
            return ;
        }
            $id =isset($_POST['postId'])? sanitize_text_field($_POST['postId']):'';
            $count = isset($_POST['shareCount'])? sanitize_text_field($_POST['shareCount']):'';
            $post_id = $id;
            $new_count = $count+1; 
            update_post_meta($post_id, 'share_count', $new_count);
    }

    /**
	 * update_block_attrUpdate Block Attr for Pagination Loadmore and Navigation
     * 
     * @since v.3.1.1
	 */
    function update_block_attr($postId, $blocks, $key, $widgetBlockId ) {
        if($widgetBlockId) {
            $widget_blocks = get_option('widget_block');
            $block_parsed = parse_blocks($widget_blocks[$widgetBlockId]['content']);
            $block_parsed[$key]['attrs']['currentPostId'] = 'widgets';
            $widget_blocks[$widgetBlockId]['content'] = serialize_blocks($block_parsed);
            update_option('widget_block', str_replace('u0022', '\u0022', $widget_blocks));
        } else {
            $blocks[$key]['attrs']['currentPostId'] = $postId;
            wp_update_post(array(
                'ID' => $postId,
                'post_content' => str_replace('u0022', '\u0022', serialize_blocks($blocks))
            ));
        }
    }

    /**
	 * Blocks Content Start
     * 
     * @since v.1.0.0
	 * @return STRING
	 */
    public function block_return($blocks, $paged, $blockId, $blockRaw, $blockName, $builder, $postId, $filterValue, $filterType, $ultp_uniqueIds=[], $ultp_current_unique_posts=[] , $widgetBlockId='', $exclude_post_id = '') {
        foreach ($blocks as $key => $value) {
            if ($blockName == $value['blockName']) {
                if ($value['attrs']['blockId'] == $blockId) {
                    // if(!$value['attrs']['currentPostId']) {
                    //     $this->update_block_attr( $postId, $blocks, $key, $widgetBlockId );
                    // }
                    $attr = $this->all_blocks[$blockRaw]->get_attributes(true); 
                    $value['attrs']['paged'] = $paged;
                    if ($builder) {
                        $value['attrs']['builder'] = $builder;
                    }
                    if ($postId) {
                        $attr['current_post'] = $postId;
                        if(get_post_type($postId) == 'ultp_builder' && !$builder){
                            $attr['current_post'] = $exclude_post_id;
                        }
                    }
                    if (isset($value['attrs']['queryUnique']) && $value['attrs']['queryUnique']) {
                        $value['attrs']['loadMoreQueryUnique'] = $ultp_uniqueIds;
                        $ultp_uniqueIds[$value['attrs']['queryUnique']] = array_diff( $ultp_uniqueIds[$value['attrs']['queryUnique']], $ultp_current_unique_posts );
                        $value['attrs']['savedQueryUnique'] = $ultp_uniqueIds;
                        $value['attrs']['ultp_current_unique_posts'] = $ultp_current_unique_posts;
                    }
                    if(isset($value['attrs']['queryUnique']) && $value['attrs']['queryUnique'] && ( $value['attrs']['paginationType'] == 'loadMore' || $value['attrs']['paginationType'] == 'navigation') && isset($ultp_uniqueIds) && !isset($ultp_current_unique_posts)) {
                        die();
                    }

                    if($filterValue) {
                        $value['attrs']['queryTaxValue'] = json_encode(array($filterValue));
                        $value['attrs']['queryTax'] = $filterType;
                        $value['attrs']['checkFilter'] = true;
                    }
                    // Exclude Current Post From Pagination
                    if($exclude_post_id){
                        $queryArr = json_decode( $value['attrs']['queryExclude']);
                        $queryArr[] = array(
                            'value' => $exclude_post_id,
                            'title' => ''
                        );
                        $value['attrs']['queryExclude'] = json_encode($queryArr);
                    }
                    $attr = array_merge($attr, $value['attrs']);
                    echo  $this->all_blocks[$blockRaw]->content($attr, true); //phpcs:ignore
                    die();
                }
            }
            if (!empty($value['innerBlocks'])) {
                $this->block_return($value['innerBlocks'], $paged, $blockId, $blockRaw, $blockName, $builder, $postId, $filterValue, $filterType, $ultp_uniqueIds, $ultp_current_unique_posts, $widgetBlockId, $exclude_post_id);
            }
        }
    }

    /**
     * WordPress Plugin Notice 
     * 
     * @since v.2.6.4
     * @return NULL
     */
    public function in_plugin_settings_update_message() {
        $response = wp_remote_get(
            'https://plugins.svn.wordpress.org/ultimate-post/trunk/readme.txt', array(
            'method' => 'GET'
        ));
        
        if ( is_wp_error( $response ) || $response['response']['code'] != 200 ) {
            return;
        }
        
        $changelog_lines = preg_split("/(\r\n|\n|\r)/", $response['body']);

        $is_copy = false;
        $current_tag = '';
        $tag_text = 'Stable tag:';
        if (!empty($changelog_lines)) {
            echo '<hr style="border-color:#dba617;"/>';
            echo '<div style="color:#50575e;font-size:13px;font-weight:bold;"> <span style="color:#f56e28;" class="dashicons dashicons-warning"></span> ' . esc_html('PostX is ready for the next update. Changelog:-') . '</div>';
            echo '<hr style="border-color:#dba617;"/>';
            echo '<ul style="max-height:200px;overflow:scroll;">';
            foreach ($changelog_lines as $key => $line) {
                // Get Current Vesion
                if ($current_tag == '') {
                    if (strpos($line, $tag_text) !== false) { 
                        $current_tag = trim(str_replace($tag_text, '', $line));
                    }
                } else {
                    if ($is_copy) {
                        if (strpos($line, '= '.ULTP_VER) !== false) {
                            break;
                        }
                        if (!empty($line)) {
                            if (strpos($line, '= ') !== false) {
                                echo '<li style="color:#50575e;font-weight:bold;"><br/>'.esc_html($line).'</li>';
                            } else {
                                echo '<li>'.esc_html($line).'</li>';
                            }
                        }
                    } else {
                        if (strpos($line, '= '.$current_tag) !== false) { // Current Version
                            $is_copy = true;
                            echo '<li style="color:#50575e;font-weight:bold;">'.esc_html($line).'</li>';
                        }
                    }
                }
            }
            echo '</ul>';
        }
    }

    /**
     * Check Plugin Upgrade
     *
     * @since 2.4.3
     *
     * @return void
     */
    public function plugin_upgrade_completed() {
        if (ultimate_post()->get_setting('init_setup') != 'yes') {
            ultimate_post()->set_setting('init_setup', 'yes');
        }
    }

    /**
     * Search Result Data Showing
     *
     * @since V.2.9.10
     * @param STRING | Search Query
	 * @return ARRAY | Search Result ( Post )
     */
    public function ultp_get_search_result() {
        $result = ultimate_post()->get_search_data( isset($_POST['data'])?sanitize_text_field($_POST['data']):''); //phpcs:disable WordPress.Security.NonceVerification.Missing
        return wp_send_json_success( [
            'result_list' => $result
        ] );
    }
}