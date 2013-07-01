<?php
if(isset($_POST['keyvalue']))
{
      stroekey($_POST['keyvalue']);
}

global $current_user;
      get_currentuserinfo();
      $result = mysql_query("SELECT user_activation_key,user_status  FROM wp_users
WHERE ID='$current_user->ID'");

while($row = mysql_fetch_array($result))
  {
  _e("<font color=\"#3D3D3D\"><b><p style=\"font-family:Helvetica; font-size:14px;\">Your Account Information</p></b></font>");
  echo "<li>";
//  _e('<p style="font-family:Helvetica; font-size:12px;">You left with '.$row['user_status']." SMS.</p>");echo "</li><li>";
if(strlen($row['user_activation_key'])<10)
{
	$str="4fa3607018f476d28778fd447ab4da41";
	$str1=str_shuffle($str);
	if(strlen($row['user_activation_key'])<10)
	{
		echo "PLEASE ENTER YOUR KEY AND SUBMIT. THEN YOU USE THIS PLUGIN<br>";
		echo "<h3>YOUR PLUGIN KEY IS</h3> ".$str1;
echo "<legend><label><form action='admin.php?page=FOSMIN' method='post'>ENTER PLUGIN KEY</label><input type='text' name='keyvalue' style='width:270px'><input type='submit' name='keystore' value='SAVE'></form></legend>";

	}else{
		echo "<h3>YOUR PLUGIN KEY IS SAVE</h3> " .$row['user_activation_key'];
	}
	
}else{
	
_e('<p style="font-family:Helvetica; font-size:12px;">Your API key is<b> '.$row['user_activation_key']."</b></p>" );
  echo "</li>";
}
}
?>


  

