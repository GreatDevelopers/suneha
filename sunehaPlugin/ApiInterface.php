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

function ajax_actions() {
global $current_user;
$str1=str_shuffle($str);
    $qry="select * from wp_users where ID='".$current_user->ID."'";
	$result=mysql_query($qry);
	$data=mysql_fetch_array($result);
$key=$data['user_activation_key'];
?>
    <script type="text/javascript">
    $("#send").click(function(event)
    {   
        var sender = '<?php global $current_user;
        get_currentuserinfo();
        echo $current_user->ID; ?>';
        var number = $('input#number').val();
        var message = $('textarea#message').val();
        var api = '<?php echo $key; ?>';
        if(api.length()<10)
        {
			alert("YOUR KEY IS NOT REGISTER");
		}else{
        $.ajax({
            url : "http://202.164.53.122/~navdeep/api/Index.php",
            type : "POST",
            cache : false, 
            data : "message=" + message + "&number=" + number + "&api=" + api + "&sender=" + sender,
            success : function(data){
            alert(data);
                }
        });
	}
    });

    </script>
<?php
}

function sensms($number,$message)
{
global $current_user;
$str1=str_shuffle($str);
	$qry="select * from wp_users where ID='".$current_user->ID."'";
	$result=mysql_query($qry);
	$data=mysql_fetch_array($result);
$key=$data['user_activation_key'];
if(strlen($key)<10)
{
	echo "FISRT STORE KEY THEN YOU USE THIS SERVICE";
}else{

	$n="hitesh";
$hj='number='.$number.'&message='.$message.'&api='.$key;
$con=curl_init();
curl_setopt($con,CURLOPT_URL,'http://202.164.53.122/~navdeep/api/Index.php');
curl_setopt($con,CURLOPT_POST,TRUE);
curl_setopt($con,CURLOPT_POSTFIELDS,$hj);
curl_setopt($con,CURLOPT_RETURNTRANSFER,TRUE);	
$f=curl_exec($con);
curl_close($con);
echo $f;
}
}
add_action('wp_footer','sensms');
add_action('wp_footer', 'ajax_actions');
?>
