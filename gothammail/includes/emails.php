<?

	function hasAttachments($email_id) {
		
		$result = mysql_query("SELECT * FROM email_bodies WHERE email_id = $email_id AND type != 'text' AND filename != ''");
		if(mysql_num_rows($result) > 0) return true;
	
	
	}



?>