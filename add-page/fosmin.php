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


function footer_fun () {
echo "
<SCRIPT type=\"text/javascript\">
$(\"#send\").click(function(event)
    {   
        var sender = '<?php global $current_user;
      get_currentuserinfo();
      echo $current_user->ID; ?>';
        var number = $('input#number').val();
        var message = $('textarea#message').val();
        var api = \"4fb3f1a5aea825cd5b3c2a835e79ed57\";
        $.ajax({
            url : \"http://localhost/api/Index.php\",
            type : \"POST\",
            cache : false, 
            data : \"message=\" + message + \"&number=\" + number + \"&api=\" + api + \"&sender=\" + sender,
            success : function(data){
            alert(data);
                }
        });
            
    });
</SCRIPT>
";

}
add_action('wp_footer', 'footer_fun');

?>
