<?php
add_action('admin_enqueue_scripts', 'enqueue_custom_plugin_styles');
add_action('admin_init', 'register_plugin_settings');
add_action('init', 'register_car_post_type');
   
// Enqueue the CSS file.
function enqueue_custom_plugin_styles() {
    // Define the path to your CSS file.
    $css_file_path = plugin_dir_url(__FILE__) . 'css/admin-styles.css';
  
    wp_enqueue_style('custom-plugin-styles', $css_file_path);
}
// register settings function
function register_plugin_settings() {

    register_setting('plugin-settings-group', 'enable_frontend_form');
    register_setting('plugin-settings-group', 'selected_pages');
    register_setting('plugin-settings-group', 'custom_car_form');

    register_setting('plugin-settings-group', 'enable_car_listing');
    register_setting('plugin-settings-group', 'selected_car_list_page');
    register_setting('plugin-settings-group', 'custom_car_listing');

}
//register custom post type car
function register_car_post_type() {
    $labels = array(
        'name' => 'Car Details',
        'singular_name' => 'Car',
        'add_new' => 'Add New Car',
        'add_new_item' => 'Add New Car',
        'edit_item' => 'Edit Car',
        'new_item' => 'New Car',
        'view_item' => 'View Car',
        'search_items' => 'Search Cars',
        'not_found' => 'No cars found',
        'not_found_in_trash' => 'No cars found in Trash',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-car', // Choose an appropriate menu icon
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
    );

    register_post_type('car', $args);

    // Register Custom Taxonomy (Model)
    $taxonomy_labels = array(
        'name' => 'Car Models',
        'singular_name' => 'Model',
        'search_items' => 'Search Models',
        'popular_items' => 'Popular Models',
        'all_items' => 'All Models',
        'parent_item' => 'Parent Model',
        'parent_item_colon' => 'Parent Model:',
        'edit_item' => 'Edit Model',
        'update_item' => 'Update Model',
        'add_new_item' => 'Add New Model',
        'new_item_name' => 'New Model Name',
    );

    $taxonomy_args = array(
        'hierarchical' => true,
        'labels' => $taxonomy_labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'model_taxonomy'),
    );    
    register_taxonomy('model_taxonomy', 'car', $taxonomy_args);
}
add_action('admin_menu', 'menu_function');
function menu_function() {
    add_submenu_page('edit.php?post_type=car', 'Car Settings', 'Car Settings', 'manage_options', 'car_settings', 'display_car_settings_page');
}

// menu submenu function
function display_car_settings_page() {
    if (isset($_POST['submit_car_settings'])) {
        // Check the nonce to verify the form submission
        if (isset($_POST['car_settings_nonce']) && wp_verify_nonce($_POST['car_settings_nonce'], 'car_settings_nonce')) {
            update_option('enable_frontend_form', isset($_POST['enable_frontend_form']) ? 1 : 0);
            update_option('selected_page', sanitize_text_field($_POST['selected_page']));
            update_option('enable_car_listing', isset($_POST['enable_car_listing']) ? 1 : 0);
            update_option('selected_car_list_page', sanitize_text_field($_POST['selected_car_list_page']));

        }
    }
    ?>
    <div class="custom-heading">
        <h2>Welcome to My Custom Plugin</h2>
    </div>
    <div class="custom-wrap">
        <h3>Admin Settings</h3>
        <form method="post">
            <?php
            // Add a security nonce to the form
            wp_nonce_field('car_settings_nonce', 'car_settings_nonce');
            ?>

            <table class="custom-form-table">
                <tr>
                    <th>Enable Form in Frontend:</th>
                    <td>
                        <div class="toggle-switch">
                            <input type="checkbox" id="enable_frontend_form" name="enable_frontend_form" value="1" <?php checked(get_option('enable_frontend_form'), 1); ?> />
                            <label class="slider" for="enable_frontend_form"></label>
                        </div>
                        <br>
                        <em class="custom-desc">Enable or disable the form on the frontend.</em>
                    </td>
                </tr>
                <tr>
                    <th>Select Page to Show Form On:</th>
                    <td>
                        <select name="selected_page" id="selected_page">
                            <option value="0">Select a Page</option>
                            <?php
                            $pages = get_pages();
                            $selected_page = get_option('selected_page', 0);
                            foreach ($pages as $page) {
                                $selected = ($page->ID == $selected_page) ? 'selected' : '';
                                echo '<option value="' . $page->ID . '" ' . $selected . '>' . esc_html($page->post_title) . '</option>';
                            }
                            ?>
                        </select>
                        <br>
                        <em class="custom-desc">Select the page where the form will be displayed.</em>
                    </td>
                </tr>
                <tr>
                    <th>Custom Car Form:</th>
                    <td>
                    <a href="<?php echo admin_url('post-new.php?post_type=page'); ?>">Click here</a> to create a custom car form page.
                        <br>
                        <em class="custom-desc">Show a create a custom car form page and add the [car_add] shortcode.</em>
                    </td>
                </tr>
                <tr>
                    <th>Enable Car Listing:</th>
                    <td>
                        <div class="toggle-switch">
                            <input type="checkbox" id="enable_car_listing" name="enable_car_listing" value="1" <?php checked(get_option('enable_car_listing'), 1); ?> />
                            <label class="slider" for="enable_car_listing"></label>
                        </div>
                        <br>
                        <em class="custom-desc">Enable or disable car listings.</em>
                    </td>
                </tr>
                <tr>
                    <th>Selected Car List Page:</th>
                    <td>
                        <select name="selected_car_list_page" id="selected_car_list_page">
                            <option value="0">Select a Page</option>
                            <?php
                            $pages = get_pages();
                            $selected_car_list_page = get_option('selected_car_list_page', 0);
                            foreach ($pages as $page) {
                                $selected = ($page->ID == $selected_car_list_page) ? 'selected' : '';
                                echo '<option value="' . $page->ID . '" ' . $selected . '>' . esc_html($page->post_title) . '</option>';
                            }
                            ?>
                        </select>
                        <br>
                        <em class="custom-desc">Select the page to display car listings.</em>
                    </td>
                </tr>
                <tr>
                    <th>Custom Car Listing:</th>
                    <td>
                        <a href="<?php echo admin_url('post-new.php?post_type=page'); ?>">Click here</a> to create a custom car listing page.
                        <br>
                        <em class="custom-desc">Show a create a custom car listing page and add the [car_listing] shortcode.</em>
                    </td>
                </tr>
                
            </table>
            <input type="submit" name="submit_car_settings" class="button-primary" value="Save Settings" />
        </form>
    </div>
    <?php
}
