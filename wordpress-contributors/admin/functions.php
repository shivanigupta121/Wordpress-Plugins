<?php

add_action( 'add_meta_boxes', 'rtcamp_notice_meta_box');
add_action( 'save_post', 'rtcamp_save_selected_authors' );
add_filter( 'the_content', 'rtcamp_append_contributors_box' );

/**
 * Adds a meta box to WordPress posts and pages.
 *
 * This function is responsible for registering a meta box that can be displayed on specific screens
 * within the WordPress admin panel, such as post editing screens.
 *
 * @since 1.0.0
 */
function rtcamp_notice_meta_box() {
    
    // This function adds a meta box to a WordPress post or page.
    $rtcamp_screens = array( 'post', 'page');

    foreach ( $rtcamp_screens as $rtcamp_screen ) {
        add_meta_box(
            'rtcamp-notice',                    // Unique ID for the meta box.
            esc_html__( 'Contributors ', 'rtcamp'),     // Title of the meta box, translated using the 'rtcamp' text domain.
            'rtcamp_notice_meta_box_callback',  // Callback function to display the contents of the meta box.
            $rtcamp_screen                      // The screen or post type where the meta box should be displayed.
        );

    }
}

/**
 * Callback function to display the content of the "Contributors" meta box.
 *
 * This function is responsible for rendering the content of the meta box.
 * It displays a textarea input for a custom field, adds a nonce field for security,
 * and lists all WordPress users with roles 'author', 'editor', and 'administrator'
 * along with checkboxes for selecting authors.
 * 
 * @since 1.0.0
 */

function rtcamp_notice_meta_box_callback( $rtcamp_post ) {

    // Add a nonce field so we can check for it later.
    wp_nonce_field( 'rtcamp_notice_nonce', 'rtcamp_notice_nonce' );

    $rtcamp_value = get_post_meta( $rtcamp_post->ID, '_rtcamp_notice', true );

    echo '<textarea style="width:100%" id="rtcamp_notice" name="rtcamp_notice">' . esc_attr( $rtcamp_value ) . '</textarea>';
    $rtcamp_allowed_roles = array( 'author', 'editor', 'administrator' );

    // Get all WordPress users with the allowed roles.
    $rtcamp_users = get_users( array( 'role__in' => $rtcamp_allowed_roles ) );

    // Check if there are users to display.
    if ( ! empty( $rtcamp_users ) ) {
        // Translate and display the "Select Authors" heading.
        echo '<h4>' . esc_html__( 'Select Authors:', 'rtcamp' ) . '</h4>';
        
        foreach ( $rtcamp_users as $rtcamp_user ) {
            $rtcamp_user_id = $rtcamp_user->ID;
            $rtcamp_user_name = esc_html( $rtcamp_user->display_name );
            echo '<label><input type="checkbox" name="selected_authors[]" value="' . esc_attr( $rtcamp_user_id ) . '" />' . $rtcamp_user_name . '</label><br>';
        }
    } else {
        // Translate and display the "No authors found" message.
        echo '<p>' . esc_html__( 'No authors found.', 'rtcamp' ) . '</p>';
    }

}

/**
 * Saves the selected authors for a post.
 *
 * This function is hooked into the WordPress 'save_post' action, which is triggered when a post is saved or updated.
 * It performs several checks and updates the post meta based on the selected authors.
 * @since 1.0.0
 */

function rtcamp_save_selected_authors( $rtcamp_post_id ) {
    // Check if this is an autosave or a revision.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check if the current user has permission to edit the post.
    if ( ! current_user_can( 'edit_post', $rtcamp_post_id ) ) {
        return;
    }

    // Check if the selected_authors field is present in the $_POST data.
    if ( isset( $_POST['selected_authors'] ) ) {
        // Sanitize and save the selected authors as an array.
        $rtcamp_selected_authors = array_map( 'intval', $_POST['selected_authors'] );
        update_post_meta( $rtcamp_post_id, 'selected_authors', $rtcamp_selected_authors );
    } else {
        // If no authors were selected, delete the custom field.
        delete_post_meta( $rtcamp_post_id, 'selected_authors' );
    }
}

/**
 * Appends a "Contributors" box to the post content.
 *
 * This function is used as a content filter in WordPress. It checks if the current post has selected authors and, if so,
 * it appends a box containing contributor information to the post content.
  * @since 1.0.0
 */

function rtcamp_append_contributors_box( $rtcamp_content ) {
    // Get the post ID.
    $rtcamp_post_id = get_the_ID();

    // Check if the current post has selected authors.
    $rtcamp_selected_authors = get_post_meta( $rtcamp_post_id, 'selected_authors', true );

    if ( ! empty( $rtcamp_selected_authors ) ) {
        // Initialize the HTML for the "Contributors" box.
        $rtcamp_contributors_box = '<div class="rtcamp-contributors-box">';
        $rtcamp_contributors_box .= '<h2>' . esc_html__( 'Contributors', 'rtcamp' ) . '</h2>';

        // Loop through selected author IDs and display their information.
        foreach ( $rtcamp_selected_authors as $rtcamp_author_id ) {
            $rtcamp_author = get_userdata( $rtcamp_author_id );
            if ( $rtcamp_author ) {
                $rtcamp_author_name = esc_html( $rtcamp_author->display_name );
                $rtcamp_author_link = esc_url( get_author_posts_url( $rtcamp_author_id ) );
                $rtcamp_author_avatar = get_avatar( $rtcamp_author_id, 64 );
                
                // Display the contributor's name, avatar, and link to their author page.
                $rtcamp_contributors_box .= '<div class="rtcamp-contributor">';
                $rtcamp_contributors_box .= '<a href="' . $rtcamp_author_link . '">' . $rtcamp_author_avatar . '</a>';
                $rtcamp_contributors_box .= '<a href="' . $rtcamp_author_link . '">' . $rtcamp_author_name . '</a>';
                $rtcamp_contributors_box .= '</div>';
            }
        }

        // Close the "Contributors" box.
        $rtcamp_contributors_box .= '</div>';

        // Append the "Contributors" box to the post content.
        $rtcamp_content .= $rtcamp_contributors_box;
    }

    return $rtcamp_content;
}
