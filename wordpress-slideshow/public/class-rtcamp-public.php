<?php

/**
 * Custom WordPress shortcode for displaying a slideshow.
 *
 * This shortcode retrieves images from the 'rtcamp_images' option in the WordPress database and displays them as a slideshow.
 * It uses the Slick slider library to create the slideshow.
 *
 */
function rtcamp_my_slideshow_shortcode($atts) {
   
    $rtcamp_images = get_option('rtcamp_images');
    // Create HTML markup for the slideshow
    $rtcamp_output = '<div class="rtcamp-slideshow">';
    $rtcamp_output .= '<div class="rtcamp-slick-slider">'; // Create a container for the slider

    foreach ($rtcamp_images as $rtcamp_image_url) {
        $rtcamp_output .= '<div><img src="' . esc_url($rtcamp_image_url) . '" alt="Slideshow Image"></div>';
    }
    $rtcamp_output .= '</div>'; // Close the slider container
    $rtcamp_output .= '</div>';
    // Include JavaScript to initialize the Slick slider
    $rtcamp_output .= '<script>';
    $rtcamp_output .= 'jQuery(document).ready(function($) {';
    $rtcamp_output .= '$(".rtcamp-slick-slider").slick();'; // Initialize the slider
    $rtcamp_output .= '});';
    $rtcamp_output .= '</script>';
   
    // Enqueue Slick slider JavaScript
    wp_enqueue_script('rtcamp-slick-slider', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), '1.8.1', true);

    // Enqueue Slick slider CSS
    wp_enqueue_style('rtcamp-slick-slider-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.css');
    wp_enqueue_style('rtcamp-public-styles', plugin_dir_url(__FILE__) . 'css/rtcamp-public.css');

    return $rtcamp_output;
}
add_shortcode('rtcamp_my_slideshow', 'rtcamp_my_slideshow_shortcode');
