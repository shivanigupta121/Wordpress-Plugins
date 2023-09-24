<?php

add_action('admin_enqueue_scripts', 'rtcamp_enqueue_styles');
add_action('admin_menu', 'rtcamp_wp_slideshow_menu');
add_action('admin_init', 'rtcamp_admin_init');

/**
 * Create a WordPress admin menu for the WordPress Slideshow plugin.
 *
 * This function adds a custom menu item to the WordPress admin dashboard menu.
 * Users with the 'manage_options' capability can access this menu.
 * The menu item is linked to the 'rtcamp_slideshow_page' page, and it displays
 * the 'WordPress Slideshow' menu title with a 'dashicons-images-alt2' icon.
 * The menu item has a priority of 1.
 */
function rtcamp_wp_slideshow_menu()
{
    add_menu_page(
        'WordPress Slideshow',
        'WordPress Slideshow',
        'manage_options',
        'rtcamp_slideshow_page', 
        'rtcamp_settings_page',
        'dashicons-images-alt2',
        1
    );

}

/**
 * Enqueue custom styles for the WordPress admin panel.
 *
 * This function is used to load a custom CSS stylesheet that will be applied
 * to the WordPress admin pages.
 */
function rtcamp_enqueue_styles()
{
  wp_enqueue_style('rtcamp-admin-styles', plugin_dir_url(__FILE__) . 'css/rtcamp-styles.css');
    
}

/**
 * Initialize the admin settings and fields.
 *
 * This function is called during the WordPress admin initialization process.
 * It registers custom settings, sections, and fields for a specific settings group.
 * These settings and fields are displayed in the WordPress admin panel.
 */
function rtcamp_admin_init()
{
  register_setting('rtcamp-settings-group', 'rtcamp_images');
  add_settings_section('rtcamp_section', '', 'rtcamp_section_callback', 'rtcamp-settings');

  add_settings_field('rtcamp_images_field', 'Images', 'rtcamp_images_field_callback', 'rtcamp-settings', 'rtcamp_section');
  add_settings_field('rtcamp_image_field_preview', 'Image Preview', 'rtcamp_image_field_preview_callback', 'rtcamp-settings', 'rtcamp_section');

}

// Section callback
function rtcamp_section_callback()
{
  return;
}
/**
 * Sanitizes and validates a list of image URLs.
 *
 * This function takes an array of image URLs as input and processes each URL individually.
 * It checks if each URL is a valid URL using the `FILTER_VALIDATE_URL` filter. If a URL is
 * valid, it is further sanitized using `esc_url_raw` to ensure it is safe for storage.
 *
 */
function rtcamp_sanitize_images($rtcamp_input)
{
  $rtcamp_sanitized_input = array();

  foreach ($rtcamp_input as $rtcamp_url) {
    if (filter_var($rtcamp_url, FILTER_VALIDATE_URL)) {
      $rtcamp_sanitized_input[] = esc_url_raw($rtcamp_url);
    }
  }

  return $rtcamp_sanitized_input;
}

/**
 * Callback function to display a list of image URLs.
 *
 * This function is used as a callback to display a list of image URLs in the WordPress admin panel.
 * Users can add, edit, or remove image URLs from the list.
 */
function rtcamp_images_field_callback()
{
  $rtcamp_images = get_option('rtcamp_images');
  ?>
  <ul id="rtcamp-images-list">
    <?php if ($rtcamp_images && is_array($rtcamp_images)): ?>
      <?php foreach ($rtcamp_images as $index => $rtcamp_url): ?>
        <li>
          <input type="text" name="rtcamp_images[]" value="<?php echo esc_attr($rtcamp_url); ?>" class="regular-text">
          <button class="button rtcamp-remove-image">Remove</button>
        </li>
      <?php endforeach; ?>
    <?php endif; ?>
  </ul>
  <button class="button" id="rtcamp-add-image">Add Image</button>
  <p class="rtcamp-description">Add Image Url.</p>
  <?php
}

/**
 * Callback function to display an image selection field with a preview.
 *
 * This function is used as a callback to display an image selection field with a preview
 * in the WordPress admin panel. Users can select an image from a dropdown list, and
 * a preview of the selected image is displayed.
 */
function rtcamp_image_field_preview_callback()
{
  $rtcamp_images = get_option('rtcamp_images');
  ?>
    <select id="rtcamp-image-select" name="rtcamp_select_image">
      <option value="">Select an Image</option>
      <?php foreach ($rtcamp_images as $index => $rtcamp_url): ?>
        <option value="<?php echo esc_attr($rtcamp_url); ?>"><?php echo esc_attr($rtcamp_url); ?></option>
      <?php endforeach; ?>
    </select>
    <div class="rtcamp-selected-image-preview">
      <img src="" alt="Selected Image Preview">
    </div>
    <script>
      jQuery(document).ready(function ($) {

        $('#rtcamp-add-image').on('click', function (e) {
          e.preventDefault();
          $('#rtcamp-images-list').append('<li><input type="text" name="rtcamp_images[]" class="regular-text"><button class="button rtcamp-remove-image">Remove</button></li>');
        });

        $('body').on('click', '.rtcamp-remove-image', function () {
          $(this).parent().remove();
        });

        // Check if there's a stored selected value in local storage
        var rtcampSelectedImageUrl = localStorage.getItem('rtcampSelectedImageUrl');

        if (rtcampSelectedImageUrl !== null) {
          // Set the selected value if it's found in local storage
          $('#rtcamp-image-select').val(rtcampSelectedImageUrl);
          rtcampUpdateImagePreview(rtcampSelectedImageUrl);
        }

        $('#rtcamp-image-select').on('change', function () {
          var rtcampSelectedImageUrl = $(this).val();

          if (rtcampSelectedImageUrl === "") {
            // User selected "Select an Image," hide the preview
            rtcampUpdateImagePreview('');
          } else {
            // User selected an image other than "Select an Image"
            rtcampUpdateImagePreview(rtcampSelectedImageUrl);
          }

          // Store the selected value in local storage
          localStorage.setItem('rtcampSelectedImageUrl', rtcampSelectedImageUrl);
        });

        function rtcampUpdateImagePreview(imageUrl) {
          $('.rtcamp-selected-image-preview img').attr('src', imageUrl);
          if (imageUrl === '') {
            $('.rtcamp-selected-image-preview').hide();
          } else {
            $('.rtcamp-selected-image-preview').show();
          }
        }
      });
    </script>
  <?php
}

/**
 * Display the settings page for the WordPress Slideshow Settings.
 *
 * This function outputs the HTML content for the settings page.
 * It includes a form where users can configure and save plugin settings.
 */
function rtcamp_settings_page()
{
  ?>
  <div class="rtcamp-wrap">
    <h2>Wordpress Slideshow Settings</h2>
    <form method="post" action="options.php" class="rtcamp-form-css" enctype="multipart/form-data">
      <?php
      settings_fields('rtcamp-settings-group');
      do_settings_sections('rtcamp-settings');
      submit_button('Save Changes');
      ?>
    </form>
  </div>
  <?php
}
