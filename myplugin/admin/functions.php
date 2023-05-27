<?php
// function removerole(){
//     remove_role('custom_role');
// }
// add_action('init','removerole');
function removecapabilites(){
    remove_menu_page('edit-comments.php');
    remove_menu_page('tools.php');
}
add_action('admin_menu','removecapabilites');
function add_custom_role(){
   add_role('Custom_New_User','Custom New User',
   array(
        'read' => true,
        'edit_posts'   => true,
        'edit_others_posts'=>true,
        'edit_private_posts'=>true,
        'delete_others_posts'=>true,
        'delete_posts' => true,
        'edit_pages' => true,
        'edit_others_pages'=>true,
        'edit_published_pages'=>true,
        'edit_private_pages'=>true,
        'delete_others_pages'=>true,
        'delete_pages'=>true,
        'delete_published_pages'=>true,
        'publish_pages'=>true,

    )
   
    );
$edit_contributor = get_role('Custom_New_User');
//print_r($edit_contributor);

//# Contributor  add capabilites 
$edit_contributor->add_cap('edit_published_posts');
$edit_contributor->add_cap('manage_options');
$edit_contributor->add_cap('publish_posts');
$edit_contributor->add_cap('edit_dashboard');
//$edit_contributor->add_cap('upload_files');//media
$edit_contributor->add_cap('delete_published_posts');
# Contributor  remove capabilites
$edit_contributor->remove_cap('upload_files');
$edit_contributor->remove_cap('read_private_posts');
$edit_contributor->remove_cap('moderate_comments');
}
   
add_action('init', 'add_custom_role');
?>

<?php
//I have used below code snippet to check current user role & capability.


// if( ! function_exists( 'prefix_current_user_details' ) ) :
//     function prefix_current_user_details()
//     {
//           $current_user = wp_get_current_user();
//           echo '<pre>';
//           print_r( $current_user );
//           wp_die();
//     }
//     add_action( 'admin_head', 'prefix_current_user_details' );
// endif;
?>