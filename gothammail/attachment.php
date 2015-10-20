<?
	require_once('includes/database.php');
	
	extract($_REQUEST);

	$result = mysql_query("SELECT * FROM email_bodies WHERE email_id = $email_id AND part_number = '$part_number'") or die (mysql_error());
	extract(mysql_fetch_array($result));

	// We'll be outputting a PDF
	header("Content-type: $type/$subtype");


	// It will be called downloaded.pdf
	if($type != 'image') header("Content-Disposition: attachment; filename=$filename");
	
	
	if($encoding = 'BASE64') echo imap_base64($body);
	else echo $body;

?>