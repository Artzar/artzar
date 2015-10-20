<?
	/*
		ANTI-SPAM FORM GENERATOR 1.6

		This script creates forms forms with the following anti-spam features:
			- Encrypted field names to prevent autofilling of fields by spambots based on their name
			- Random labels hidden by css to bait spambot to autofill the wrong info
			- Honeypot fields hidden by css which detect a spambot if it is autofilling fields
			- A form key which tracks what time the form was generated to catch a spambot if it:
					- submits the form without a key in the proper format
					- submits a form faster than a human would be able
					- catches a copy of the form and tries resubmitting it again later
			- Groups of fields can be shuffled to prevent autofilling based on field order

		CHANGES IN 1.5.1
			- Fixed recusive error in antispam_form_are_errors when checking for errors

		CHANGES IN 1.5
			- allow "multi" field types with seperator
			- add the note field
			- something minor but I forget what
			- stripslashes in antispam_form_write_field_element to prevent slashes if form is resubmitted after an error

		CHANGES IN 1.6
			- moving from tables to css
			- there was an /includes/ in the form action
			- default min_time down to 2 secs

		EXAMPLE USAGE
		
		
			<? require ($_SERVER['DOCUMENT_ROOT'] . "/includes/antispam-form.inc.php"); ?>
			
			<?
				$form_fields[] = array('name' => 'name', 'type' => 'text', 'size' => '40', 'label' => 'Your Name', 'required' => 'true');
				$form_fields[] = array('name' => 'city', 'type' => 'text', 'size' => '40', 'label' => 'City', 'required' => 'true');
				$form_fields[] = array('name' => 'state', 'type' => 'longstate', 'label' => 'State', 'required' => 'true');
				$form_fields[] = array('name' => 'zipcode', 'type' => 'text', 'size' => '10', 'label' => 'Zip Code', 'required' => 'true');
				$form_fields[] = array('name' => 'email', 'type' => 'text', 'size' => '40', 'label' => 'Email', 'required' => 'true');
				$form_fields[] = array('name' => 'phone', 'type' => 'text', 'size' => '40', 'label' => 'Phone');
				$form_fields[] = array('name' => 'contact', 'type' => 'radio', 'buttons' => array(array('value' => 'yes', 'label' => 'Yes'), array('value' => 'no', 'label' => 'No')), 'label' => 'Can We <br>Contact You?');
				$form_fields[] = array('name' => 'story', 'type' => 'textarea', 'cols' => '40', 'rows' => '10', 'label' => 'Your Story', 'required' => 'true');
				$form_fields[] = array('name' => 'howhear', 'type' => 'select', 'options' => array(array('value' => 'internet', 'label' => 'The Internet'), array('value' => 'radio', 'label' => 'The Radio'), array('value' => 'news', 'label' => 'A Newspaper')), label => 'How did you hear about us?');
				$form_fields[] = array('name' => 'likeus', 'type' => 'checkbox', 'label' => 'Do you like us?');
				$form_fields[] = array('name' => 'submit', 'type' => 'submit', 'value' => 'Submit');
		
				$shuffle = array(array('city', 'state', 'zipcode'), array('email', 'phone'));
		
				$form_values = antispam_form_get_values($form_fields);
				
				if($form_values) {
				
					if(antispam_form_get_errors()) $form_errors[] = antispam_form_get_errors();
				
					// YOUR ERROR CHECKING HERE
				
					if($form_errors) {
					
						foreach($form_errors as $this_error) print "<p style='color: red'><strong>$this_error</strong></p>";
		
						print antispam_form_write_form ($form_fields, $form_values, $shuffle);
											
					} else {
				
						// YOUR CODE TO RUN ON SUCESS HERE
					} 
				
				} else { 
				
					print antispam_form_write_form ($form_fields, '', $shuffle);
		
				}
		
			?>
	*/


// depreciated function to get errors, replaced by antispam_form_errors which returns an array
function antispam_form_get_errors ($min_time = "2", $max_time = "300") {
	
	if (antispam_form_are_errors($min_time, $max_time)) {
	
		$errors = antispam_form_errors ($min_time, $max_time);
		
		return $errors[0];
		
	}
	return false; 
}


function antispam_form_are_errors ($min_time = "2", $max_time = "300") {


	$errors = antispam_form_errors ($min_time, $max_time);
	
	if ($errors) return true;

	else return false;

}



