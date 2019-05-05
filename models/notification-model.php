<?php

/* Notification Model */

class notificationmodel {
    
    public $_options;
    
    public function __construct() {
        $this->_options = array ("notification_text" => "","taxonomies" => array(),"posts" => array());
    }
    
    public function update_options() {
        if (current_user_can('manage_options')) {
            foreach ($this->_options as $key => $value) {
                update_option('pushnotification_'.$key, $value);
            }
        }
    }
    
    public function get_options() {
        $exists = get_option('pushnotification_notification_text');
        if ($exists) {
            foreach ($this->_options as $key => $value) {
                $this->_options[$key] = get_option('pushnotification_'.$key);
            }
        }
    }
    
    /*
     * Insert subscription fields
     */
    public function get_all_post_types() {
        $post_types = get_post_types();
        return $post_types;
    }
    
    /*
     * Search for a subscription using similar first_name or email
     */
    public function list_posts_by_taxonomy($taxonomy) {
        $posts = get_posts([
            'post_type' => $taxonomy,
            'post_status' => 'publish',
            'numberposts' => -1
        ]);
        return $posts;
    }
}
?>