<?php

namespace MadForWebs\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
class Account
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\Year", inversedBy="accounts", cascade={"persist"})
     * @ORM\JoinColumn(name="year", referencedColumnName="id", nullable=true)
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="balance", type="float",precision=2)
     */
    private $balance;

    /**
     * @ORM\OneToMany(targetEntity="Expenditure", mappedBy="account", cascade={"all"})
     * @ORM\OrderBy({"dateBuy"="DESC"})
     */
    private $expenditures;

    /**
     * @ORM\OneToMany(targetEntity="Income", mappedBy="account", cascade={"all"})
     * @ORM\OrderBy({"dateIncome"="DESC"})
     */
    private $earnings;

    /**
     * @ORM\OneToMany(targetEntity="Income", mappedBy="accountDestiny", cascade={"all"})
     * @ORM\OrderBy({"dateIncome"="DESC"})
     */
    private $earningsOrigins;

    /**
     * @ORM\OneToMany(targetEntity="Bill", mappedBy="account", cascade={"all"})
     * @ORM\OrderBy({"client"="ASC"})
     */
    private $bills;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    public function totalEarnings()
    {
        $sum = 0;

        if (count($this->getEarnings())) {
            /** @var Income $earning */
            foreach ($this->getEarnings() as $earning) {
                $sum += $earning->getTotal();
            }
        }

        return $sum;
    }

    public function totalExpenditures()
    {
        $sum = 0;
        if (count($this->getExpenditures())) {
            /** @var Expenditure $earning */
            foreach ($this->getExpenditures() as $earning) if ($earning->getStatus() == 'paid') {
                $sum += $earning->getTotal();
            }
        }
        return $sum;
    }

    public function totalBills()
    {
        $sum = 0;
        if (count($this->getBills())) {
            /** @var Bill $earning */
            foreach ($this->getBills() as $earning) if ($earning->getPaid()) {
                $sum += $earning->calculateTotal();
            }
        }
        return $sum;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->setCreatedAt(new \DateTime('now'));
        $this->setUpdatedAt(new \DateTime('now'));
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->setUpdatedAt(new \DateTime('now'));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    /**
     * Set balance
     *
     * @param float $balance
     *
     * @return Account
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance
     *
     * @return float
     */
    public function getBalance()
    {
        return $this->balance;
    }

    public function __toString()
    {
        return ($this->getName() == '') ? '' : $this->getName();
    }

    /**
     * @return mixed
     */
    public function getExpenditures()
    {
        return $this->expenditures;
    }

    /**
     * @param mixed $expenditures
     */
    public function setExpenditures($expenditures)
    {
        $this->expenditures = $expenditures;
    }

    /**
     * @return mixed
     */
    public function getEarnings()
    {
        return $this->earnings;
    }

    /**
     * @param mixed $earnings
     */
    public function setEarnings($earnings)
    {
        $this->earnings = $earnings;
    }

    public function addEarning($earning)
    {
        $this->earnings[] = $earning;
    }

    public function removeEarning($earning)
    {
        $this->earnings->removeElement($earning);
    }

    /**
     * @return mixed
     */
    public function getBills()
    {
        return $this->bills;
    }

    /**
     * @param mixed $bills
     */
    public function setBills($bills)
    {
        $this->bills = $bills;
    }

    public function addExpenditure($expenditure)
    {
        $this->expenditures[] = $expenditure;
    }

    public function removeExpenditure($expenditure)
    {
        $this->expenditures->removeElement($expenditure);
    }


    public function addBill($bill)
    {
        $this->bills[] = $bill;
    }

    public function removeBill($bill)
    {
        $this->bills->removeElement($bill);
    }

    /**
     * @return mixed
     */
    public function getEarningsOrigins()
    {
        return $this->earningsOrigins;
    }

    /**
     * @param mixed $earningsOrigins
     */
    public function setEarningsOrigins($earningsOrigins)
    {
        $this->earningsOrigins = $earningsOrigins;
    }

    public function addEarningOrigin($earningOrigin)
    {
        $this->earningsOrigins[] = $earningOrigin;
    }

    public function removeEarningOrigin($earningOrigin)
    {
        $this->earningsOrigins->removeElement($earningOrigin);
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param mixed $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }



}

