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
			$this->load->view( 'UserLogin' );
			$this->view = new UserLoginVW();
			$this->view->setPageId('login');
		}

		public function index() {
			try {
				//build the login form
				$login_form = $this->buildLoginForm();

				//build the register form
				$register_form = $this->buildRegisterForm();

				$this->view->setErrors( $this->getErrors() );
				$this->view->setLoginForm( $login_form );
				$this->view->setRegisterForm( $register_form );

				$this->view->renderView();
			} catch( Exception $e ) {
				$this->logMessage( $e->getMessage(), N8_Error::ERROR );
				show_error( "there was an error loading this page. Please try again <!-- {$e->getMessage()} -->", 500 );
			}
		}

		/**
		 * builds the login form
		 *
		 * @return \Form
		 * @access private
		 * @since 1.1
		 */
		private function buildLoginForm() {
			$login_form = new Form();
			$login_form->setAction( "login/processLogin" );
			$login_form->setId("login_form");

			$login_form->addField( $this->buildField(Form_Field::FIELD_TYPE_INPUT, "login_username", "Username"));

			/*
			 * We'll start with an input field and have jquery convert it to a password field
			 * once the user focuses on the field. this allows us to put our label inside the field
			 */
			$login_form->addField( $this->buildField(Form_Field::FIELD_TYPE_INPUT, "login_password", "Password"));

			$field = Form::getNewField( Form_Field::FIELD_TYPE_SUBMIT );
			$field->setId( "login_submit" );
			$field->setValue( "Login" );

			$login_form->addField( $field );

			return $login_form;
		}

		/**
		 * builds the register form
		 *
		 * @return \Form
		 * @access private
		 * @since 1.1
		 */
		private function buildRegisterForm() {
			$register_form = new Form();
			$register_form->setAction( "login/addUser" );
			$register_form->setId('register_form');

			$register_form->addField( $this->buildField(Form_Field::FIELD_TYPE_INPUT, "username", "Username"));
			$register_form->addField( $this->buildField(Form_Field::FIELD_TYPE_INPUT, "email", "Email"));
			$register_form->addField( $this->buildField(Form_Field::FIELD_TYPE_INPUT, "confirm_email", "Confirm Email"));
			/*
			 * We'll start with an input field and have jquery convert it to a password field
			 * once the user focuses on the field. this allows us to put our label inside the field
			 */
			$password_field = $this->buildField(Form_Field::FIELD_TYPE_INPUT, "password", "Password");
			$password_field->setClass("replace_type new_type_password toggle_text");

			$register_form->addField($password_field);

			$confirm_password_field = $this->buildField(Form_Field::FIELD_TYPE_INPUT, "confirm_password", "Confirm Password");
			$confirm_password_field->setClass("replace_type new_type_password toggle_text");

			$register_form->addField($confirm_password_field);

			$zip_field = $this->buildField(Form_Field::FIELD_TYPE_INPUT, "zip", "Zip");
			$zip_field->setMaxLength("5");

			$register_form->addField($zip_field);

			$field = Form::getNewField( Form_Field::FIELD_TYPE_CHECKBOX );
			$field->setLabelContainerClass("float_right checkbox_label");
			$field->setLabel( "Agree to Terms and Policies" );
			$field->setValue( "agreed" );
			if( $this->input->post( $field->getName() ) == 'agreed' ) {
				$field->setChecked( true );
			}
			$field->addErrorLabel( 'error', null, form_error( $field->getName() ) );

			$register_form->addField( $field );

			$field = Form::getNewField( Form_Field::FIELD_TYPE_RECAPTCHA );
			$field->setContainerClass("recaptcha_container");
			$field->setLabel( "Please proove you're human" );
			$field->addErrorLabel('error', 'recaptcha_error', form_error('recaptcha_response_field'));

			$register_form->addField( $field );

			$field = Form::getNewField( Form_Field::FIELD_TYPE_BUTTON );
			$field->setId( "register_submit" );
			$field->setContent( "Sign Up" );

			$register_form->addField( $field );

			return $register_form;
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
		 * @access public
		 * @since 1.0
		 * @return void
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

		/**
		 * Validates the user info and adds the user to the database
		 *
		 * @return void
		 * @since 1.0
		 */
		public function addUser() {
			require_once(APPPATH . 'third_party/phpass-0.3/PasswordHash.php');
			if( $this->validate( 'add_user' ) ) {
				//add some validation before hashing the password. 32 chars max on pw.
				$phpass		 = new PasswordHash( Auth::PHPASS_ITERATIONS, Auth::PHPASS_PORTABLE_HASH );
				$password	 = $phpass->HashPassword( $this->input->post( 'password' ) );

				$user_profile_dm = new UserProfileDM();
				$user_profile_dm->setUsername( $this->input->post( 'username' ) );
				$user_profile_dm->setEmail( $this->input->post( 'email' ) );
				$user_profile_dm->setPassword( $password );
				$user_profile_dm->setZip( $this->input->post( 'zip' ) );
				$user_profile_dm->setAgreeToTerms(true);

				if( !$user_profile_dm->save() ) {
					$message = 'Unable to save user [' . $this->input->post( 'username' ) . ']';

					$this->addError( $message );

					$message .= " with email [" . $this->input->post( 'email' ) . "]";
					$message .= " and zip [" . $this->input->post( 'zip' ) . "]";

					$this->logMessage( $message, N8_Error::ERROR );

					$this->index();
				} else {
					//account created, log user in.
					$login = $this->auth->process_login($this->input->post('username'), $this->input->post('password'));
					if($login !== true) {
						$this->setError($login);
						$this->index();//@todo do something better than this.
					} else {
						// Login successful, let's redirect.
						$this->auth->redirect();
					}
				}
			} else {
				$this->index();
			}
		}
	}

?>
