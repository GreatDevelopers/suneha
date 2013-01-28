<?php
	require_once('constants.inc');
	require_once(FILE_FUNCTIONS);
	require_once(FILE_CLASS_OPTIONS);
	require_once('classes.php');
	require_once(FILE_LIB_MAIL);

$db_link = openDatabase($db_hostname, $db_username, $db_password, $db_name);
$status = new SmsStatus();
?>

<html>
	<head>
		<title>Reset Password</title>
		<link rel='stylesheet' type='text/css' href='styles.css'>
		<script type='text/javascript' src='harbhag_predictive.js'></script>
		</head>
	<body>
		<div id='title_img'>
		<img src='images/title.png' height='90' width='570' />
		<?php if(isset($_POST['reset_now'])) { 
			$result = mysql_query("SELECT mobile FROM users WHERE username='".$_POST['reset_username']."'");
			$nums = mysql_num_rows($result);
			$mobile = mysql_fetch_assoc($result);
			if($nums==0) {
				echo "<p>Incorrect Username</p>";
				echo "<a href='reset_password.php'><INPUT TYPE='button' CLASS='formButton' VALUE='Try Again'></a>";
				echo "<a href='index.php'><INPUT TYPE='button' CLASS='formButton' VALUE='Home'></a>";
			}
			elseif($nums>0 && $mobile['mobile']=='') {
				echo "<p>No Mobile no. Associated with this Username. Please contact Admin at harbhag.sohal@gmail.com</p>";
				echo "<a href='index.php'><INPUT TYPE='button' CLASS='formButton' VALUE='Home'></a>";
			}
			
			else
			{
				$msgd = "Hi ".$_POST['reset_username'].",\nYour New Password is : ".$system_answer."\nThanks for using GNDEC SMS Service";
				mysql_query("INSERT INTO send_sms (sender,receiver,msgdata) VALUES ('GNDEC SMS Service','".$mobile['mobile']."','".$msgd."')") or die(mysql_error());
				mysql_query("UPDATE users SET password='".md5($system_answer)."' WHERE username='".$_POST['reset_username']."'") or die(mysql_error());
				echo "<p>Your New password has been Sent Successfully to:".$mobile['mobile']."</p>";
				echo "<a href='index.php'><INPUT TYPE='button' CLASS='formButton' VALUE='Home'></a>";
			}
			?>
		<?php }
		else { ?>
		
		<p>Enter your username and your new password Will be sent to you via SMS on the mobile no. that you used for registeration.
		<?php if($status->bearerbox==false or $status->sqlbox==false) {
		echo "<br /><span class='warning_r'>Warning: SMS Service is Currenty down, You will receive your new password after the service will be back to normal again.</span>"; 
		echo "<br><a href='index.php'><INPUT TYPE='button' CLASS='formButton' VALUE='Go Back'></a>";
		}
		else {?> </p>To get new password, send SMS to 9417212503 in format: <b><h1>cpw,NewPassword</h1></b>
		<?php
    echo "<br><a href='index.php'><INPUT TYPE='button' CLASS='formButton' VALUE='Go Back'></a>";
    } } ?>
		
		</div>
	</body>
</html>
