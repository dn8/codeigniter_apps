<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/17/18
 * Time: 11:31 AM
 */

namespace Funds;


use Transaction\Row;

class Distributor extends \CI_Model {

    /**
     * @var \Deposit\Row
     */
    private $deposit;

    private $user_id;

    /**
     * @var \Budget_DataModel_AccountDM
     */
    private $account_dm;

    public function __construct(\Deposit\Row $deposit, $user_id) {
        $this->deposit = $deposit;
        $this->user_id = $user_id;
    }

    /**
     * @throws \Exception
     */
    public function run() {
        $this->account_dm = new \Budget_DataModel_AccountDM($this->deposit->getFields()->getAccountId(), $this->user_id);
        $this->account_dm->loadCategories();
//        while($account_dm->getAccountAmount() > 0) {
            $this->distribute($this->deposit->getFields()->getDate()->format('Y-m-d H:i:s'));
//        }
    }

    /**
     * @param $date
     * @throws \Exception
     */
    private function distribute($date) {
        $divider = 1;

        //loop through each category and update the current amount
        $categories = $this->account_dm->orderCategoriesByDueFirst($date);
        foreach ($categories as $category_dms) {
            foreach ($category_dms as $category) {
                if ($this->deposit->getFields()->getRemaining() <= 0) {
                    break 2;
                }

                if ($category->getCurrentAmount() < $category->getAmountNecessary()) {
                    $deposit_amount = $this->calculateDepositAmount($category, $divider, $date);
                    $this->db->trans_start();
                    $this->updateCategoryAmount($category, $deposit_amount);
                    $this->updateRemainingAmount($deposit_amount);
                    $this->addTransaction($category, $deposit_amount, $date);
                    $this->db->trans_complete();
                }
            }
            $divider++;
        }
    }

    /**
     * @param $deposit_amount
     * @return bool
     * @throws \Exception
     */
    private function updateRemainingAmount($deposit_amount) {
        $total = subtract($this->deposit->getFields()->getRemaining(), $deposit_amount,2);
        $this->deposit->getFields()->setRemaining($total);

        return $this->deposit->save();
    }

    /**
     * @param \Budget_DataModel_CategoryDM $category
     * @param $deposit_amount
     * @return mixed
     * @throws \Exception
     */
    private function updateCategoryAmount(\Budget_DataModel_CategoryDM $category, $deposit_amount) {
        $new_category_amount = add($deposit_amount, $category->getCurrentAmount(),2);

        $category->setCurrentAmount($new_category_amount);

        if($category->saveCategory() === false) {
            throw new \Exception($category->getErrors());
        }

        return $deposit_amount;
    }

    /**
     * @param $category
     * @param $deposit_amount
     * @param $date
     * @throws \Exception
     */
    private function addTransaction($category, $deposit_amount, $date) {
        $transaction = new Row();
        $transaction->getStructure()->setToCategory($category->getCategoryId());
        $transaction->getStructure()->setFromAccount($this->account_dm->getAccountId());
        $transaction->getStructure()->setDepositId($this->deposit->getFields()->getId());
        $transaction->getStructure()->setOwnerId($this->user_id);
        $transaction->getStructure()->setTransactionAmount($deposit_amount);
        $transaction->getStructure()->setTransactionDate($date);
        $transaction->getStructure()->setTransactionInfo("Automatically distributed funds from ".$this->account_dm->getAccountName()." account into ".$category->getCategoryName());
        $transaction->saveTransaction();

        if($transaction->getErrors()) {
            throw new \Exception($transaction->getErrors());
        }
    }

    /**
     * @param \Budget_DataModel_CategoryDM $category
     * @param $divider
     * @param $date
     * @return float
     */
    private function calculateDepositAmount(\Budget_DataModel_CategoryDM $category, $divider, $date) {
        if( ($category->getDaysUntilDue($date) <= $this->account_dm->getPayFrequency())
            || (
                (
                    (round($category->getAmountNecessary() / $divider, 2)) + $category->getCurrentAmount()
                ) > $category->getAmountNecessary()
            )
        ) {

            $amount = subtract($category->getAmountNecessary(), $category->getCurrentAmount(),2);
        } else {
            $amount = divide($category->getAmountNecessary(), $divider,2);
        }

        if($amount > $this->deposit->getFields()->getRemaining()) {
            $amount = $this->deposit->getFields()->getRemaining();
        }

        return $amount;
    }
}