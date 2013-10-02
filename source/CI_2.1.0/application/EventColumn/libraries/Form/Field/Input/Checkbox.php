<?php

/**
 * generates a single text field for the Form object using the CI form_helper functions
 *
 * @author stretch
 */
class Field_Input_Checkbox extends Field_Input {

	protected $checked = false;

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
		$this->renderField(form_checkbox($this->filterAttributes(), '', $this->checked, $this->element_javascript));
	}

	public function setChecked($checked) {
		$this->checked = Utilities::getBoolean($checked);
		return $this;
	}

}

?>