function antispam_form_errors ($min_time = "2", $max_time = "300") {

	$key = $_POST['form_id'];
	
	$form_time = antispam_form_retrieve_time($key);
	
	if(!is_numeric($form_time)) $out_errors[] =  "Form key ($key) is not in a valid format($form_time).";
	
	$time_difference = time() - $form_time;
	
	if ($time_difference < $min_time || $time_difference > $max_time) $out_errors[] = "Form was not submitted within an appropriate time frame. Please repost in a couple of seconds.";

	if($_POST['name'] || $_POST['email']) $out_errors[] = "Form fields not filled out properly. Please ensure that CSS is enabled in your browser.";

	if(is_array($out_errors)) return $out_errors;
	
	return false;

} 

function antispam_form_get_values($fields) {

	$key = $_POST['form_id'];
	
	if(empty($key)) return false;

	foreach($fields as $this_field) {
	
		// if this is a multi field call recursively
		if ($this_field['type'] == 'multi' && is_array($this_field['fields'])) {
			
			$returned_values = antispam_form_get_values($this_field['fields']);
			
			if(is_array($out_values)) $out_values = array_merge($out_values, $returned_values);
			else $out_values = $returned_values;
			
		} else {
			
			$encoded_field_name = antispam_form_encode_name($this_field['name'], $key);		
			
			$out_values[$this_field['name']] = $_POST[$encoded_field_name];
		}
	}
	
	return $out_values;

}



function antispam_form_write_form ($fields, $values = "", $shuffle = "", $action = "SELF") {

	$out_content .= "<div style='display: none'><strong>Warning: This form may not work properly with your style sheet settings!</strong></div>";

	if ($action == "SELF") $action = $_SERVER['REQUEST_URI'];

	$out_content .= "\n<form action='$action' method='POST'>\n\n";

	$key = antispam_form_generate_key();
	
	$out_content .= "\t<input type='hidden' name='form_id' value='$key'>\n\n";
	
	$out_content .= "\t<div style='display: none;'>Not Your Name: <input type='text' name='name' size='40'/></div>\n\n";
	
	$out_content .= "\t<div style='display: none;'>Not Your Email: <input type='text' name='email' size='40'/></div>\n\n";



	antispam_form_shuffle_fields($fields, $shuffle);

	foreach ($fields as $this_field) {
	
		$out_content .= antispam_form_write_field($this_field, $values, $key);
	
	}

	$out_content .= "\n\n</form>\n\n";

	return $out_content;

}

function antispam_form_generate_key() {

	return rtrim(base64_encode(time()), '=');

}

function antispam_form_retrieve_time($key) {

	return base64_decode($key."==");

}

function antispam_form_encode_name($name, $key) {

	return md5($name.$key.'randomString');

}


function antispam_form_shuffle_fields(&$in_fields, $shuffle_names = "") {

		// only do function if 
		if (!is_array($shuffle_names)) return;

		// check if there are multiple groups
		if (is_array($shuffle_names[0])) {
			
			foreach($shuffle_names as $shuffle_group) antispam_form_shuffle_field_group($in_fields, $shuffle_group);
			
		// else if only one group
		} else {
		
			antispam_form_shuffle_field_group($in_fields, $shuffle_names);
		
		}
}

function antispam_form_shuffle_field_group(&$in_fields, $shuffle_names = "") {

		// only do function if 
		if (!is_array($shuffle_names)) return;

		// convert suffle names to ids
		for ($i = 0; $i < sizeof($in_fields); $i++) {
			$this_field = $in_fields[$i];
			if(in_array($this_field['name'], $shuffle_names)) 
				$shuffle_keys[] = $i;
		}
		
		if (is_array($shuffle_keys)) {
		
			// pull feilds to be shuffled into another array
			foreach ($shuffle_keys as $this_key) $shuffle_fields[] = $in_fields[$this_key];
			
			// suffle the fields in the tempoary array
			shuffle($shuffle_fields);
	
			// insert new values back into original
			for ($i = 0; $i < sizeof($shuffle_keys); $i++) $in_fields[$shuffle_keys[$i]] = $shuffle_fields[$i];
			
		}
}

function antispam_form_write_field($in_field, $values = "", $key) {

	if ($in_field['required'] == 'true' || $in_field['required'] == true) $in_field['label'] = "<strong>".$in_field['label']."</strong>";

	$random_label = antispam_form_random_label();

	$out_content .= "\t<div class='antispam-field'>\n\t\t<div class='antispam-field-name'>{$in_field['label']}<div style='display: none'>$random_label</div></div>\n\t\t<div class='antispam-field-item'>";

	$out_content .= antispam_form_write_field_element($in_field, $values, $key);

	$out_content .= "</div>\n\t</div>\n\n";

	return $out_content;

}


