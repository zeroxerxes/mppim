<?php
/**
 * Plugin Cache.
 * 
 * @package ULTP\RequestAPI
 * @since v.2.7.0
 */
namespace ULTP;

defined('ABSPATH') || exit;

/**
 * RequestAPI class.
 */
class RequestAPI{

    private $api_endpoint = 'https://ultp.wpxpo.com/wp-json/restapi/v2/';
    
    public function __construct() {
        add_action('rest_api_init', array($this, 'get_template_data'));
    }

    /**
	 * Create Builder Post Type
     * 
     * @since v.2.7.0
	 * @return NULL
	 */
    public function get_template_data() {
        register_rest_route(
			'ultp/v2', 
			'/get_single_premade/',
			array(
				array(
					'methods'  => 'POST', 
					'callback' => array( $this, 'get_single_premade_callback'),
					'permission_callback' => function () {
						return current_user_can( 'edit_posts' );
					},
					'args' => array()
				)
			)
        );
        register_rest_route(
			'ultp/v2',
			'/condition/',
			array(
				array(
					'methods'  => 'POST',
					'callback' => array($this, 'condition_settings_action'),
					'permission_callback' => function () {
						return current_user_can('edit_posts');
					},
					'args' => array()
				)
			)
		);
        register_rest_route(
			'ultp/v2',
			'/condition_save/',
			array(
				array(
					'methods'  => 'POST',
					'callback' => array($this, 'condition_save_action'),
					'permission_callback' => function () {
						return current_user_can('publish_posts'); // user who have publish post capability, can save the conditions.
					},
					'args' => array()
				)
			)
		);
        register_rest_route(
			'ultp/v2',
			'/data_builder/',
			array(
				array(
					'methods'  => 'POST',
					'callback' => array($this, 'data_builder_action'),
					'permission_callback' => function () {
						return current_user_can('edit_posts');
					},
					'args' => array()
				)
			)
		);
        register_rest_route(
			'ultp/v2',
			'/template_action/',
			array(
				array(
					'methods'  => 'POST',
					'callback' => array($this, 'template_page_action'),
					'permission_callback' => function () {
						return current_user_can('publish_posts'); // user who have publish post capability, can perform template action.
					},
					'args' => array()
				)
			)
		);
    }

    /**
	 * Delete Template Page
     * 
     * @since v.2.6.6
     * @param STRING
	 * @return ARRAY | Success Message
	 */
    public function template_page_action($server) {
        $post = $server->get_params();
        $message = '';
        if (isset($post['type']) && isset($post['id']) && $post['id']) {
            if ($post['type'] == 'delete') {
                wp_delete_post( $post['id'], true);
                $message = __('Template has been deleted.', 'ultimate-post');
            } else if ($post['type'] == 'duplicate') {
                $post_id = $post['id'];
                $post = get_post( $post_id );
                $current_user = wp_get_current_user();
                $new_post_author = $current_user->ID;
                if (isset( $post ) && $post != null) {
                    $args = array(
                        'post_author'    => $new_post_author,
                        'post_content'   => str_replace('u0022', '\u0022', $post->post_content),
                        'post_excerpt'   => $post->post_excerpt,
                        'post_name'      => $post->post_name,
                        'post_status'    => 'draft',
                        'post_title'     => $post->post_title,
                        'post_type'      => $post->post_type,
                      );
                }
                $new_post_id = wp_insert_post( $args );
                
                $type = get_post_meta( $post_id, '__ultp_builder_type', true );
                update_post_meta( $new_post_id, '__ultp_builder_type', $type );

                $css = get_post_meta( $post_id, '_ultp_css', true );
                update_post_meta( $new_post_id, '_ultp_css', $css );
                
                $width = get_post_meta( $post_id, '__container_width', true );
                update_post_meta( $new_post_id, '__container_width', $width );
                
                $sidebar = get_post_meta( $post_id, '__builder_sidebar', true );
                update_post_meta( $new_post_id, '__builder_sidebar', $width );

                $widget_area = get_post_meta( $post_id, '__builder_widget_area', true );
                update_post_meta( $new_post_id, '__builder_widget_area', $widget_area );
                
                update_post_meta( $new_post_id, '_ultp_active', 'yes' );

                $conditions = get_option('ultp_builder_conditions', array());
                if ($conditions && $type) {
                    if (isset($conditions[$type][$post_id])) {
                        $conditions[$type][$new_post_id] = $conditions[$type][$post_id];
                        update_option('ultp_builder_conditions', $conditions);
                    }
                }
                $message = __('Template has been duplicated.', 'ultimate-post');
            } else if ($post['type'] == 'status') {
                if ($post['status']) {
                    wp_update_post(array(
                        'ID' => $post['id'],
                        'post_status' => $post['status']
                    ));
                }
                $message = __('Status has been changed.', 'ultimate-post');
            }
        }
        
        return array(
            'success' => true,
            'message' => $message
        );
    }
   

