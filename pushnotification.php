<?php

/*
Plugin Name: Push Notification
*/

require_once( plugin_dir_path( __FILE__ ) . '/models/notification-model.php' );

class pushnotification extends notificationmodel {
    
    protected static $_object = null;
    
    public function __construct() {
        if (is_admin()) {
            add_action('admin_menu', array(&$this, 'add_admin_menu'));
            add_action('init', array(&$this, 'admin_form_submit'));
            add_action('admin_enqueue_scripts', array(&$this, 'admin_embed_scripts'));
            add_action('wp_ajax_postsbytaxonomy', array(&$this, "get_posts_by_taxonomy"));
            add_action('wp_ajax_nopriv_postsbytaxonomy', array(&$this, "get_posts_by_taxonomy"));
        } else {
            add_action( 'wp', array(&$this, "init_pushnotification") );
            add_action( 'wp_footer', array(&$this, "load_pushnotification") );
        }
    }
    
    /*
     * create the object
     */
    public static function initialize() {
        if (is_null(self::$_object)) {
            self::$_object = new self();
        }
        return self::$_object;
    }
    
    /*
     * Add admin menu under Settings with the callback notification_settings()
     * All admin settings
     */
    public function add_admin_menu() {
        add_submenu_page(
			'options-general.php'
			, __('Push Notifications', 'notification')
			, __('Push Notifications', 'notification')
			, 'manage_options'
			, 'pushnotification'
			, array(&$this, 'notification_settings')
	);
    }
    
    /*
     * Settings for the Notification
     * Actual settings for the notification
     */
    public function notification_settings() {
        $model = new notificationmodel();
        $model->get_options();
        include( plugin_dir_path( __FILE__ ) . 'views/admin/notification-settings-header.php');
        $post_types = $model->get_all_post_types();
        $selected_taxonomies = $model->_options["taxonomies"];
        $notification_text = $model->_options["notification_text"];
        foreach ( $post_types  as $post_type ) {
            if(in_array($post_type,$selected_taxonomies)) {
                $checked = "checked";
            } else {
                $checked = "";
            }
            include( plugin_dir_path( __FILE__ ) . 'views/admin/post-types.php');
        }
        include( plugin_dir_path( __FILE__ ) . 'views/admin/notification-settings.php');
        include( plugin_dir_path( __FILE__ ) . 'views/admin/notification-settings-footer.php');
        exit;
    }
    
    /*
     * Posts by Taxonomy
     * Get all posts ny sending the taxonomy slug
     */
    public function get_posts_by_taxonomy() {
        $model = new notificationmodel();
        $model->get_options();
        $taxonomy = $_POST["_post_taxonomy"];
        $posts = $model->list_posts_by_taxonomy($taxonomy);
        $selected_posts = $model->_options["posts"];
        include( plugin_dir_path( __FILE__ ) . 'views/admin/select-posts.php');
        foreach ( $posts  as $post ) {
            if(in_array($post->ID,$selected_posts)) {
                $checked = "checked";
            } else {
                $checked = "";
            }
            include( plugin_dir_path( __FILE__ ) . 'views/admin/post.php');
        }
    }
    
    /*
     * Request Handler
     * Handles the admin side form submission with the notification settings
     */
    public function admin_form_submit() {
        if (isset($_POST["form_action"]) && $_POST["form_action"] === "notification_options_update") {
            $model = new notificationmodel();        
            $model->_options["notification_text"] = $_POST["notification_text"];
            $model->_options["taxonomies"] = $_POST["post-type"];
            $model->_options["posts"] = $_POST["post"];
                    
            $model->update_options();
            header('Location: '.get_bloginfo('wpurl').'/wp-admin/options-general.php?page=pushnotification');
            die();
        }
    }
    
    public function init_pushnotification() {
        wp_enqueue_script("jquery");
        wp_enqueue_style("notification-css", plugins_url('/css/styles.css?ver=1', __FILE__));
    }
        
    public function load_pushnotification() {
        global $post;
        $model = new notificationmodel();
        $model->get_options();
        $selected_posts = $model->_options["posts"];
        if(in_array($post->ID,$selected_posts)) {
            $notification_text = $model->_options["notification_text"];
            include( plugin_dir_path( __FILE__ ) . 'views/push-notification.php');
        }
    }
    
    /*
     * Styles and JS for admin
     */
    public function admin_embed_scripts() {
        wp_enqueue_script('notification-admin-js', plugins_url('/js/admin.js?ver=2', __FILE__),array("jquery"));
        wp_localize_script('notification-admin-js', 'ajax_url', admin_url('admin-ajax.php') );
        wp_enqueue_style("notification-admin-css", plugins_url('/css/admin-styles.css?ver=1', __FILE__));
    }
}
pushnotification::initialize();
?>