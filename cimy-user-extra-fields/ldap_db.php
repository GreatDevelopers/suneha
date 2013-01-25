<?php
$ldapHost = 'localhost';
$ldapLogin = 'cn=admin,dc=navdeep,dc=bagga';
$ldapDomain = 'ou=people,dc=navdeep,dc=bagga';
$ldapPass = 'villa';
$ldapConn = ldap_connect($ldapHost)
	or die("Could not connect to LDAP server.");

ldap_set_option($ldapConn, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ldapConn, LDAP_OPT_REFERRALS, 0);
?>
