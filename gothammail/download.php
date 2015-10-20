<? require_once('includes/database.php'); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>

<h2>Downloading Mail</h2>

<?php

$bodyTypeText[0] = "text";	
$bodyTypeText[1] = "multipart";	
$bodyTypeText[2] = "message";	
$bodyTypeText[3] = "application";	
$bodyTypeText[4] = "audio";	
$bodyTypeText[5] = "image";	
$bodyTypeText[6] = "video";	
$bodyTypeText[7] = "other";

$encodingTypeText[0] = "7BIT";	
$encodingTypeText[1] = "8BIT";	
$encodingTypeText[2] = "BINARY";	
$encodingTypeText[3] = "BASE64";	
$encodingTypeText[4] = "QUOTED-PRINTABLE";	
$encodingTypeText[5] = "OTHER";

function insertBodyPart($imap, $messageNo, $thisStructure, $email_id, $part_number = "0"){

	$thisStructureArray = get_object_vars($thisStructure);
	extract($thisStructureArray);
	
	global $bodyTypeText;
	$typeText =  $bodyTypeText[$type];
	global $encodingTypeText;
	$encodingText = $encodingTypeText[$encoding];
	
	if (is_array($thisStructure->dparameters)) {
		foreach($thisStructure->dparameters as $obj) {
			$dparametersText .= addslashes($obj->attribute.": <i>".$obj->value."</i>; ");
			if ((strtoupper($obj->attribute)=='NAME') ||(strtoupper($obj->attribute)=='FILENAME')) $filename = addslashes($obj->value);
		}	
	}

	if (is_array($thisStructure->parameters)) {
		foreach($thisStructure->parameters as $obj) {
			$parametersText .= addslashes($obj->attribute.": <i>".$obj->value."</i>; ");
			if ((strtoupper($obj->attribute)=='NAME') ||(strtoupper($obj->attribute)=='FILENAME')) $filename = addslashes($obj->value);
		}	
	}

	if ($lines == "") $lines = 'NULL';
	if ($bytes == "") $bytes = 'NULL';

	// so you don't get headers on single part messages
	if ($part_number == 0 && $type != 1) $fetchbody_part_number = 1; 
	else $fetchbody_part_number = $part_number;

	if ($type != 1) $body = addslashes(imap_fetchbody ($imap, $messageNo, $fetchbody_part_number, FT_PEEK ));

	$sql = "INSERT INTO email_bodies (email_id, part_number, type, encoding, subtype, description, idstring, numlines, bytes, disposition, dparameters, parameters, filename, body)
								VALUES ($email_id, '$part_number', '$typeText', '$encodingText', '$subtype', '$description', '$id', $lines, $bytes, '$disposition', '$dparametersText', '$parametersText', '$filename', '$body')";
	
	mysql_query($sql) or die(mysql_error()."<p><i>$sql</i></p>");

	// get parts of this part
	if (is_array($thisStructure->parts)) {
		
		$i = 1;
		foreach($thisStructure->parts as $subStructure) {
			
			if ($part_number == 0) $new_part_number = $i;
			else $new_part_number = $part_number.".".$i;
			
			insertBodyPart($imap, $messageNo, $subStructure, $email_id, $new_part_number);
		
			$i++;
		}
	}

	
		


/*


	echo "<blockquote>";
	
	echo "$partNo $thisTypeText/$thisSubType ($thisEncodingText)";
	
	
	echo "<blockquote>";

		echo "<b>type:</b> ".$thisStructure->type;
		echo "<br><b>encoding:</b> ".$thisStructure->encoding;
		echo "<br><b>subtype:</b> ".$thisStructure->subtype;
		echo "<br><b>description:</b> ".$thisStructure->description;
		echo "<br><b>id:</b> ".$thisStructure->id;
		echo "<br><b>lines:</b> ".$thisStructure->lines;
		echo "<br><b>bytes:</b> ".$thisStructure->bytes;
		echo "<br><b>disposition:</b> ".$thisStructure->disposition;

		echo "<br><b>dparameters:</b> ";
		if (is_array($thisStructure->dparameters)) {
			foreach($thisStructure->dparameters as $obj) {
				echo $obj->attribute.": <i>".$obj->value."</i>; ";
			}	
		}
	
		echo "<br><b>parameters:</b> ";
		if (is_array($thisStructure->parameters)) {
			foreach($thisStructure->parameters as $obj) {
				echo $obj->attribute.": <i>".$obj->value."</i>; ";
			}	
		}
		
	
	
	echo "</blockquote>";
	
	
	if (is_array($thisStructure->parts)) {
		
		$i = 1;
		
		foreach($thisStructure->parts as $subStructure) {
			
			if ($partNo == 0) $newPartNo = $i;
			else $newPartNo = $partNo.".".$i;
			
			insertBodyPart($imap, $messageNo, $subStructure, $newPartNo);
		
			$i++;
		}
	} else {
	
		if ($partNo == 0) $partNo = 1;
		
		$thisBody = imap_fetchbody ($imap, $messageNo, $partNo, FT_PEEK );
		
		echo "<blockquote>".substr(str_replace("<", "&lt;", $thisBody), 0, 400)."</blockquote>";
	
	}
	
	echo "</blockquote>";
*/	
}

