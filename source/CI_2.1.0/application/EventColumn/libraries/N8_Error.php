<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class N8_Error {

	const ERROR = 'error';
	const DEBUG = 'debug';
	const INFO = 'info';

	private $messages = array(
	    self::ERROR => array(),
	    self::DEBUG => array(),
	    self::INFO => array()
	);

	function __construct() {
		// log_message('debug', "N8_Error Class Initialized");
	}

	/**
	 * @return array $this->errors
	 */
	public function getErrors() {
		return $this->getMessages(self::ERROR);
	}

	public function setErrors(array $errors) {
		$this->messages[self::ERROR] = $errors;
	}

	public function addError($error) {
		$this->messages[self::ERROR][] = $error;
	}

	/**
	 * Sets the message into the array which is publicly accessible via getErrors()
	 * logs message and message_type
	 *
	 * @param String $message
	 * @param string $message_type  ex. 'error', 'debug', 'info'
	 * @return void
	 */
	protected function setError($message, $message_type = self::ERROR) {
		log_message($message_type, $message);
		$this->messages[$message_type][] = $message;
	}

	/**
	 * Quick check to see if we have any errors
	 *
	 * @return bool
	 */
	public function isErrors() {
		$return = false;
		if (count($this->messages[self::ERROR]) > 0) {
			$return = true;
		}
		return $return;
	}

	public function getMessages($message_type) {
		return $this->messages[$message_type];
	}

}

