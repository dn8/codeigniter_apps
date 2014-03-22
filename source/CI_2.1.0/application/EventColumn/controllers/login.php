<?php
	if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	require_once(APPPATH . 'third_party/phpass-0.3/PasswordHash.php');

	/**
	 * Description of login
	 *
	 * @author stretch
	 */
	class login extends N8_Controller {

		public function __construct() {
			parent::__construct();
			$this->load->helper('form_validation');
		}

		public function index() {
			$this->load->view( 'UserLogin' );
			try {
				$login_form = new Form();
				$login_form->setAction( "login/processLogin" );
				$login_form->setId("login_form");

				$field = Form::getNewField( Form_Field::FIELD_TYPE_INPUT );
				$field->setLabel( "Username" );
				$field->setValue( $this->input->post( $field->getName() ) );
				$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

				$login_form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_PASSWORD );
				$field->setLabel( "Password" );
				$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

				$login_form->addField( $field );

				$field = Form::getNewField( Form_Field::FIELD_TYPE_BUTTON );
				$field->setId( "login-submit" );
				$field->setContent( "Login" );

				$login_form->addField( $field );

				$this->view = new UserLoginVW();
				$this->view->setPageId('login');
				$this->view->setErrors( $this->getErrors() );
				$this->view->setLoginForm( $login_form );

				$this->view->renderView();
			} catch( Exception $e ) {
				$this->logMessage( $e->getMessage(), N8_Error::ERROR );
				show_error( "there was an error loading this page. Please try again <!-- {$e->getMessage()} -->", 500 );
			}
		}

		/**
		 * process the user login
		 *
		 * @return void
		 * @since  1.0
		 */
		public function processLogin() {
			if(!$this->auth->isSiteActive()) {
				redirect("/inactive/");
			}

			if($this->validate('login')) {
				$login = $this->auth->process_login($this->input->post('username'), $this->input->post('password'));
				if($login !== true) {
					$this->setError($login);
					$this->index();
				} else {
					// Login successful, let's redirect.
					$this->auth->redirect();
				}
			}
		}

		/**
		 * main controller method for forgot password.
		 *
		 *
		 */
		public function forgotPassword() {
			$this->load->view('forgotPassword');

			try {
				$form = new Form();
				$form->setAction( "login/generatePassword" );
				$form->setId("forgot_password_form");

				$field = Form::getNewField( Form_Field::FIELD_TYPE_INPUT );
				$field->setLabel( "Email" );
				$field->setValue( $this->input->post( 'email' ) );
				$field->addErrorLabel( 'error', null, form_error( 'email' ) );

				$form->addField($field);

				$field = Form::getNewField( Form_Field::FIELD_TYPE_BUTTON );
				$field->setId( "forgot_password_submit" );
				$field->setContent( "Submit" );

				$form->addField( $field );

				//add the view
				$this->view = new forgotPasswordVW();
				$this->view->setPageId('login');
				$this->view->setErrors( $this->getErrors() );
				$this->view->setForgotPasswordForm( $form );
				$this->view->renderView();

			} catch(Exception $e) {
				$this->logError( $e->getMessage() );
				show_error( "there was an error loading this page. Please try again <!-- {$e->getMessage()} -->", 500 );
			}

		}

		/**
		 * Generates a random password
		 *
		 * @return void
		 * @since 1.0
		 */
		public function generatePassword() {
			if($this->validate('forgot_password')) {
				try {
					$this->load->helper('password');
					$password      = passwordHelper::generatePassword();

					$phpass		   = new PasswordHash( Auth::PHPASS_ITERATIONS, Auth::PHPASS_PORTABLE_HASH );
					$password_hash = $phpass->HashPassword( $password );
					if($this->savePassword($this->input->post('email'), $password_hash) === true) {
						$this->sendPasswordEmail($this->input->post('email'), $password);
					} else {
						$this->forgotPassword();
					}
				} catch(Exception $e) {
					$this->logMessage($e->getMessage(), N8_Error::ERROR);
					$this->forgotPassword();
				}
			}
		}

		/**
		 * saves the new password to the user profile
		 *
		 * @param string $email
		 * @param string $password_hash
		 * @return boolean
		 * @since 1.0
		 */
		private function savePassword($email, $password_hash) {
			$result = true;
			try {
				$user_profile_dm = new UserProfileDM();
				$user_profile_dm->setEmail($email);
				$user_profile_dm->loadProfileByEmail();

				if($user_profile_dm->getUsername()) {
					$user_profile_dm->setPassword($password_hash);
					$user_profile_dm->setTemporaryPassword(true);
					$user_profile_dm->save();
				}
			} catch(Exception $e) {
				$this->logMessage($e->getMessage(), N8_Error::ERROR);
				$result = false;
			}

			return $result;
		}

		/**
		 * sends an email to the user with their new password
		 *
		 * @param string $email
		 * @param string $raw_password
		 * @throws Exception
		 */
		private function sendPasswordEmail($email, $raw_password) {
			//load the CI email library
			$this->load->library('email');

			$this->email->from('webmaster@EventColumn.com');
			$this->email->to($email);
			$this->email->subject('password reset information');

			$message = "You recently requested a new password at EventColumn.com, your new password is ".$raw_password .
					" if you feel this password reset is in error or you did not request a password reset please contact us".
					"<br /><br />Thank You,<br />The EventColumn Staff";

			$this->email->message($message);

			if(!$this->email->send()) {
				throw new Exception("unable to send email to " . $email);
			}
		}

		/**
		 * logs out of a session
		 */
		public function logout() {
			$this->auth->logout();
		}
	}

?>
