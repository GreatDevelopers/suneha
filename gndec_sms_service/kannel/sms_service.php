<?php
require_once('class.phpmailer.php');
require_once('functions.php');
require_once('config.php');

$phone = $_GET['phone'];
$msg = $_GET['text'];

$str_ph = explode("+91",$phone);
$alphabets = array('a','b','c','d','e','f','g','h','i','j','k','m','n','p','q','r','s','t','u','v','w','x','y','z');

for($i=0;$i<=2;$i++) {
 $rand_no[] = rand(2,9);
 $rand_al[] = $alphabets[rand(0,24)];
}

$newpwd = $rand_al[0].$rand_no[0].$rand_al[1].$rand_no[1].$rand_al[2].$rand_no[2];

$conn = mysql_connect($db_hostname,$db_username,$db_password);
mysql_select_db($db_name,$conn);

$details = explode(",",$msg);

if(strtolower($details[0])=='cpw') {
	
	changePassword($newpwd);
    
}

elseif(strtolower($details[0])=='s2m') {
	
  smsToEmail($details);
		
}

elseif(strtolower($details[0])=='s2gd') {
	
  smsToGD($details);
		
}


elseif(strtolower($details[0])=='lnws') {
	
	updateNews($details);
	
}

elseif(strtolower($details[0])=='llnws') {
	
  listNews($details);
  
}

elseif(strtolower($details[0])=='rlnws') {
	
  removeNews($details);
	
}


elseif(strtolower($details[0])=='chkmail') {
	
  checkGmail($details);
  
}


elseif(strtolower($details[0])=='yudl') {
	
  youtubeDl($details);
	
}


else {
    mailReceivedSMS($phone,$msg);
}

?>
