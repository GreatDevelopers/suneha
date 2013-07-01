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
    add_users_page('Suneha Plugin', 'FosMIN', 'read', 'FOSMIN', 'fosmin_user');
}
function fosmin_user() {
    global $current_user;
    get_currentuserinfo();
   // echo $current_user->user_login;
include 'api_information.php';
include 'account_information.php';
echo '<list><li><a href="' . plugins_url( 'msgdetails/tests/test.php?id='.$current_user->ID , __FILE__ ) . '">Download Message Log</a></li></list>';

}
function stroekey($key)
{
global $current_user;
    $qry="update wp_users set `user_activation_key`='".$key."' where ID='".$current_user->ID."'";
	$result=mysql_query($qry);
		$qry1="select * from wp_users where ID='".$current_user->ID."'";
	$result1=mysql_query($qry1);
	$data1=mysql_fetch_array($result1);
echo "<h3>YOUR PLUGIN KEY IS SAVE</h3> " .$data1['user_activation_key'];
}

add_action('admin_menu', 'add_page');
?>
