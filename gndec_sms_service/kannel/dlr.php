<?php
require_once('../config.php');
$status = $_GET['status'];
$fid = $_GET['fid'];
$msgid = $_GET['msgid'];

$conn = mysql_connect($db_hostname,$db_username,$db_password);
mysql_select_db("adbook",$conn);
mysql_query("INSERT INTO tdlr (status,fid,msgid) VALUES ('".$status."','".$fid."','".$msgid."')");

mysql_close($conn);

?>
