<?php
	require_once(APPPATH.'/views/budget/baseVW.php');

	class Budget_UserProfile_UserProfileVW extends Budget_BaseVW {

		private $user_dm;

		public function __construct(&$CI) {
			parent::__construct($CI);
		}

		/**
		 * generates the body of the view
		 *
		 * @access  protected
		 * @since   07.01.2013
		 */
		public function generateView() {
			//for some reason because this is in a class rather than just a file it is screwing up the html.
			//need to look into a template type structure rather than loading a bunch of files for header and footer.
			?>
			<form name="user_profile_form" action="/userCTL/update/" method="post">
				<div class="row">
					<div class="col-xs-3" style="margin:0 5px 0 0;">
						<label for="username">Username:</label>
					</div>
					<div class="col-xs-3">
						<span id="username"><?=$this->user_dm->getUsername();?></span>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-3" style="margin:3px 5px 3px 0;">
						<label for="email">Email:</label>
					</div>
					<div class="col-xs-3">
						<input type="text" value="<?=$this->user_dm->getEmail();?>" id="email" name="email" class="required email left" />
						<div class="error"> <?=form_error('email');?></div>
					</div>
				</div>
				<div class="row clear-both">
					<div class="col-xs-3" style="margin:3px 5px 3px 0;">
						<label for="new_password">New Password:</label>
					</div>
					<div class="col-xs-3">
						<input type="password" value="" id="new_password" name="new_password" />
					</div>
				</div>
				<div class="row">
					<div class="col-xs-3" style="margin:3px 5px 3px 0;">
						<label for="confirm_new_password">Confirm New Password:</label>
					</div>
					<div class="col-xs-3">
						<input type="password" value="" id="confirm_new_password" name="confirm_new_password" class="left" />
						<div class="error"> <?=form_error('confirm_new_password');?></div>
					</div>
				</div>
				<div class="row clear-both">
					<div class="col-xs-3" style="margin:3px 5px 3px 0;">
						<label for="password">Password:</label>
					</div>
					<div class="col-xs-3">
						<input type="password" value="" id="password" name="password" class="required left" />
						<div class="error"> <?=form_error('password');?></div>
					</div>
				</div>
				<div class="row clear-both">
					<div class="col-xs-3" style="margin:3px 5px 3px 0;">
						<input type="button" value="Submit Changes" name="user_form_submit" />
					</div>
				</div>
			</form>
			<?
		}

		public function setUserDM( $user_dm ) {
			$this->user_dm = $user_dm;
		}
	}
?>