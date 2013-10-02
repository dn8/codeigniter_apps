<?php

/**
 * generates a single text field for the Form object using the CI form_helper functions
 *
 * @author stretch
 */
class Field_Input_Checkbox_Radio extends Field_Input_Checkbox {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Generates the form field
	 *
	 * @return string
	 * @access public
	 * @since 1.0
	 */
	public function generateField() {
		$this->renderField(form_radio($this->filterAttributes(), '', $this->checked, $this->element_javascript));
	}

}

?>
