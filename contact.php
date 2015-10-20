<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Mark Katzman - Writer</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="stylesheet.css" rel="stylesheet" type="text/css">
		<style> 
			#contact { border: 1px solid white; }
			.antispam-field { margin-top: 10px;}
		</style>
</head>

<body>
<div id="container">
	<div id="leftcol">
		<? require('leftnav.inc.php'); ?>
	</div>
	
  <div id="rightcol">&nbsp; 
    <?  require ("includes/antispam-form.inc.php"); ?>
    <?  require ("includes/validate-fields.inc.php"); ?>
			
			<?
			
				$form_fields[] = array('name' => 'name', 'type' => 'text', 'size' => '40', 'label' => 'Your Name', 'required' => 'true');
				$form_fields[] = array('name' => 'email', 'type' => 'text', 'size' => '40', 'label' => 'Your Email', 'required' => 'true');
				$form_fields[] = array('name' => 'subject', 'type' => 'text', 'size' => '40', 'label' => 'Subject', 'required' => 'true');
				$form_fields[] = array('name' => 'body', 'type' => 'textarea', 'cols' => '40', 'rows' => '10', 'label' => 'Message', 'required' => 'true');
				$form_fields[] = array('name' => 'submit', 'type' => 'submit', 'value' => 'Submit');
		
				$shuffle = array(array('name', 'email'));
		
				$form_values = antispam_form_get_values($form_fields);
				
				if($form_values) {
				
					if(antispam_form_get_errors()) $form_errors[] = antispam_form_get_errors();
				
				
					if(!valid_name_field($form_values['name'])) $form_errors[] = 'You must enter your name!';
					if(!valid_comment_field($form_values['subject'])) $form_errors[] = 'You must enter a subject!';
					if(!valid_email_field($form_values['email'])) $form_errors[] = 'You must enter a valid email address!';
					if(!valid_comment_field($form_values['body'])) $form_errors[] = 'You must enter a message!';
				
					if($form_errors) {
					
						foreach($form_errors as $this_error) print "<p style='color: red'><strong>$this_error</strong></p>";
		
						print antispam_form_write_form ($form_fields, $form_values, $shuffle);
											
					} else {
				
						$notify = 'Mark Katzman <mark@markkatzman.com>';
				
						$script_name = "http://{$_SERVER['HTTP_HOST']}";
						$script_name .= $_SERVER['REQUEST_URI'];

						$message = $form_values['body'];
						$message .= "\n\nThe form was submitted from the following page:\n$script_name";
		
						$bcc = "Bcc: mccaffry@artzar.com\r\n";
						
						$from = $form_values['name']." <".$form_values['email'].">";
						
						$subject = "[contact form] ".$form_values['subject'];
		
						$wasSent = mail($notify, $subject, $message, "From: $from\r\n$bccX-Mailer: PHP/" . phpversion());
				 
	  					if($wasSent) echo "<div><strong>Your message has been sent!</strong></div>";
	 					else echo "<div style='color: red'><strong>Could not send message. Please try again at a later time!</strong></div>";
					} 
				
				} else { 
				
					print antispam_form_write_form ($form_fields, '', $shuffle);
		
				}
		
			?>
	</div>
</div>
</body>
</html>
