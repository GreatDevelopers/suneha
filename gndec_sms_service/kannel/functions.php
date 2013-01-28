<?php

function changePassword($newpwd) {
  
  if($details[1]=='') {
		$details[1] = $newpwd;
	}
	$num_users = mysql_num_rows(mysql_query("SELECT fullname FROM users WHERE mobile = '".$str_ph[1]."'"));
	if($num_users==0) {
		echo "You are not Registered with GNDEC SMS Service.";
	}
	else {
		mysql_query("UPDATE users SET password='".md5($details[1])."' WHERE mobile='".$str_ph[1]."'");
		$user = mysql_fetch_assoc(mysql_query("SELECT fullname FROM users WHERE mobile = '".$str_ph[1]."'"));
		if($details[1]==$newpwd) {
			echo "Hi ".$user['fullname'].", Your new password = ".$newpwd."";
			   
		}
		if($details[1]!=$newpwd) {
			echo "Hi ".$user['fullname'].", Your password has been changed successfully";
			
		}
	}
	mysql_query("DELETE FROM sent_sms WHERE momt='MO' AND msgdata LIKE '%".$msg."%'");
	mysql_close($conn);  
  
}

function smsToEmail($details) {
  
  if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $details[2])) {
			echo "Hey, You provided wrong email, Please provide valid email.";
			echo $details[2];
		}
		else {
			if(count($details)>5) {
				for($i=4;$i<=count($details);$i++) {
					$body_m[] = $details[$i];
				}
				$body_f = implode(",",$body_m);
				//$body_f = "sfdsfDS";
			}
			else {
				$body_f = $details[4];
			}
		if (!mail($details[2],$details[3],$body_f,'FROM:'.$details[1].'<suneha@gndec.ac.in>')) {
			echo "Error Sending Your Mail !";
		}
		else {
			echo "Your Mail Has Been Delivered";
		}
	}
	mysql_query("DELETE FROM sent_sms WHERE momt='MO' AND msgdata LIKE '%".$msg."%'");
	mysql_close($conn);
  
}

function smsToGD($details) {
  
  //if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $details[2])) {
  if(false) {
			echo "Hey, You provided wrong email, Please provide valid email.";
			echo $details[2];
		}
		else {
			if(count($details)>4) {
				for($i=3;$i<=count($details);$i++) {
					$body_m[] = $details[$i];
				}
				$body_f = implode(",",$body_m);
				//$body_f = "sfdsfDS";
			}
			else {
				$body_f = $details[3];
			}
		if (!mail('greatdevelopers@googlegroups.com',$details[2],$body_f,'FROM:'.$details[1].'<suneha@gndec.ac.in>')) {
			echo "Error Sending Your Mail !";
		}
		else {
			echo "Your Mail Has Been Delivered";
		}
	}
	mysql_query("DELETE FROM sent_sms WHERE momt='MO' AND msgdata LIKE '%".$msg."%'");
	mysql_close($conn);
  
}

function updateNews($details) {
  
  $allowed_users = array('+919463030713','919463030713','9463030713','9876700810','+919876700810','919876700810');
	if(in_array($str_ph[1],$allowed_users)) {
		if(count($details)>4) {
			for($i=3;$i<=count($details);$i++) {
					$body_m[] = $details[$i];
				}
				$body_f = implode(",",$body_m);
			}
			else {
				$body_f = $details[3];
			}
		$con_g = mysql_connect($db_hostname,$db_username,$db_password);
		mysql_select_db("drupal",$con_g);
		$fetch_prior = mysql_query("SELECT priority from h_t WHERE priority>='".$details[2]."'");
		while($row = mysql_fetch_assoc($fetch_prior)) {
			$pr = $row['priority']+1;
			mysql_query("UPDATE h_t SET priority='".$pr."',changed='Yes' WHERE priority='".$row['priority']."' AND changed='No'");
			}
		mysql_query("UPDATE h_t SET changed='No'");
		mysql_query("INSERT INTO h_t (link,priority,news) VALUES ('".$details[1]."','".$details[2]."','".mysql_real_escape_string($body_f)."')");
		if(!mysql_error()) {
			echo "News Updated successfully";
		}
		else {
			echo "Error: Unable to update news";
		}
	}
	else {
		echo "You are not Authorized to perform this operation. This incident will be reported to the admin";
		$msgdata = "An un-Authorized attempt to access gndec.ac.in denied for ".$str_ph[1]." at ".date('Y-m-d,G-i-s')."";
		mysql_query("INSERT INTO send_sms (sender,receiver,msgdata) VALUES ('GNDEC SMS Service','9463030713','".$msgdata."')");
		mysql_query("INSERT INTO send_sms (sender,receiver,msgdata) VALUES ('GNDEC SMS Service','9876700810','".$msgdata."')");
	}
	mysql_query("DELETE FROM sent_sms WHERE momt='MO' AND msgdata LIKE '%".$msg."%'");
	mysql_close($conn);
  
}


function listNews($details) {
  
  $allowed_users = array('+919463030713','919463030713','9463030713','9876700810','+919876700810','919876700810');
	if(in_array($str_ph[1],$allowed_users)) {
		$con_g = mysql_connect($db_hostname,$db_username,$db_password);
		mysql_select_db("drupal",$con_g);
		$result = mysql_query("SELECT id,news FROM h_t ORDER BY h_t.id DESC");
		if(!mysql_error()) {
			while($row = mysql_fetch_assoc($result)) {
				echo "(".$row['id'].".)".$row['news']."";
			}
		}
		else {
			echo "Error: Unable to fetch news.";
		}
	}
	else {
		echo "You are not Authorized to perform this operation. This incident will be reported to the admin";
		$msgdata = "An un-Authorized attempt to access gndec.ac.in denied for ".$str_ph[1]." at ".date('Y-m-d,G-i-s')."";
		mysql_query("INSERT INTO send_sms (sender,receiver,msgdata) VALUES ('GNDEC SMS Service','9463030713','".$msgdata."')");
		mysql_query("INSERT INTO send_sms (sender,receiver,msgdata) VALUES ('GNDEC SMS Service','9876700810','".$msgdata."')");
	}
	mysql_query("DELETE FROM sent_sms WHERE momt='MO' AND msgdata LIKE '%".$msg."%'");
	mysql_close($conn);
	
  
}


