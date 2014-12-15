<?php

class Welcome extends N8_Controller {

	function Welcome() {
		parent::__construct();
		$this->load->library('utilities');
		$this->load->helper('html');
	}

    /**
     * displays the home page
     */
	function index() {
		$this->auth->restrict();
		$this->load->model('notes_model', 'NM', TRUE);
		$this->load->view('budget/homeVW');

		$CI      =& get_instance();
		$home_vw = new Budget_HomeVW($CI);
		$home_vw->setTitle("Your Accounts");
		$home_vw->setScripts($this->jsincludes->welcome());

		//get the accounts
		$home_model = new Budget_BusinessModel_Home();
		$home_model->loadAccounts($this->session->userdata('user_id'));

        //get monthlyNeeds
        $home_vw->setTotalsArray($this->getTotalsArray($home_model->getAccounts()));

		$last_transaction = null;
		$category_dm      = new Budget_DataModel_CategoryDM();
		$transactions     = $category_dm->fetchTransactions("desc");

		//because we fetched the transactions in descending order we want the first one
		if( is_array($transactions) && count($transactions) > 0 ) {
			$last_transaction = $this->UserFriendlyTransactionDetails($this->transactionDetails($transactions, 0));
		}

		$home_vw->setLastTransaction($last_transaction);
		$home_vw->setNotes($this->NM->getAllNotes($this->session->userdata('user_id'), $this->session->userdata('user_id')));
		$home_vw->setLastUpdate(date('l F d, Y h:i:s a', strtotime($this->session->userdata('last_update'))));

		$home_vw->renderView();
	}

    /**
     * creates an array of monthlyNeed objects
     *
     * @param array $accounts
     * @return \monthlyNeed
     */
    private function getTotalsArray($accounts) {
        $totals_array = array();
        foreach($accounts as $account_dm) {
            $account_id = $account_dm->getAccountId();
            $totals_array[$account_id] = new monthlyNeed($account_dm);
        }

        return $totals_array;
    }

	function php_info() {
		$this->auth->restrict();
		$this->load->view('info.php');
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