function antispam_form_write_field_element($in_field, $values = "", $key) {

	// check to see if the user has already inputted values into this field
	$new_value = stripslashes($values[$in_field['name']]);
	if(!empty($new_value) || $in_field['type'] == 'checkbox') $in_field['value'] = $new_value;
	
	// encrypt the field name
	$in_field['name'] = antispam_form_encode_name($in_field['name'], $key);


	// write the fields

	if ($in_field['type'] == 'text') $out_content .= "<input name='{$in_field['name']}' type='text' value='{$in_field['value']}' size='{$in_field['size']}' />";

	if ($in_field['type'] == 'textarea') $out_content .= "<textarea name='{$in_field['name']}' rows='{$in_field['rows']}' cols='{$in_field['cols']}'>{$in_field['value']}</textarea>";

	if ($in_field['type'] == 'submit') $out_content .= "<input type='submit' name='{$in_field['name']}' value='{$in_field['value']}' />";

	if ($in_field['type'] == 'radio') {
		
		foreach ($in_field['buttons'] as $this_button) {
		
			$checked = ($this_button['value'] == $in_field['value'] ? 'checked' : '');
			
			$out_content .= "{$this_button['label']}  <input name='{$in_field['name']}' type='radio' value='{$this_button['value']}' $checked /> ";

		}	
	}

	if ($in_field['type'] == 'checkbox') {
	
		$checked = ($in_field['value'] == 'on' || $in_field['value'] == 'checked') ? 'checked' : '';
		
		$out_content .= "<input type='checkbox' name='{$in_field['name']}' $checked />";
	
	}

	if ($in_field['type'] == 'select') {
	
		$out_content .= "<select name='{$in_field['name']}'>\n";
	
		foreach($in_field['options'] as $this_option) {
	
			$selected = ($in_field['value'] == $this_option['value'] ? 'selected' : '');
			
			$out_content .= "\t\t\t<option value='{$this_option['value']}' $selected>{$this_option['label']}</option>";
			
		}
		
		$out_content .= "\t\t</select>\n\n";
	}


	if ($in_field['type'] == 'state' || $in_field['type'] == 'longstate') {
	
		$out_content .= "<select name='{$in_field['name']}'>\n";
	
		$states = get_state_list();
		
		foreach ($states as $this_state) {
			
			if($in_field['type'] == 'state') $state_name = $this_state['abbr'];
			else $state_name = $this_state['full'];
			
			if ($in_field['value'] == $this_state['value']) $selected = "selected";
			else $selected = "";
			
			$out_content .= "\t\t\t  <option value='{$this_state['value']}' $selected	>$state_name</option>\n";
	
		}

		$out_content .= "\t\t\t</select>\n\t\t";
	}
	
	// if it is a multifield, call this funtion recursively	
	if ($in_field['type'] == 'multi') {	
	
		if (!is_array($in_field['fields'])) {
		
			$out_content .= "<strong>Error: Multifield configured inproperly!</strong>";
	
		} else {
		
			// determine the separator
			if ($in_field['separator'] == 'space') $separator = '&nbsp;';
			else if ($in_field['separator'] == 'line') $separator = '</td></tr><tr><td>&nbsp;</td><td>';
			else $separator = $in_field['separator'];
		
			foreach ($in_field['fields'] as $this_field) {
			
				// add the seperator
				if($after_first) $out_content .= $separator;
				else $after_first = true; 
				
				$out_content .= antispam_form_write_field_element($this_field, $values, $key);
				
			}
		}
	}
	
	
	if($in_field['note']) $out_content .= ' <em>'.$in_field['note'].'</em>';

	return $out_content;
}


function antispam_form_random_label() {

	$labels[] = "Username";
	$labels[] = "Password";
	$labels[] = "First Name";
	$labels[] = "Last Name";
	$labels[] = "Name";
	$labels[] = "Email";
	$labels[] = "Email Address";
	$labels[] = "Phone Number";
	$labels[] = "Phone";
	$labels[] = "City";
	$labels[] = "State";
	$labels[] = "Comment";
	$labels[] = "Title";
	
	return $labels[array_rand($labels)];

}

