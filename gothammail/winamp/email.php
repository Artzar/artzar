<? require_once('includes/database.php'); ?>
<? require_once('includes/emails.php'); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>

<? 
	extract($_REQUEST);
	$result = mysql_query("SELECT * FROM winamp_emails WHERE email_id = $email_id") or die(mysql_error());
	$resultRow = mysql_fetch_array($result);
	extract($resultRow);
?>


<p><a href="mailbox.php?mailbox=<?=$mailbox?>">Return to <?=$mailbox?></a></p>

<table>
	<tr><td><b>From:</b></td><td><?=$fromaddress?></td></tr>
	<tr><td><b>To:</b></td><td><?=$toaddress?></td></tr>
	<tr><td><b>CC:</b></td><td><?=$ccaddress?></td></tr>
	<tr><td><b>Date:</b></td><td><?=$date?></td></tr>
	<tr><td><b>Subject:</b></td><td><?=$subject?></td></tr>
	<?
		if (hasAttachments($email_id)) {
			echo "<tr><td valign='top'><b>Attachments:</b></td><td>";
			
			$result = mysql_query("SELECT * FROM winamp_email_bodies WHERE email_id = $email_id AND type != 'text' AND type != 'multipart' AND filename != ''") or die(mysql_error());
			while($resultRow = mysql_fetch_array($result)) {
				extract($resultRow);
				echo "<a href='attachment.php?email_id=$email_id&part_number=$part_number'>$filename</a><br>";
			}
			
			echo "</td></tr>";
		}
	?>
</table>
	
	<?
		$result = mysql_query("SELECT * FROM winamp_email_bodies WHERE email_id = $email_id AND type = 'text'") or die(mysql_error());
		while($resultRow = mysql_fetch_array($result)) {
			extract($resultRow);
			if ($encoding = "QUOTED-PRINTABLE") $body = quoted_printable_decode($body);
			$body = str_replace("<hr>", "", $body);
			$body = str_replace("<BODY", "<notbody>", $body);
			$body = str_replace("</BODY", "</notbody>", $body);
			$body = str_replace("\n", "<br>", $body);
			echo "<hr><div style='width: 600px;'>$body</div><hr>";
		}
	
	?>
	
</body>
</html>
