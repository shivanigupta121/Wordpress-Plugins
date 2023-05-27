<?php

add_action('admin_init', 'sampleoptions_init_fn');
add_action('admin_menu', 'Plugin_Menu');
function Plugin_Menu()
{
  //add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
  add_menu_page(
    'Shivani Plugin',  // Title of the page
    'Shivani Page', // Text to show on the menu Title
    'custom_admin', // Capability requirement to see the link
    'page1',   //menu slug
    'display_Plugin_Admin_Settings', //callback function
    plugin_dir_url(__FILE__) . 'image/logo.png"',//'dashicons-chart-area',//icon url
    1 //Position of the menu

  );
  
}
  $roles =wp_roles();
  $roles->add_cap('administrator','custom_admin' );
  $roles->add_cap('Custom_New_User','custom_admin');
  // //Parent Menu Function Call
  function sampleoptions_init_fn()
  {
// First, register_setting
// register_setting($option_group, $option_name, array $args = array() )
// Second, we add_settings_section. This is necessary since all future settings must belong to one.
// add_settings_section( $id, $title, $callback, $page, array $args = array() )
// Third, add_settings_field
// add_settings_field($id, $title, $callback, $page, $section = 'default', array $args = array() )
//For HomeTab
    register_setting('register1', 'plugin_options');
    add_settings_section('shivani-plugin', '', 'section_text_fn', 'page1');
    add_settings_field('plugin_text_string', 'Name', 'setting_string_fn', 'page1', 'shivani-plugin');
    add_settings_field('plugin_text_pass', 'Password', 'setting_pass_fn', 'page1', 'shivani-plugin');
    add_settings_field('plugin_email', 'Email', 'setting_email_fn', 'page1', 'shivani-plugin');
    add_settings_field('plugin_textarea_string', 'Comment', 'setting_textarea_fn', 'page1', 'shivani-plugin');
   //For Address Tab
    register_setting('register2', 'address_options');
    add_settings_section('address_option', '', 'section_text_fn', 'page2');
    add_settings_field('plugin_address_string', 'Address', 'setting_address_fn', 'page2', 'address_option');
    add_settings_field('plugin_pincode_string', 'Pin Code', 'setting_pincode_fn', 'page2', 'address_option');
    add_settings_field('plugin_city_string', 'City', 'setting_city_fn', 'page2', 'address_option');
    add_settings_field('plugin_state_string', 'State', 'setting_state_fn', 'page2', 'address_option');
  }

  //Callback Functions Section HTML, displayed before the first option
  function section_text_fn()
  {
    //echo '<p>Below are some examples of different option controls.</p>';
  }
  // TEXTBOX - Name: plugin_options[name_string]
  function setting_string_fn()
  {
    $options = get_option('plugin_options');
    $name = isset($options['name_string']) ? $options['name_string'] : '';
    echo "<input id='plugin_text_string' name='plugin_options[name_string]' size='40' type='text' value='" . $name . "'>";
  }

  // PASSWORD-TEXTBOX - Name: plugin_options[pass_string]
  function setting_pass_fn()
  {
    $options = get_option('plugin_options');
    $password = isset($options['pass_string']) ? $options['pass_string'] : '';
    echo "<input id='plugin_text_pass' name='plugin_options[pass_string]' size='40' type='password' value='" . $password . "' />";
  }
  // EMAIL-TEXTBOX - Name: plugin_options[text_string]
  function setting_email_fn()
  {
    $options = get_option('plugin_options');
    $email = isset($options['email_string']) ? $options['email_string'] : '';
    echo "<input id='plugin_email' name='plugin_options[email_string]' size='40' type='email' value='" . $email . "' />"; //value='{$options['text_string']}'
  }
  // TEXTAREA - Name: plugin_options[text_area]
  function setting_textarea_fn()
  {
    $options = get_option('plugin_options');
    $comment = isset($options['comment_string']) ? $options['comment_string'] : '';
    echo "<input id='plugin_textarea_string' name='plugin_options[comment_string]' size='40' type='text' value='" . $comment . "'>";
  }
  //Address Tab
  //TEXTBOX - Address: plugin_options[address_string]
  function setting_address_fn()
  {
    $options = get_option('address_options');
    $address = isset($options['address_string']) ? $options['address_string'] : '';
    echo "<input id='plugin_address_string' name='address_options[address_string]' size='40' type='text' value='" . $address . "'>";
  }
  //TEXTBOX - Pin Code: plugin_options[pincode_string]
  function setting_pincode_fn()
  {
    $options = get_option('address_options');
    $pincode= isset($options['pincode_string']) ? $options['pincode_string'] : '';
    echo "<input id='plugin_pincode_string' name='address_options[pincode_string]' size='40' type='text' value='" . $pincode . "'>";
  }
