<?php 

global $current_user;
      get_currentuserinfo();

      //echo "Hey ". $current_user->user_login . "\n";echo "<br>";
      $result = mysql_query("SELECT user_activation_key,user_status  FROM wp_users
WHERE ID='$current_user->ID'");

while($row = mysql_fetch_array($result))
  {
  _e("<font color=\"#3D3D3D\"><b><p style=\"font-family:Helvetica; font-size:14px;\">Your Account Information</p></b></font>"); 
  echo "<li>";
  //_e('<p style="font-family:Helvetica; font-size:12px;">You left with '.$row['user_status']." SMS.</p>");echo "</li><li>";
  _e('<p style="font-family:Helvetica; font-size:12px;">Your API key is '.$row['user_activation_key']."</p>" );
  echo "</li>";

}
?>	

  