die("protected");

//mysql_query("TRUNCATE TABLE emails");
//mysql_query("TRUNCATE TABLE email_bodies");

$mailboxRoot = '{64.15.252.17:143}';
$mailUsername = 'mkatzman%artzar.com';
$mailPassword = 'zeusAthena';

// get mailboxes
$imap = imap_open($mailboxRoot, $mailUsername, $mailPassword, OP_HALFOPEN)
     or die("Can't connect: " . imap_last_error());
$mailboxes = imap_getmailboxes($imap, "{64.15.252.17:143}", "*");
imap_close($imap);

$total_added = 0;
$total_time = 0;

//go through each mailbox
if (is_array($mailboxes)) {
   foreach ($mailboxes as $key => $val) {
	$thisBox =  imap_utf7_decode($val->name);
	$boxName = substr($thisBox, strlen($mailboxRoot));
	
	
	   if($boxName != "Junk Folder" && $boxName != "Junk E-Mail" && $boxName != "Deleted Items") {

	   		$startTime = time();
	   
			$imap = imap_open($mailboxRoot.$boxName, $mailUsername, $mailPassword)
				 or die("Can't open $boxName box! " . imap_last_error());
			
			$added = 0;	
			
			$num_msg = imap_num_msg($imap);
			
			//if ($num_msg > 40) $num_msg = 40;
			
			// go through each message
			for ($i = 1; $i <= $num_msg && $added < 100; $i++) {


				$thisUID = imap_uid ($imap, $i);
				$result = mysql_query("SELECT * FROM emails WHERE uid = '$thisUID'");
				if (mysql_num_rows($result) == 0) {

					$header = imap_headerinfo($imap, $i);
					$thisTo = addslashes($header->toaddress);
					$thisCC = addslashes($header->ccaddress);
					$thisFrom = addslashes($header->fromaddress);
					$thisSubject = addslashes($header->subject);
					$thisDate = strtotime($header->date);
					$thisHeader = addslashes(imap_fetchheader($imap, $i));
					mysql_query("INSERT INTO emails (uid, mailbox, fromaddress, toaddress, ccaddress, subject, date, headers) 
											VALUES ('$thisUID', '$boxName', '$thisFrom', '$thisTo', '$thisCC', '$thisSubject', FROM_UNIXTIME($thisDate), '$thisHeader')");
					if(mysql_error()) die (mysql_error());
				
					$email_id = mysql_insert_id();
				
					$added++;
					$total_added++;
				
					//$thisBody = imap_body($imap, $i, FT_PEEK);
					$thisStructure = imap_fetchstructure($imap, $i);
					$thisType = $thisStructure->type;
					$thisSubType = $thisStructure->subtype;
	
					insertBodyPart($imap, $i, $thisStructure, $email_id);
				
				}
			}

			$netTime = time() - $startTime;
			
			$totalTime += $netTime;
			
			echo "<p>Folder <b>$boxName</b> parsed with <b>$added</b> (of $num_msg total) messages in <b>$netTime</b> seconds.</p>";
			
			imap_close($imap);
	   	
	   }   
   }
   
} else {
   echo "imap_getmailboxes failed: " . imap_last_error() . "\n";
}

echo "<p>Pulled in <b>$total_added</b> total messages in <b>$totalTime</b> seconds.</p>";


mysql_close($db);


?>


</body>
</html>
