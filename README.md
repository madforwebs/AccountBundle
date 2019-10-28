CalendarBundle
=============

The `BookingBundle` means easy-to-implement and feature-rich calendar in your Symfony application!

## Installation

### Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require madforwebs/financial-bundle
```
This command requires you to have Composer installed globally, as explained
in the `installation chapter` of the Composer documentation.

### Enable the Bundle


Then, enable the bundle by adding the following line in the ``app/AppKernel.php``
file of your project:

```php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new MadForWebs\FinancialBundle\FinancialBundle(),
        );

        // ...
    }

    // ...
}
```
    
## Import service calendar to app    

You must define import service from @CalendarBundle like this in your config.yml

```yml
    imports:
        ...
        - { resource: "@FinancialBundle/Resources/config/services.yml" }
        ...
```   
 
## Import calendar festive days    

An example use service to import festive days

```php
    /** @var CalendarHandler $calendarHandler */
    $calendarHandler = $this->get('mad_for_webs_calendar.handler');
    $calendarHandler->createDaysFromCalendar();
```

 
## Extend class account

You can extend class

```php
    use MadForWebs\FinancialBundle\Entity\Account as BaseAccount;
    
    /**
     * @ORM\Entity
     * @ORM\Table(name="mfw_account")
     */
    class Account extends BaseAccount
    {
        ...

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
}
```



```php
    use MadForWebs\FinancialBundle\Entity\Expenditure as BaseExpenditure;

    /**
     * Expenditure
     *
     * @ORM\Table(name="expenditure")
     * @ORM\Entity(repositoryClass="FinancialBundle\Repository\ExpenditureRepository")
     */
    class Expenditure
    {

        /**
         * @ORM\ManyToOne(targetEntity="Provider", inversedBy="expenditures")
         * @ORM\JoinColumn(name="provider", referencedColumnName="id", nullable=true)
         */
        private $provider;


        /**
         * @ORM\ManyToOne(targetEntity="Account", inversedBy="expenditures")
         * @ORM\JoinColumn(name="account", referencedColumnName="id", nullable=true)
         */
        private $account;

        /**
         * @var string
         * @ORM\Column(name="way_to_pay", type="string", columnDefinition="enum('card','bank','transfer','cash','paydesk', 'gratification')" , nullable=false)
         */
        private $wayToPay;


    }







```php
    use MadForWebs\FinancialBundle\Entity\Income as BaseIncome;

    /**
     * Income
     *
     * @ORM\Table(name="income")
     * @ORM\Entity(repositoryClass="FinancialBundle\Repository\IncomeRepository")
     */
    class Income
    {

        /**
         * @ORM\OneToOne(targetEntity="Income", inversedBy="linkedIncome")
         * @ORM\JoinColumn(name="linkedIncome", referencedColumnName="id", nullable=true)
         */
        private $linkedIncome;


        /**
         * @ORM\ManyToOne(targetEntity="Account", inversedBy="earnings")
         * @ORM\JoinColumn(name="account", referencedColumnName="id", nullable=false)
         */
        private $account;

        /**
         * @ORM\ManyToOne(targetEntity="Account", inversedBy="earningsOrigins")
         * @ORM\JoinColumn(name="accountDestiny", referencedColumnName="id", nullable=true)
         */
        private $accountDestiny;
    }




```php
    use MadForWebs\FinancialBundle\Entity\Provider as BaseProvider;

    /**
     * Provider
     *
     * @ORM\Table(name="provider")
     * @ORM\Entity(repositoryClass="FinancialBundle\Repository\ProviderRepository")
     */
    class Provider
    {

        /**
         * @ORM\OneToMany(targetEntity="Expenditure", mappedBy="provider", cascade={"all"})
         * @ORM\OrderBy({"dateBuy"="DESC"})
         */
        private $expenditures;

        /**
         * @ORM\OneToMany(targetEntity="Product", mappedBy="provider", cascade={"all"})
         * @ORM\OrderBy({"name"="ASC"})
         */
        private $products;
    }