    /**
	 * Builder Post Type Data
     * 
     * @since v.2.7.0
     * @param ARRAY
	 * @return ARRAY | Information of Builder Post
	 */
    public function data_builder_action($server) {
        $post = $server->get_params();
        $args = array(
            'post_type'         => 'ultp_builder',
            'post_status'       => array('publish', 'draft'),
            'orderby'           => 'title', 
            'order'             => 'ASC',
            'posts_per_page'    => -1,
        );
        $post_results = new \WP_Query($args);
        $post_list = [];
        if (!empty($post_results)) {
            while ( $post_results->have_posts() ) {
                $post_results->the_post();
                $id = get_the_ID();
                $meta_type = get_post_meta( $id, '__ultp_builder_type', true );
                $post_list[] = array(
                    'id' => $id, 
                    'title' => get_the_title(),
                    'author' => get_the_author_meta('display_name'),
                    'date' => get_the_date( get_option('date_format')),
                    'edit' => get_edit_post_link($id),
                    'type' => $meta_type ? $meta_type : 'archive',
                    'status' => get_post_status(),
                );
            }
            wp_reset_postdata();
        }

        $arg = [
            'success' => true,
            'postlist' => $post_list,
            'settings' => get_option('ultp_builder_conditions', array()),
            'defaults' => ultimate_post()->builder_data(),
            // 'new_url' => add_query_arg(array('post_type'=>'ultp_builder'),admin_url('post-new.php'))
        ];
        if (isset($post['pid']) && $post['pid']) {
            $post_meta = get_post_meta( $post['pid'], '__ultp_builder_type', true );
            $arg['type'] = $post_meta ? $post_meta : 'archive';
        }
        return $arg;
    }


    /**
	 * Save Conditions Data
     * 
     * @since v.2.7.0
     * @param ARRAY
	 * @return ARRAY | Message of the Condition Success
	 */
    public function condition_save_action($server) {
        $post = $server->get_params();
        if (isset($post['settings'])) {
            update_option('ultp_builder_conditions', $post['settings']);
            return [
                    'success' => true,
                    'data' => 'Settings Saved!!!'
            ];
        }
    }


