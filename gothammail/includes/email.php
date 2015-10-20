<?

	function hasAttachments($email_id) {
		
		$result = mysql_query("SELECT * FROM email_bodies WHERE email_id = $email_id");
		while($resultRow = mysql_fetch_array($result)) {
			extract($resultRow);
			if ($filename != "") return true;
		
		}
	
	
	}



?>