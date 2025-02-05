<?php 
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class for Action Items
 *
 * @since  1.0.0
 * @access all
 */
class OSHActionItems 
{
    public function __construct(){
        add_action("wp_ajax_get_action_items_ajax", array($this, "get_action_items_ajax"));
        add_action("wp_ajax_nopriv_get_action_items_ajax", array($this, "get_action_items_ajax"));

        add_action("wp_ajax_clear_action_items_ajax", array($this, "clear_action_items_ajax"));
        add_action("wp_ajax_nopriv_clear_action_items_ajax", array($this, "clear_action_items_ajax"));
    }

    /**
     * Getting all action items
     *
     * @since  1.0.0
     * @access all
     */
    public function get_action_items_ajax(){
        global $wpdb;
        $this->create_table();
        
        if (!is_user_logged_in()) {
            wp_send_json_error(['msg' => esc_html__('You are not logged in!', 'osh')]);
        }

        $user_id = get_current_user_id();
        
        $total_unread_action_items = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(ID) FROM {$wpdb->prefix}action_items WHERE user_id = %d AND is_read IS NULL", 
            $user_id
        ));
        
        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}action_items WHERE user_id = %d ORDER BY created_at DESC LIMIT 10", 
            $user_id
        ));
        
        $html_string = '';
        if (!empty($results)) {
            foreach ($results as $row) {
                $html_string .= '<li>' . esc_html($row->msg) . '</li>';
            }
        } else {
            $html_string .= '<li>' . esc_html__('No new action items!', 'osh') . '</li>';
        }

        wp_send_json_success(['total_unread_action_items' => $total_unread_action_items, 'html' => $html_string]);
    }

    /**
     * Removing action items
     *
     * @since  1.0.0
     * @access all
     */
    public function clear_action_items_ajax() {
        global $wpdb;
        if (!is_user_logged_in()) {
            wp_send_json_error(['msg' => esc_html__('You are not logged in!', 'osh')]);
        }

        $user_id = get_current_user_id();
        $wpdb->update("{$wpdb->prefix}action_items", ['is_read' => 1], ['user_id' => $user_id, 'is_read' => NULL]);

        wp_send_json_success(['msg' => 'success']);
    }

    /**
     * Creating the action_items table
     */
    public function create_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'action_items';
        $charset_collate = $wpdb->get_charset_collate();
        
        if ($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name)) !== $table_name) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            $sql = "CREATE TABLE $table_name (
                ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                user_id bigint(20) unsigned NOT NULL,
                msg text DEFAULT NULL,
                created_at datetime NOT NULL,
                is_read tinyint(4) DEFAULT NULL,
                PRIMARY KEY (ID),
                KEY user_id (user_id),
                KEY is_read (is_read),
                KEY created_at (created_at)
            ) $charset_collate;";
            dbDelta($sql);
        }
    }
}

$actions_items = new OSHActionItems();