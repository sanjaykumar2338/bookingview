<?php
/*
Plugin Name: Schedule a Tour
Description: A plugin to schedule a tour for listings with a two-step form.
Version: 1.0
Author: Your Name
*/

// Create database table for storing scheduled tours
function create_tour_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tour_schedule';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        post_id bigint(20) NOT NULL,
        name varchar(100) NOT NULL,
        email varchar(100) NOT NULL,
        phone varchar(20) NOT NULL,
        tour_date date NOT NULL,
        tour_time time NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

register_activation_hook(__FILE__, 'create_tour_table');

// Shortcode to display the tour scheduling form
function display_tour_form($atts) {
    ob_start();
    include plugin_dir_path(__FILE__) . 'templates/tour-form.php';
    return ob_get_clean();
}
add_shortcode('schedule_tour', 'display_tour_form');

// Handle form submission
function handle_tour_form_submission() {
    if (isset($_POST['tour_submit'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'tour_schedule';
        
        $post_id = intval($_POST['post_id']);
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $tour_date = sanitize_text_field($_POST['tour_date']);
        $tour_time = sanitize_text_field($_POST['tour_time']);

        $wpdb->insert(
            $table_name,
            [
                'post_id' => $post_id,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'tour_date' => $tour_date,
                'tour_time' => $tour_time
            ]
        );

        $redirect_url = add_query_arg('tour_success', '1', get_permalink($post_id)) . '#tourForm'; // Add fragment identifier
        wp_redirect($redirect_url);
        exit;
    }
}
add_action('admin_post_nopriv_tour_form', 'handle_tour_form_submission');
add_action('admin_post_tour_form', 'handle_tour_form_submission');

// Create an admin menu for viewing the scheduled tours
function tour_schedule_menu() {
    add_menu_page(
        'Tour Schedule',
        'Tour Schedule',
        'manage_options',
        'tour-schedule',
        'tour_schedule_page'
    );
}
add_action('admin_menu', 'tour_schedule_menu');

// Display the scheduled tours in the admin panel
function tour_schedule_page() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'tour_schedule';

    // Define the number of records per page
    $records_per_page = 10;

    // Get the current page number from query string (default to 1 if not set)
    $paged = isset($_GET['paged']) ? absint($_GET['paged']) : 1;

    // Calculate the offset (how many records to skip)
    $offset = ($paged - 1) * $records_per_page;

    // Fetch the total number of records
    $total_records = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

    // Calculate total number of pages
    $total_pages = ceil($total_records / $records_per_page);

    // Fetch the records for the current page using LIMIT and OFFSET
    $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name LIMIT %d OFFSET %d", $records_per_page, $offset));

    echo '<h2>Scheduled Tours</h2>';
    echo '<table class="widefat fixed" cellspacing="0">';
    echo '<thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Date</th><th>Time</th><th>View Property</th></tr></thead>';
    echo '<tbody>';
    foreach ($results as $row) {
        echo '<tr>';
        echo '<td>' . esc_html($row->name) . '</td>';
        echo '<td>' . esc_html($row->email) . '</td>';
        echo '<td>' . esc_html($row->phone) . '</td>';
        echo '<td>' . esc_html($row->tour_date) . '</td>';
        echo '<td>' . esc_html($row->tour_time) . '</td>';
        echo '<td><a href="' . get_permalink($row->post_id) . '" target="_blank">' . esc_html(get_the_title($row->post_id)) . '</a></td>';
        echo '</tr>';
    }
    echo '</tbody></table>';

    // Display pagination links if there are multiple pages
    if ($total_pages > 1) {
        echo '<div class="pagination" style="margin-top: 20px;">';
        if ($paged > 1) {
            echo '<a class="prev-page button" href="' . add_query_arg('paged', $paged - 1) . '">&laquo; Previous</a>';
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $paged) {
                echo '<span class="current-page button">' . $i . '</span>';
            } else {
                echo '<a class="button" href="' . add_query_arg('paged', $i) . '">' . $i . '</a>';
            }
        }

        if ($paged < $total_pages) {
            echo '<a class="next-page button" href="' . add_query_arg('paged', $paged + 1) . '">Next &raquo;</a>';
        }
        echo '</div>';
    }
}
?>