function removeNews($details) {
  
  $allowed_users = array('+919463030713','919463030713','9463030713','9876700810','+919876700810','919876700810');
	if(in_array($str_ph[1],$allowed_users)) {
	
		
		$con_g = mysql_connect($db_hostname,$db_username,$db_password);
		mysql_select_db("drupal",$con_g);
		
		$fetch_prior = mysql_query("SELECT priority from h_t WHERE priority>'".$details[1]."'");
		if(count($fetch_prior)>1) {
		while($row = mysql_fetch_assoc($fetch_prior)) {
			$pr = $row['priority']-1;
			mysql_query("UPDATE h_t SET priority='".$pr."',changed='Yes' WHERE priority='".$row['priority']."' AND changed='No'");
			}
		}
		
		mysql_query("DELETE FROM h_t WHERE priority='".$details[1]."' AND changed='No'");
		mysql_query("UPDATE h_t SET changed='No'");
		if(!mysql_error()) {
			echo "News removed from Website";
		}
		else {
			echo "Error: Unable to remove news.";
		}
	}
	else {
		echo "You are not Authorized to perform this operation. This incident will be reported to the admin";
		$msgdata = "An un-Authorized attempt to access gndec.ac.in denied for ".$str_ph[1]." at ".date('Y-m-d,G-i-s')."";
		mysql_query("INSERT INTO send_sms (sender,receiver,msgdata) VALUES ('GNDEC SMS Service','9463030713','".$msgdata."')");
		mysql_query("INSERT INTO send_sms (sender,receiver,msgdata) VALUES ('GNDEC SMS Service','9876700810','".$msgdata."')");
	}
	mysql_query("DELETE FROM sent_sms WHERE momt='MO' AND msgdata LIKE '%".$msg."%'");
	mysql_close($conn);
  
}


function checkGmail($details) {
  
  $username = $details[1];
	$password = $details[2];
	$label = $details[3];
	if($label!='') {
		$url = "https://gmail.google.com/gmail/feed/atom/".$label;
	}
	else {
		$url = "https://gmail.google.com/gmail/feed/atom";
	}
	$c = curl_init($url);
	$headers = array(
	"Host: gmail.google.com",
	"Authorization: Basic ".base64_encode($username.':'.$password),
	"User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.0.4) Gecko/20060508 Firefox/1.5.0.4",
	"Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5",
	"Accept-Language: en-gb,en;q=0.5",
	"Accept-Encoding: text",
	"Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7",
	"Date: ".date(DATE_RFC822).""
	);

	curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($c, CURLOPT_COOKIESESSION, true);
	curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 1);
	curl_setopt($c, CURLOPT_UNRESTRICTED_AUTH, 1);
	curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 1);

	$str = curl_exec($c);
	$fp = fopen("hss/hss.xml",'w');
	fwrite($fp,$str);
	fclose($fp);
	$doc = new DOMDocument();
	$doc->load("hss/hss.xml");

	$feeds = '';

	$count =1;
	$countloop = 1;
	foreach ($doc->getElementsByTagName('entry') as $node) {
    $feeds .=
        "\n (".$count.")Subject :".$node->getElementsByTagName('title')->item(0)->nodeValue
        ."\n Sender: ".$node->getElementsByTagName('email')->item(0)->nodeValue;
    if($countloop==5) {
			break;
		}
		$count +=1;
		$countloop +=1;
	}
	
	echo $feeds;
	curl_close($c);
	unlink("hss/hss.xml");
	mysql_query("DELETE FROM sent_sms WHERE momt='MO' AND msgdata LIKE '%chkmail%' OR msgdata LIKE '%Chkmail%' OR msgdata LIKE '%".$msg."%'");
	mysql_close($conn);

}

function youtubeDl($details) {
  
  $url = "http://www.youtube.com/watch?v=".$details[1];
	$url_f = escapeshellcmd("youtube-dl $url");
	$name = explode("=",$url);
	$name_f = explode("&",$name[1]);
	
	exec($url_f);
	exec("mv ".$name_f[0].".flv youtubedl/");
	
	//echo "Get your file from http://202.164.53.116/~harbhag/kannel/youtubedl/".$name_f[0].".flv";
	echo $url;
	mysql_query("DELETE FROM sent_sms WHERE momt='MO' AND msgdata LIKE '%".$msg."%'");
	mysql_close($conn);
  
}


function mailReceivedSMS($phone,$msg) {

	if($phone=="DM-PSBANK" or $phone=="BT-PSBANK" or $phone=="LM-PSBANK" or $phone=="%BANK%") {
		$to = "harbhag.sohal@gmail.com,tcc@gndec.ac.in";
	}
	else {
		$to = "harbhag.sohal@gmail.com,greatdevelopers@googlegroups.com";
	}

	$subject = "New SMS Received by Suneha From: ".$phone;
	$body = $msg;
	$headers = "From: Suneha (GNDEC) <suneha@gndec.ac.in>";

	mail($to,$subject,$body,$headers);

}

?>
