<? require_once('includes/database.php'); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>

<? 
	$result = mysql_query("SELECT mailbox, count(*) as mailboxcount FROM emails GROUP BY mailbox ORDER BY mailbox ") or die(mysql_error());
	
	
	
	while($resultRow = mysql_fetch_array($result)) {
		extract($resultRow);
		echo "<p><a href='mailbox.php?mailbox=$mailbox'>$mailbox</a> ($mailboxcount)</p>";
	
	}

?>

</body>
</html>
