<?php
/* 
    Plugin Name: Add User Page
    Plugin URI: http://www.navdeepbagga.com
    Description: Plugin for add page to Users Profile
    Author: Navdeep Bagga
    Version: 1.0 
    Author URI: http://www.navdeepbagga.com
    */ 

function add_page() {
    add_users_page('FosMIN Plugin', 'FosMIN', 'read', 'unique-identifier', 'fosmin_user');
}
function fosmin_user() {
    global $current_user;
    get_currentuserinfo();
    echo $current_user->user_login;
}
add_action('admin_menu', 'add_page');





?>
