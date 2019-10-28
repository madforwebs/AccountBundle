<?php

/*
 * This file is part of the MadForWebs package
 *
 * Copyright (c) 2017 Fernando Sánchez Martínez
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Fernando Sánchez Martínez <fer@madforwebs.com>
 */

namespace MadForWebs\AccountBundle\Service;

use CoreBundle\Entity\Account;
use CoreBundle\Entity\Bill;
use CoreBundle\Entity\Client;
use CoreBundle\Entity\Contract;
use CoreBundle\Entity\Emplacement;
use CoreBundle\Entity\Expenditure;
use CoreBundle\Entity\Income;
use CoreBundle\Entity\Room;

class AccountHandler
{
    protected $em;

    private $router;

    private $translator;

    public function __construct($entityManager, $router, $translator)
    {
        $this->em = $entityManager;
        $this->router = $router;
        $this->translator = $translator;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @param Income $movement
     * @return float|int Balance Destiny Account
     */
    public function createMovementAccountDestiny(Income $movement)
    {


        $total = ($movement->getTotal()*-1);

//        echo "lala";
//        exit;
        $accountDestiny = $this->getEm()->getRepository('CoreBundle:Account')->find($movement->getAccountDestiny()->getId());
        $balance = $this->calculateBalance($accountDestiny);
        $balance = $balance + ($movement->getTotal() * -1);
        $accountDestiny->setBalance($balance);
        $this->getEm()->persist($accountDestiny);
        $newIncomeInDestiny = new Income();
        $newIncomeInDestiny->setAccount($accountDestiny);
        $newIncomeInDestiny->setMethod('income');
//        $newIncomeInDestiny->setWayToPay($movement->getWayToPay());
        $newIncomeInDestiny->setConcept($movement->getConcept());
        $newIncomeInDestiny->setDateIncome($movement->getDateIncome());
        $newIncomeInDestiny->setTotal($total);

        $newIncomeInDestiny->setLinkedIncome($movement);

        $movement->setLinkedIncome($newIncomeInDestiny);

//        dump($newIncomeInDestiny);
//        exit;

        $this->getEm()->persist($movement);
        $this->getEm()->persist($newIncomeInDestiny);
        $this->getEm()->flush();

        return $balance;
    }

    public function calculateBalance(Account $account)
    {
        $balance = 0;
        if($incomes = $account->getEarnings()  ){
            /** @var Income $income */
            foreach ( $incomes as $income) {

//                if($income->getMethod() == 'income'){
//                echo "balance:". $balance;
                    $balance += $income->getTotal();
//                echo " ".$income->getMethod()." cantidad:". $income->getTotal();
//                echo " balance:". $balance;
//                echo "<br/>";

//                }elseif ($income->getMethod() == 'expenditure'){
//                    $balance -= $income->getTotal();
//                }

            }
        }

//        exit;

        if($expenditures = $account->getExpenditures()  ){
            /** @var Expenditure $expenditure */
            foreach ( $expenditures as $expenditure) {
                if($expenditure->getStatus() == 'paid'){
                    $balance -= $expenditure->getTotal();
                }
            }
        }

        if($bills = $account->getBills()  ){
            /** @var Bill $bill */
            foreach ( $bills as $bill) {
                if($bill->getPaid() ){
                    $balance += $bill->getPaid();
                }
            }
        }
        return $balance;
    }
}
