<? require_once('includes/database.php'); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>

<h2>Validating Mail</h2>

<?php

function countParts($thisStructure){

	$i = 1;
		
	// get parts of this part
	if (is_array($thisStructure->parts)) 
		foreach($thisStructure->parts as $subStructure) 	
			$i+= countParts($subStructure);

	return $i;
}
	

$mailboxRoot = '{64.15.252.17:143}';
$mailUsername = 'winamp3%artzar.com';
$mailPassword = 'skinnerbox';

// get mailboxes
$imap = imap_open($mailboxRoot, $mailUsername, $mailPassword, OP_HALFOPEN)
     or die("Can't connect: " . imap_last_error());
$mailboxes = imap_getmailboxes($imap, "{64.15.252.17:143}", "*");
imap_close($imap);

$total_checked = 0;
$total_time = 0;

//go through each mailbox
if (is_array($mailboxes)) {
   foreach ($mailboxes as $key => $val) {
	$thisBox =  imap_utf7_decode($val->name);
	$boxName = substr($thisBox, strlen($mailboxRoot));
	
	
	  // if($boxName != "Junk Folder" && $boxName != "Junk E-Mail" && $boxName != "Deleted Items") {
	   if($boxName == "Inbox" || $boxName == "comments") {

	   		$startTime = time();
	   
			$imap = imap_open($mailboxRoot.$boxName, $mailUsername, $mailPassword)
				 or die("Can't open $boxName box! " . imap_last_error());
			
			$num_msg = imap_num_msg($imap);
			
			$checked = 0;
			
			// go through each message
			for ($i = 1; $i <= $num_msg; $i++) {


				$thisUID = imap_uid ($imap, $i);
				$result = mysql_query("SELECT * FROM winamp_emails WHERE uid = '$thisUID'");
				if (mysql_num_rows($result) == 0) echo "<p>Email $thisUID not found!</p>";

				
				//$thisBody = imap_body($imap, $i, FT_PEEK);
				$thisStructure = imap_fetchstructure($imap, $i);
				$num_parts = countParts($thisStructure);
				
				$result = mysql_query("SELECT * FROM winamp_emails WHERE uid = '$thisUID'");
				if (mysql_num_rows($result) > $num_parts) echo "<p>Email $email_id/$thisUID is missing parts!</p>";
				
				$checked++;
				$total_checked++;
				
			}

			$netTime = time() - $startTime;
			
			$totalTime += $netTime;
			
			echo "<p>Folder <b>$boxName</b> checked with <b>$checked</b> (of $num_msg total) messages in <b>$netTime</b> seconds.</p>";
			
			imap_close($imap);
	   	
	   }   
   }
   
} else {
   echo "imap_getmailboxes failed: " . imap_last_error() . "\n";
}

echo "<p>Checked <b>$total_checked</b> total messages in <b>$totalTime</b> seconds.</p>";


mysql_close($db);


?>


</body>
</html>
