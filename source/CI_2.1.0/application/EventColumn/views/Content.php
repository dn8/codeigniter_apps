<?php
	require_once('baseVW.php');

	/**
	 * Description of ContentVW
	 *
	 * @author stretch
	 */
	class ContentVW extends BaseVW {

		protected $content;

		public function __construct() {
			parent::__construct();
		}

		protected function generateView() { ?>
			<div id="page-content">
				<?php
				if(is_object($this->content) && $this->content instanceof Form) {
					$this->content->renderForm();
				} else {
					echo $this->content;
				}
				?>
			</div>
			<?php
		}

		public function setContent($content) {
			$this->content = $content;
		}
	}

?>
