<?

	/*
		FORM FIELD VALIDATOR 1.0

		This script uses regular expressions to validate common types of form values. Functions include:
		
		FUNCTION LIST:
			- valid_fullname_field($value, $required = true);
			- valid_name_field($value, $required = true);
			- valid_words_field($value, $required = true);
			- valid_email_field ($value, $required = true);
			- valid_zip_code_field ($value, $required = true);
			- valid_phone_number($value, $required = true);
			- valid_state_field($value, $required = true);
			- valid_option_field($value, $options, $required = true);
			- valid_comment_field($value, $required = true);

		CHANGES IN 1.1
			- fixed false positives in valid_words_field
			- fixed false positives in valid_comment_field

	*/

	function valid_fullname_field($value, $required = true) {

		if(!$required && $value=="") return true;

		if(!valid_words_field($value)) return false;

		if (num_words($value) > 4 || num_words($value) < 2) return false;

		return true;

	}

	function valid_name_field($value, $required = true) {

		if(!$required && $value=="") return true;

		if(!valid_words_field($value)) return false;

		if (num_words($value) > 3 || num_words($value) < 1) return false;

		return true;

	}

	function valid_words_field($value, $required = true) {

		if($required && $value=="") return false;

		if(ereg('[^A-Za-z. -]', $value)) return false;

		return true;

	}

	function valid_email_field ($value, $required = true) {

		if(!$required && $value=="") return true;

		if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $value)) return false;

		return true;

	}

	function valid_zip_code_field ($value, $required = true) {

		if(!$required && $value=="") return true;

		if (!eregi("^[0-9]{5}(-[0-9]{4})?$", $value)) return false;

		return true;

	}

	function valid_phone_field($value, $required = true) {

		if(!$required && $value=="") return true;

		if (!eregi('^[(]?[2-9]{1}[0-9]{2}[) .-]{0,2}[0-9]{3}[- .]?[0-9]{4}[ ]?$', $value)) return false;

		return true;

	}



	function valid_state_field($value, $required = true) {

		if(!$required && $value=="") return true;

		$states = valid_state_field_list();

		foreach($states as $state) if(strtoupper($value) == $state['abbr']) return true;

		return false;

	}

	function valid_option_field($value, $options, $required = true) {

		if(!$required && $value=="") return true;

		if(!is_array($options)) return false;

		foreach($options as $option) if ($value === $option) return true;

		return false;
	}


	function valid_comment_field($value, $required = true) {

		if($required && $value=="") return false;

		return true;

	}

// ------------   HELPER FUNCTIONS ------------------------

	function valid_state_field_list() {

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


	function num_words ($value) {

		if(empty($value)) return 0;

		$value = rtrim($value, ' ');

		return sizeof(split(' ', $value));

	}

?>
