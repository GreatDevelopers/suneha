<?php
include 'ldap_db.php';
if ($ldapConn) { 
    $ldapBind=ldap_bind($ldapConn, $ldapLogin, $ldapPass);     
    $activation=md5($user_id.$plaintext_pass);
        // ldap add    
        $info["uid"] = $user_id;
        $info["sn"] = $activation;
        $info["userpassword"] = $plaintext_pass;
        $info["objectclass"] = "inetOrgPerson";
        $ldapAdd = ldap_add($ldapConn, "cn=$user_email,$ldapDomain", $info);
        //api key and sms account store in wp_users table
        $store_key = mysql_query("UPDATE wp_users SET user_activation_key='$activation', user_status='10' WHERE ID = '$user_id'");


}
else
{
echo "ldap is not connected";	
}
?>