//TEXTBOX - City: plugin_options[city_string]
  function setting_city_fn()
  {
    $options = get_option('address_options');
    $city = isset($options['city_string']) ? $options['city_string'] : '';
    echo "<input id='plugin_city_string' name='address_options[city_string]' size='40' type='text' value='" . $city . "'>";
  }
  //TEXTBOX - State: plugin_options[state_string]
  function setting_state_fn()
  {
    $options = get_option('address_options');
    $state = isset($options['state_string']) ? $options['state_string'] : '';
    echo "<input id='plugin_state_string' name='address_options[state_string]' size='40' type='text' value='" . $state . "'>";
  }

// nav tab function
  function admin_tabs($current = 'homepage')
  {
    $tabs = array('homepage' => 'Home', 'address' => 'Address');
   // $links = array();
    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<h1 class="nav-tab-wrapper">';
    foreach ($tabs as $tab => $name) {
      $class = ($tab == $current) ? ' nav-tab-active' : '';
      echo "<a class='nav-tab$class' href='?page=page1&tab=$tab'>$name</a>";

    }
    echo '</h1>';
  }
  // Display the admin options page
  function display_Plugin_Admin_Settings() 
  {
    //settings_errors();
    ?>
    <div class="wrap">
      <div class="icon32" id="icon-options-general"><br></div>
      <h2> <?php echo esc_html(get_admin_page_title()); ?> </h2>
      <?php if (isset($_GET['tab'])) admin_tabs($_GET['tab']);
       else admin_tabs('homepage');
      ?>
      <form action="options.php" method="post">
        <?php if (isset($_GET['tab'])) $tab = $_GET['tab'];
        else $tab = 'homepage';

        echo '<table class="form-table">';
        switch ($tab) {
          case 'homepage':
            ?>
            <tr>
              <th><label for="">Simple Form</label></th>
              <td>
                <?php settings_fields('register1');
                do_settings_sections('page1');
                ?>
                <p class="submit">
                  <input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
                </p>
              </td>
            </tr>
            <?php
            break;
          case 'address':
            ?>
            <tr>
              <th><label for="">Address </label></th>
              <td>
              <?php settings_fields('register2');
                do_settings_sections('page2');
                ?>
                <p class="submit">
                  <input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
                </p>
              </td>
            </tr>
            <?php
            break;
        }
        echo '</table>';?>
        
        </form>
        <?php }?>
        <?php
        // Add Shortcode
        function wpdocs_bartag_func() {
          if(isset($_POST['submit'])){
          $arr1= get_option('formdata');
           if($arr1){
            $arr1 =unserialize($arr1);
          //    echo "<pre>";
          // print_r($arr1);
          }else{
            $arr1= array();
          }
      
        $arr1[] = array(
          'realname'=>sanitize_text_field($_POST['realname']),
          //update_option('fname', $name);
          'email'=>sanitize_text_field($_POST['email']),
         // update_option('email', $email);
          'password'=>sanitize_text_field($_POST['password'])
        );

         
           $arr1 =serialize($arr1);
         update_option('formdata', $arr1);}
        ?>
            <form name="custom_form" id="custom_form" method="post">
          
                <input type="text" name="realname" placeholder="Your Name" style="width:100%;"required><br><hr>                
                 <input type="email" name="email" placeholder="E-mail" style="width:100%;" required><br><hr> 
                 <input type="password" name="password" placeholder="Password"style="width:100%;" required><br><br>
                <button  type="submit" name="submit"  style="width:20%;">Submit</button>
            </form>
<?php
}
add_shortcode( 'custom_form', 'wpdocs_bartag_func');
//display shortcode
add_shortcode( 'display', 'WCSGetPostData');

function WCSGetPostData() {
  $value= get_option('formdata');
  $values=unserialize($value);
  //  echo '<pre>';
  //  print_r($values);
        ?>
        <table>
          <th> Name</th>
          <th>Emaiil</th>
          <th>Password</th>
          <?php foreach($values as $key => $val ) {?>
          <tr>
           <td> <?php echo $val['realname']?></td>
          <td> <?php echo $val['email'] ?></td>
          <td> <?php echo $val['password'] ?></td> 
          </tr>
<?php  } ?>
</table>
 <?php   }
 require_once plugin_dir_path( __FILE__ ).'functions.php'; 
    ?>
  
  