function get_state_list() {
	
	$states[] = array('value' => '', 'abbr' => '- -', 'full' => '-- Select --');
	$states[] = array('value' => 'AK', 'abbr' => 'AK', 'full' => 'Alaska');
	$states[] = array('value' => 'AL', 'abbr' => 'AL', 'full' => 'Alabama');
	$states[] = array('value' => 'AR', 'abbr' => 'AR', 'full' => 'Arkansas');
	$states[] = array('value' => 'AZ', 'abbr' => 'AZ', 'full' => 'Arizona');
	$states[] = array('value' => 'CA', 'abbr' => 'CA', 'full' => 'California');
	$states[] = array('value' => 'CO', 'abbr' => 'CO', 'full' => 'Colorado');
	$states[] = array('value' => 'CT', 'abbr' => 'CT', 'full' => 'Connecticut');
	$states[] = array('value' => 'DC', 'abbr' => 'DC', 'full' => 'Washington, DC');
	$states[] = array('value' => 'DE', 'abbr' => 'DE', 'full' => 'Delaware');
	$states[] = array('value' => 'FL', 'abbr' => 'FL', 'full' => 'Florida');
	$states[] = array('value' => 'GA', 'abbr' => 'GA', 'full' => 'Georgia');
	$states[] = array('value' => 'HI', 'abbr' => 'HI', 'full' => 'Hawaii');
	$states[] = array('value' => 'IA', 'abbr' => 'IA', 'full' => 'Iowa');
	$states[] = array('value' => 'ID', 'abbr' => 'ID', 'full' => 'Idaho');
	$states[] = array('value' => 'IL', 'abbr' => 'IL', 'full' => 'Illinois');
	$states[] = array('value' => 'IN', 'abbr' => 'IN', 'full' => 'Indiana');
	$states[] = array('value' => 'KS', 'abbr' => 'KS', 'full' => 'Kansas');
	$states[] = array('value' => 'KY', 'abbr' => 'KY', 'full' => 'Kentucky');
	$states[] = array('value' => 'LA', 'abbr' => 'LA', 'full' => 'Louisiana');
	$states[] = array('value' => 'MA', 'abbr' => 'MA', 'full' => 'Massachusetts');
	$states[] = array('value' => 'MD', 'abbr' => 'MD', 'full' => 'Maryland');
	$states[] = array('value' => 'ME', 'abbr' => 'ME', 'full' => 'Maine');
	$states[] = array('value' => 'MI', 'abbr' => 'MI', 'full' => 'Michigan');
	$states[] = array('value' => 'MN', 'abbr' => 'MN', 'full' => 'Minnesota');
	$states[] = array('value' => 'MO', 'abbr' => 'MO', 'full' => 'Missouri');
	$states[] = array('value' => 'MS', 'abbr' => 'MS', 'full' => 'Mississippi');
	$states[] = array('value' => 'MT', 'abbr' => 'MT', 'full' => 'Montana');
	$states[] = array('value' => 'NC', 'abbr' => 'NC', 'full' => 'North Carolina');
	$states[] = array('value' => 'ND', 'abbr' => 'ND', 'full' => 'North Dakota');
	$states[] = array('value' => 'NE', 'abbr' => 'NE', 'full' => 'Nebraska');
	$states[] = array('value' => 'NH', 'abbr' => 'NH', 'full' => 'New Hampshire');
	$states[] = array('value' => 'NJ', 'abbr' => 'NJ', 'full' => 'New Jersey');
	$states[] = array('value' => 'NM', 'abbr' => 'NM', 'full' => 'New Mexico');
	$states[] = array('value' => 'NV', 'abbr' => 'NV', 'full' => 'Nevada');
	$states[] = array('value' => 'NY', 'abbr' => 'NY', 'full' => 'New York');
	$states[] = array('value' => 'OH', 'abbr' => 'OH', 'full' => 'Ohio');
	$states[] = array('value' => 'OK', 'abbr' => 'OK', 'full' => 'Oklahoma');
	$states[] = array('value' => 'OR', 'abbr' => 'OR', 'full' => 'Oregon');
	$states[] = array('value' => 'PA', 'abbr' => 'PA', 'full' => 'Pennsylvania');
	$states[] = array('value' => 'RI', 'abbr' => 'RI', 'full' => 'Rhode Island');
	$states[] = array('value' => 'SC', 'abbr' => 'SC', 'full' => 'South Carolina');
	$states[] = array('value' => 'SD', 'abbr' => 'SD', 'full' => 'South Dakota');
	$states[] = array('value' => 'TN', 'abbr' => 'TN', 'full' => 'Tennessee');
	$states[] = array('value' => 'TX', 'abbr' => 'TX', 'full' => 'Texas');
	$states[] = array('value' => 'US', 'abbr' => 'US', 'full' => 'United States');
	$states[] = array('value' => 'UT', 'abbr' => 'UT', 'full' => 'Utah');
	$states[] = array('value' => 'VA', 'abbr' => 'VA', 'full' => 'Virginia');
	$states[] = array('value' => 'VT', 'abbr' => 'VT', 'full' => 'Vermont');
	$states[] = array('value' => 'WA', 'abbr' => 'WA', 'full' => 'Washington');
	$states[] = array('value' => 'WI', 'abbr' => 'WI', 'full' => 'Wisconsin');
	$states[] = array('value' => 'WV', 'abbr' => 'WV', 'full' => 'West Virginia');
	$states[] = array('value' => 'WY', 'abbr' => 'WY', 'full' => 'Wyoming');

	return $states;
}

?>