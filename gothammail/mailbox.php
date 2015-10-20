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
	$result = mysql_query("SELECT * FROM emails WHERE mailbox = '$mailbox' ORDER BY date DESC ") or die(mysql_error());
?>

<a href="index.php">Return to Index</a>

<h2><?=$mailbox?></h2>

<table cellpadding="3" cellspacing="0"><tr><th>Sender</th><th>Subject</th><th>A</th><th>Date</th></tr>

<?

	while($resultRow = mysql_fetch_array($result)) {
		$row_color = ($row_color != "#ffffff") ? "#ffffff" : "#dddddd";
		extract($resultRow);
		$subject = substr($subject, 0, 60);
		$fromaddress = substr(str_replace("<", "&lt;", $fromaddress), 0, 40);
		$date = str_replace(" ", "&nbsp;", $date);
		if($subject == "") $subject = "<i>No Subject</i>";
		$attachments = (hasAttachments($email_id)) ? "A" : "&nbsp;";
		echo "<tr><td bgcolor='$row_color'> $fromaddress</td><td bgcolor='$row_color'><a href='email.php?email_id=$email_id'>$subject</a></td><td bgcolor='$row_color'>$attachments</td><td bgcolor='$row_color'>$date</td>";
	
	}

?>

</table>

</body>
</html>