    /**
	 * Condition Settings Actions
     * 
     * @since v.2.7.0
     * @param ARRAY
	 * @return ARRAY | Data of the Condition
	 */
    public function condition_settings_action($server) {
        global $wpdb;
        $post = $server->get_params();
        $search_type = explode('###', $post['type']);

        switch ($search_type[0]) {
            case 'type':
                $args = array(
                    'post_type'         => $search_type[1],
                    'post_status'       => 'publish',
                    'orderby'           => 'title', 
                    'order'             => 'ASC',
                    's'                 => $post['term'],
                    'posts_per_page'    => 10,
                );
                if ($post['title_return']){
                    unset($args['s']);
                    $args['p'] = $post['term'];
                }
                $post_results = new \WP_Query($args);
                $title = '';
                $data = [];
                if (!empty($post_results)) {
                    while ( $post_results->have_posts() ) {
                        $post_results->the_post();
                        $id = get_the_ID();
                        $title = html_entity_decode(get_the_title());
                        $data[] = array('value'=>$id, 'title'=>($title?$title:('##'.$id)));
                    }
                    wp_reset_postdata();
                }
                return ['success' => true, 'data' => ($post['title_return'] ? $title : $data )];
            break;

            case 'term':
                $args = array(
                    'taxonomy'  => $search_type[1],
                    'fields'    => 'all',
                    'orderby'   => 'id',
                    'order'     => 'ASC',
                    'name__like'=> $post['term']
                );
                if ($post['title_return']) {
                    $args['term_taxonomy_id'] = array($post['term']);
                    unset($args['name__like']);
                }
                $post_results = get_terms( $args );
                $title = '';
                $data = [];
                if (!empty($post_results)) {
                    foreach ($post_results as $key => $val) {
                        $title = $val->name;
                        $data[] = array('value'=>$val->term_id, 'title'=>$title);
                    }
                }
                return ['success' => true, 'data' => ($post['title_return'] ? $title : $data )];
            break;

            case 'author':
                $term = $post['title_return'] ? $wpdb->esc_like( $post['term'] ) : '%'. $wpdb->esc_like( $post['term'] ) .'%';
                $post_results = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT ID, display_name 
                        FROM $wpdb->users 
                        WHERE user_login LIKE '%s' OR ID LIKE '%s' OR user_nicename LIKE '%s' OR user_email LIKE '%s' OR display_name LIKE '%s' LIMIT 10", $term, $term, $term, $term, $term 
                    )
                );
                $title = '';
                $data = [];
                if (!empty($post_results)) {
                    foreach ($post_results as $key => $val) {
                        $title = $val->display_name;
                        $data[] = array('value'=>$val->ID, 'title'=>$val->display_name);
                    }
                }
                return ['success' => true, 'data' => ($post['title_return'] ? $title : $data )];
            break;
            default:
                return ['success' => false];
            break;
        }
        return ['success' => true, 'data' => 'This is Testing !!!'];
    }


    /**
	 * Single Premade Data and Create Builder Posts
     * 
     * @since v.2.7.0
     * @param ARRAY
	 * @return ARRAY | Data of the Premade
	 */
    public function get_single_premade_callback($server) {

        $is_active = ultimate_post()->is_lc_active();
        $obj_count = wp_count_posts('ultp_builder');
        if (!$is_active) {
            $p_count = isset($obj_count->publish) ? $obj_count->publish : 0;
            $d_count = isset($obj_count->draft) ? $obj_count->draft : 0;
            if (($p_count + $d_count) > 0) {
                return array( 'success' => false );
            }
        }

        $post = $server->get_params();
        $id = isset($post['ID']) ? sanitize_text_field($post['ID']) : '';
        
        if ($id) {
            $response = wp_remote_post( $this->api_endpoint.'single-premade', array(
                'method' => 'POST',
                'timeout' => 120,
                'body' => array( 'section_id' => $id, 'license' => get_option('edd_ultp_license_key') )
            ));
            if (is_wp_error( $response ) ) {
                return array( 'success' => false, 'data' => "Something went wrong:" . $response->get_error_message() );
            } else {
                if (isset($response['body']) && isset($post['type'])) {
                    $body = json_decode($response['body']);
                    if (isset($body->rawData) && isset($body->success) && $body->success) {
                        $post_id = $this->set_post_content($post['type'], wp_slash($body->rawData));
                        return array( 'success' => true, 'link' => get_edit_post_link($post_id) );
                    } else {
                        return array( 'success' => false );
                    }
                }
            }
        } else {
            $post_id = $this->set_post_content($post['type'], '');
            return array( 'success' => true, 'link' => get_edit_post_link($post_id) );
        }
        
    }

     /**
	 * Single Premade Data and Insert Builder Posts
     * 
     * @since v.2.7.0
     * @param ARRAY
	 * @return INT | Post ID
	 */
    public function set_post_content($type, $body = '') {
        $post_id = wp_insert_post(
            array(
                'post_title'   => ucfirst($type) . ' Template',
                'post_content' => $body,
                'post_type'    => 'ultp_builder',
                'post_status'  => 'draft'
            )
        );
        $settings = get_option('ultp_builder_conditions', array());
        switch ($type) {
            case 'singular':
                update_post_meta($post_id, '__ultp_builder_type', 'singular');
                $settings['singular'][$post_id] = ['include/singular/post'];
                break;
            case 'front_page':
                update_post_meta($post_id, '__ultp_builder_type', 'singular');
                $settings['singular'][$post_id] = ['include/singular/front_page'];
                break;
            case 'author':
            case 'post_tag':
            case 'date':
            case 'search':
            case 'archive':
            case 'category':
                update_post_meta($post_id, '__ultp_builder_type', 'archive');
                $extra = $type != 'archive' ? '/'.$type : '';
                $settings['archive'][$post_id] = ['include/archive'.$extra];
                break;
            case 'header':
                update_post_meta($post_id, '__ultp_builder_type', 'header');
                $settings['header'][$post_id] = ['include/header/entire_site'];
                break;
            case 'footer':
                update_post_meta($post_id, '__ultp_builder_type', 'footer');
                $settings['footer'][$post_id] = ['include/footer/entire_site'];
                break;
            case '404':
                update_post_meta($post_id, '__ultp_builder_type', '404');
                $settings['404'][$post_id] = ['include/404'];
                break;
            default:
                break;
        }
        update_option('ultp_builder_conditions', $settings);
        return $post_id;
    }

}