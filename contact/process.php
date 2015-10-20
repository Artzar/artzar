<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>

<h2>Contact Us</h2>

<?php


if (empty($_POST['name'])) $blankField = "Name";
else if (empty($_POST['email'])) $blankField = "Email Address";
else if (empty($_POST['comment'])) $blankField = "Comment or Request";

if (!empty($blankField)) {

	echo "<p><font color='#990000'><strong>Error:</strong> You have not entered your <strong>$blankField</strong>.</p>";
	echo "<p>Please <a href='javascript:back();'>Go Back</a> and correct it.</p>";
	
} else {


	$message = "The contact form has been submitted! The data is as follows:\n\n";
	foreach ($_POST as $key => $value) if ($key != "submit" && $value != "") $message .= $key.": ".addslashes($value)."\n\n";
	$headers = "From: Artzar Contact Form <noemail@artzar.com>\n";
	$success = mail('mkatzman@artzar.com, mccaffry@artzar.com', "Contact Form Submission", $message, $headers);

	if($success) echo "<p>Your requst has been submitted. We will get back to you at the earliest opportunity.</p><p>Click the logo below to return to the site's main contents.</p>";
	else echo "<p>Could not submit request. Please try again at a later time.";
	
}

?>


</body>
</html>
