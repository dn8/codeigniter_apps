<?php
/**
 * Copyright © Quantum Budgeting Systems, LLC.
 * All Rights Reserved.
 *
 * Date: 1/28/2019
 * Time: 7:56 PM
 */
namespace Traits;

trait Distribute {

    /**
     * @param \Budget_DataModel_AccountDM $account_dm
     * @throws Exception
     */
    protected function distribute($account_dm) {
        $fields = new \Deposit\Row\Fields();
        $fields->setOwnerId(get_instance()->session->user_id);
        $fields->setRemaining(0);
        $fields->setManualDistribution(false);
        $fields->setOperator('remaining', '>');
        $deposit = new \Deposit($fields, 'id DESC');

        $account_dm->orderCategoriesByDueFirst(date('Y-m-d'));
        $distributor = new \Funds\Distributor($account_dm);
        while($deposit->valid()) {
            $distributor->setDeposit($deposit->current());
            $distributor->run();
            $deposit->next();
        }
    }
}