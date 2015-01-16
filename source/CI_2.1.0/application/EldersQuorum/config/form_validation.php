<?php
	/*
	 * This is the config file for all form validation on ceq
	 */
	$config = array(
		'add_report'	 => array(
			array(
				'field'	 => 'home_teacher',
				'label'	 => 'Home Teacher',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'family',
				'label'	 => 'Family',
				'rules'	 => 'required'
			),
            array(
                'field'  => 'date_of_visit',
                'label'  => 'Date Of Visit',
                'rules'  => 'required'
            ),
			array(
				'field'	 => 'assessment',
				'label'	 => 'Visit/Contact Type',
				'rules'	 => 'required'
			)
		)
	);
?>
