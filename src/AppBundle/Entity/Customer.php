<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\StringableInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Customer
 *
 * @ORM\Table(name="customer")
 * @Gedmo\Loggable(logEntryClass="AppBundle\Entity\LogEntry")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CustomerRepository")
 */
class Customer implements StringableInterface
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     * @Gedmo\Versioned
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="industry_vertical", type="string", length=50)
     * @Gedmo\Versioned
     */
    private $industryVertical;

    /**
     * @var string
     *
     * @ORM\Column(name="functions", type="json_array")
     * @Gedmo\Versioned
     */
    private $functions;

    /**
     * @var int
     *
     * @ORM\Column(name="total_seats", type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $totalSeats;

    /**
     * @var string
     *
     * @ORM\Column(name="percentage_of_business", type="decimal", precision=10, scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    private $percentageOfBusiness;

    /**
     * @var string
     *
     * @ORM\Column(name="work_period", type="string", length=30)
     * @Gedmo\Versioned
     */
    private $workPeriod;

    /**
     * @var Supplier
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Supplier", inversedBy="customers")
     * @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
     */
    private $supplier;

    /**
     * @var Location[]
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Location", inversedBy="customers")
     * @ORM\JoinTable(name="customers_locations")
     */
    private $locations;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Customer
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set industryVertical
     *
     * @param string $industryVertical
     *
     * @return Customer
     */
    public function setIndustryVertical($industryVertical)
    {
        $this->industryVertical = $industryVertical;

        return $this;
    }

    /**
     * Get industryVertical
     *
     * @return string
     */
    public function getIndustryVertical()
    {
        return $this->industryVertical;
    }

    /**
     * Set functions
     *
     * @param string $functions
     *
     * @return Customer
     */
    public function setFunctions($functions)
    {
        $this->functions = $functions;

        return $this;
    }

    /**
     * Get functions
     *
     * @return string
     */
    public function getFunctions()
    {
        return $this->functions;
    }

    /**
     * Set totalSeats
     *
     * @param integer $totalSeats
     *
     * @return Customer
     */
    public function setTotalSeats($totalSeats)
    {
        $this->totalSeats = $totalSeats;

        return $this;
    }

    /**
     * Get totalSeats
     *
     * @return int
     */
    public function getTotalSeats()
    {
        return $this->totalSeats;
    }

    /**
     * Set percentageOfBusiness
     *
     * @param string $percentageOfBusiness
     *
     * @return Customer
     */
    public function setPercentageOfBusiness($percentageOfBusiness)
    {
        $this->percentageOfBusiness = $percentageOfBusiness;

        return $this;
    }

    /**
     * Get percentageOfBusiness
     *
     * @return string
     */
    public function getPercentageOfBusiness()
    {
        return $this->percentageOfBusiness;
    }

    /**
     * Set workPeriod
     *
     * @param string $workPeriod
     *
     * @return Customer
     */
    public function setWorkPeriod($workPeriod)
    {
        $this->workPeriod = $workPeriod;

        return $this;
    }

    /**
     * Get workPeriod
     *
     * @return string
     */
    public function getWorkPeriod()
    {
        return $this->workPeriod;
    }

    /**
     * @return Supplier
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * @param Supplier $supplier
     */
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;
    }

    /**
     * @return Location[]
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * @param Location[] $locations
     *
     * @return Customer
     */
    public function setLocations($locations)
    {
        $this->locations = $locations;

        return $this;
    }


    public static function getIndustryVerticals()
    {
        return [
            'Automotive',
            'Banking',
            'Consumer',
            'Education',
            'Electronics',
            'Engineering',
            'Energy',
            'FMCG',
            'Financial',
            'Food and beverage',
            'Government - federal, state, local',
            'Healthcare',
            'Insurance',
            'Legal',
            'Manufacturing',
            'Media',
            'Not-for-Profit',
            'Online',
            'Real estate',
            'Recruitment',
            'Religion',
            'Retail',
            'Technology',
            'Telecommunications',
            'Transportation',
            'Travel',
        ];
    }

    public static function getYears()
    {
        return [
            'Under 12 months',
            '1-2 Years',
            '2-3 Years',
            '3-4 Years',
            '4-5 Years',
            '5 to 10 Years',
            '10 to 20 Years',
            '> 20 Years',
        ];
    }

    /**
     * @inheritDoc
     */
    public function toString()
    {
        return Profile::PROFILE_CUSTOMER;
    }

}

