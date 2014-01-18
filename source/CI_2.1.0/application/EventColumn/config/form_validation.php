<?php
	/*
	 * This is the config file for all form validation on EventColumn
	 */

	$config = array(
		'add_event'	 => array(
			array(
				'field'	 => 'event_name',
				'label'	 => 'Event Name',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'event_start_datetime',
				'label'	 => 'Start Date',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'event_end_datetime',
				'label'	 => 'End Date',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'event_details_locations[0][event_location_name]',
				'label'	 => 'Location',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'event_details_locations[0][lat_long]',
				'label'	 => 'Coordinates',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'event_details_locations[0][event_address]',
				'label'	 => 'Address',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'event_details_locations[0][event_city]',
				'label'	 => 'City',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'event_details_locations[0][event_state]',
				'label'	 => 'State',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'event_details_locations[0][event_zip]',
				'label'	 => 'Zipcode',
				'rules'	 => 'required|numeric|exact_length[5]'
			),
			array(
				'field'	 => 'event_details_locations[0][event_country]',
				'label'	 => 'Country',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'event_details_locations[0][smoking]',
				'label'	 => 'Smoking',
				'rules'	 => ''
			),
			array(
				'field'	 => 'event_details_locations[0][food]',
				'label'	 => 'Food',
				'rules'	 => ''
			),
			array(
				'field'	 => 'event_details_locations[0][age]',
				'label'	 => 'Age Range',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'event_description',
				'label'	 => 'Details',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'event_category',
				'label'	 => 'Category',
				'rules'	 => 'required'
			)
		),
		'add_user'	 => array(
			array(
				'field'	 => 'username',
				'label'	 => 'Username',
				'rules'	 => 'required|min_length[8]|max_length[32]|alpha_dash|is_unique[USERS.username]'
			),
			array(
				'field'	 => 'email',
				'label'	 => 'Email',
				'rules'	 => 'required|valid_email|is_unique[USERS.email]'
			),
			array(
				'field'	 => 'confirm_email',
				'label'	 => 'Confirm Email',
				'rules'	 => 'required|valid_email|matches[email]'
			),
			array(
				'field'	 => 'password',
				'label'	 => 'Password',
				'rules'	 => 'required|min_length[8]|max_length[32]|callback_validate_password'//using a callback to validate
			),
			array(
				'field'	 => 'password_retype',
				'label'	 => 'Password Confirmation',
				'rules'	 => 'required|min_length[8]|max_length[32]|matches[password]'
			),
			array(
				'field'	 => 'zip',
				'label'	 => 'Zip',
				'rules'	 => 'required|exact_length[5]|numeric'
			),
			array(
				'field'	 => 'agree_to_terms',
				'label'	 => 'Agree To Terms and Policies',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'recaptcha_response_field',
				'label'	 => 'Captcha',
				'rules'	 => 'callback_validate_captcha'
			)
		),
		'contact_us' => array (
			array(
				'field'	 => 'username',
				'label'	 => 'Username',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'email',
				'label'	 => 'Email',
				'rules'	 => 'required|valid_email'
			),
			array(
				'field' => 'subject',
				'label' => 'Subject',
				'rules' => 'required'
			),
			array(
				'field' => 'email_text',
				'label' => 'Email Text',
				'rules' => 'required|max_length[1000]'
			),
			array(
				'field'	 => 'recaptcha_response_field',
				'label'	 => 'Captcha',
				'rules'	 => 'callback_validate_captcha'
			)
		),
		'mini_search' => array(
			array(
				'field'	 => 'mini_search_zip',
				'label'	 => 'Zip',
				'rules'	 => 'required|exact_length[5]|numeric'
			)
		),
		'advanced_search' => array(
			array(
				'field' => 'title',
				'label' => 'Event Title',
				'rules' => ''
			),
			array(
				'field' => 'city',
				'label' => 'City',
				'rules' => 'alpha'
			),
			array(
				'field' => 'state',
				'label' => 'State',
				'rules' => ''
			),
			array(
				'field' => 'zip',
				'label' => 'Zip',
				'rules' => 'numeric|exact_length[5]'
			),
			array(
				'field' => 'start_date',
				'label' => 'Start Date',
				'rules' => ''
			),
			array(
				'field' => 'end_date',
				'label' => 'End Date',
				'rules' => ''
			)
		)
	);
?>
