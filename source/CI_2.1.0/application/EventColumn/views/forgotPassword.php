<?php
	require_once('topViews/searchHeaderVW.php');
	/**
	 * Description of forgotPassword
	 *
	 * @author stretch
	 */
	class forgotPasswordVW extends searchHeaderVW {

		protected $forgot_password_form;

		public function __construct() {
			parent::__construct();
		}

		/**
		 * generates the view
		 *
		 * @return void
		 * @since 1.0
		 */
		public function generateView() {
			?>
			<div id="pane_container">
				<div id="left_pane" class='columns'>
					<h2>Forgot Password</h2>
					<div>
						Please provide your email so we can send you a temporary password.
					</div>
					<?=$this->forgot_password_form->renderForm();?>
				</div>
				<div id="right_pane" class='columns'>&nbsp;</div>
			</div>
			<?php
		}

		/**
		 * sets the forgot_password_form
		 *
		 * @param Form $forgot_password_form
		 * @return \forgotPasswordVW
		 * @since 1.0
		 */
		public function setForgotPasswordForm( Form $forgot_password_form) {
			$this->forgot_password_form = $forgot_password_form;
			return $this;
		}
	}

?>
