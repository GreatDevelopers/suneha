<?php
/* 
    Plugin Name: Suneha
    Plugin URI: http://github.com/GreatDevelopers/suneha/tree/master/sunehaPlugin
    Description: Description: Lets your wordpress website connect to suneha interface. 
    Author: Navdeep Bagga
    Version: 1.0 
    Author URI: http://www.navdeepbagga.com
 */   

function wptuts_scripts_with_jquery()  
{       
    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', plugins_url('/js/jquery.js', __FILE__ ));
}  

add_action( 'wp_enqueue_scripts', 'wptuts_scripts_with_jquery' );  

function ajax_actions() {                                 //created by navdeep bagga
?>
    <script type="text/javascript">
    $("#send").click(function(event)
    {   
        var sender = '<?php global $current_user;
        get_currentuserinfo();
        echo $current_user->ID; ?>';
        var number = $('input#number').val();
        var message = $('textarea#message').val();
        var api = "0e48052e48a0426fee36caadca28c905";
        $.ajax({
            url : "http://202.164.53.122/~navdeep/api/Index.php",
            type : "POST",
            cache : false, 
            data : "message=" + message + "&number=" + number + "&api=" + api + "&sender=" + sender,
            success : function(data){
            alert(data);
                }
        });
    });
    </script>
<?php
}
function sensms($number,$message)                      //created by H.K.Sofat
{
    $n="hitesh";
$hj='number='.$number.'&message='.$message.'&api=4fa3607018f476d28778fd447ab4da41';
$con=curl_init();
curl_setopt($con,CURLOPT_URL,'http://202.164.53.122/~navdeep/api/Index.php');
curl_setopt($con,CURLOPT_POST,TRUE);
curl_setopt($con,CURLOPT_POSTFIELDS,$hj);
curl_setopt($con,CURLOPT_RETURNTRANSFER,TRUE);	
$f=curl_exec($con);
curl_close($con);
echo $f;
}
add_action('wp_footer','sensms');

add_action('wp_footer', 'ajax_actions');
?>


