<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\StringableInterface;
use AppBundle\Model\Place;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Location
 *
 * @ORM\Table(name="location")
 * @Gedmo\Loggable(logEntryClass="AppBundle\Entity\LogEntry")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LocationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Location implements StringableInterface
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
     * @ORM\Column(name="full_address", type="string")
     * @Gedmo\Versioned
     */
    private $fullAddress;

    /**
     * @var Place
     *
     * @ORM\Embedded(class="AppBundle\Model\Place")
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="years_open", type="string", length=50)
     * @Gedmo\Versioned
     */
    private $yearsOpen;

    /**
     * @var Customer[]
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Customer", mappedBy="locations")
     */
    private $customers;

    /**
     * @var int
     *
     * @ORM\Column(name="total_seats", type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $totalSeats;

    /**
     * @var int
     *
     * @ORM\Column(name="available_seats", type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $availableSeats;

    /**
     * @var string
     * @ORM\Column(name="operate_from", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $operateFrom;

    /**
     * @var string
     * @ORM\Column(name="conduct_in", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $conductIn;

    /**
     * @var bool
     * @ORM\Column(name="noise_cancelling", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $noiseCancelling;

    /**
     * @var \DateTime
     * @ORM\Column(name="monday_open_time", type="time", nullable=true)
     * @Gedmo\Versioned
     */
    private $mondayOpenTime;

    /**
     * @var \DateTime
     * @ORM\Column(name="monday_close_time", type="time", nullable=true)
     * @Gedmo\Versioned
     */
    private $mondayCloseTime;

    /**
     * @var bool
     * @ORM\Column(name="is_monday_open_24_hours", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $isMondayOpen24Hours;

    /**
     * @var boolean
     * @ORM\Column(name="is_monday_closed", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $isMondayClosed;

    /**
     * @var \DateTime
     * @ORM\Column(name="tuesday_open_time", type="time", nullable=true)
     * @Gedmo\Versioned
     */
    private $tuesdayOpenTime;

    /**
     * @var \DateTime
     * @ORM\Column(name="tuesday_close_time", type="time", nullable=true)
     * @Gedmo\Versioned
     */
    private $tuesdayCloseTime;

    /**
     * @var bool
     * @ORM\Column(name="is_tuesday_open_24_hours", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $isTuesdayOpen24Hours;

    /**
     * @var boolean
     * @ORM\Column(name="is_tuesday_closed", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $isTuesdayClosed;

    /**
     * @var \DateTime
     * @ORM\Column(name="wednesday_open_time", type="time", nullable=true)
     * @Gedmo\Versioned
     */
    private $wednesdayOpenTime;

    /**
     * @var \DateTime
     * @ORM\Column(name="wednesday_close_time", type="time", nullable=true)
     * @Gedmo\Versioned
     */
    private $wednesdayCloseTime;

    /**
     * @var bool
     * @ORM\Column(name="is_wednesday_open_24_hours", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $isWednesdayOpen24Hours;

    /**
     * @var boolean
     * @ORM\Column(name="is_wednesday_closed", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $isWednesdayClosed;

    /**
     * @var \DateTime
     * @ORM\Column(name="thursday_open_time", type="time", nullable=true)
     * @Gedmo\Versioned
     */
    private $thursdayOpenTime;

    /**
     * @var \DateTime
     * @ORM\Column(name="thursday_close_time", type="time", nullable=true)
     * @Gedmo\Versioned
     */
    private $thursdayCloseTime;

    /**
     * @var bool
     * @ORM\Column(name="is_thursday_open_24_hours", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $isThursdayOpen24Hours;

    /**
     * @var boolean
     * @ORM\Column(name="is_thursday_closed", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $isThursdayClosed;

    /**
     * @var \DateTime
     * @ORM\Column(name="friday_open_time", type="time", nullable=true)
     * @Gedmo\Versioned
     */
    private $fridayOpenTime;

    /**
     * @var \DateTime
     * @ORM\Column(name="friday_close_time", type="time", nullable=true)
     * @Gedmo\Versioned
     */
    private $fridayCloseTime;

    /**
     * @var bool
     * @ORM\Column(name="is_friday_open_24_hours", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $isFridayOpen24Hours;

    /**
     * @var boolean
     * @ORM\Column(name="is_friday_closed", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $isFridayClosed;

    /**
     * @var \DateTime
     * @ORM\Column(name="saturday_open_time", type="time", nullable=true)
     * @Gedmo\Versioned
     */
    private $saturdayOpenTime;

    /**
     * @var \DateTime
     * @ORM\Column(name="saturday_close_time", type="time", nullable=true)
     * @Gedmo\Versioned
     */
    private $saturdayCloseTime;

    /**
     * @var bool
     * @ORM\Column(name="is_saturday_open_24_hours", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $isSaturdayOpen24Hours;

    /**
     * @var boolean
     * @ORM\Column(name="is_saturday_closed", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $isSaturdayClosed;

    /**
     * @var \DateTime
     * @ORM\Column(name="sunday_open_time", type="time", nullable=true)
     * @Gedmo\Versioned
     */
    private $sundayOpenTime;

    /**
     * @var \DateTime
     * @ORM\Column(name="sunday_close_time", type="time", nullable=true)
     * @Gedmo\Versioned
     */
    private $sundayCloseTime;

    /**
     * @var bool
     * @ORM\Column(name="is_sunday_open_24_hours", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $isSundayOpen24Hours;

    /**
     * @var boolean
     * @ORM\Column(name="is_sunday_closed", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $isSundayClosed;

    /**
     * @var Supplier
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Supplier", inversedBy="locations")
     * @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
     */
    private $supplier;

    public function __construct()
    {
        $this->mondayOpenTime = (new \DateTime())->setTime(8,0,0);
        $this->mondayCloseTime = (new \DateTime())->setTime(20,0,0);
        $this->tuesdayOpenTime = (new \DateTime())->setTime(8,0,0);
        $this->tuesdayCloseTime = (new \DateTime())->setTime(20,0,0);
        $this->wednesdayOpenTime = (new \DateTime())->setTime(8,0,0);
        $this->wednesdayCloseTime = (new \DateTime())->setTime(20,0,0);
        $this->thursdayOpenTime = (new \DateTime())->setTime(8,0,0);
        $this->thursdayCloseTime = (new \DateTime())->setTime(20,0,0);
        $this->fridayOpenTime = (new \DateTime())->setTime(8,0,0);
        $this->fridayCloseTime = (new \DateTime())->setTime(20,0,0);
        $this->saturdayOpenTime = (new \DateTime())->setTime(8,0,0);
        $this->saturdayCloseTime = (new \DateTime())->setTime(20,0,0);
        $this->sundayOpenTime = (new \DateTime())->setTime(8,0,0);
        $this->sundayCloseTime = (new \DateTime())->setTime(20,0,0);
    }

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
     * Set address
     *
     * @param Place $address
     *
     * @return Location
     */
    public function setAddress($address)
    {
        $this->address = $address;
        $this->fullAddress = $address->getFullName();
        return $this;
    }

    /**
     * Get address
     *
     * @return Place
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set yearsOpen
     *
     * @param string $yearsOpen
     *
     * @return Location
     */
    public function setYearsOpen($yearsOpen)
    {
        $this->yearsOpen = $yearsOpen;

        return $this;
    }

    /**
     * Get yearsOpen
     *
     * @return string
     */
    public function getYearsOpen()
    {
        return $this->yearsOpen;
    }

    /**
     * Set totalSeats
     *
     * @param integer $totalSeats
     *
     * @return Location
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
     * Set availableSeats
     *
     * @param integer $availableSeats
     *
     * @return Location
     */
    public function setAvailableSeats($availableSeats)
    {
        $this->availableSeats = $availableSeats;

        return $this;
    }

    /**
     * Get availableSeats
     *
     * @return int
     */
    public function getAvailableSeats()
    {
        return $this->availableSeats;
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
     * @return Customer[]
     */
    public function getCustomers()
    {
        return $this->customers;
    }

    /**
     * @param Customer[] $customers
     *
     * @return Location
     */
    public function setCustomers($customers)
    {
        $this->customers = $customers;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toString()
    {
        return Profile::PROFILE_LOCATION;
    }

    /**
     * @return string
     */
    public function getFullAddress()
    {
        if ($this->address->getName()) {
            return $this->fullAddress;
        }

        return null;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setFullAddress()
    {
        if ($this->address) {
            $this->fullAddress = $this->address->getFullName();
        }
    }

    /**
     * @return \DateTime
     */
    public function getMondayOpenTime()
    {
        return $this->mondayOpenTime;
    }

    /**
     * @param \DateTime $mondayOpenTime
     */
    public function setMondayOpenTime($mondayOpenTime)
    {
        $this->mondayOpenTime = $mondayOpenTime;
    }

    /**
     * @return \DateTime
     */
    public function getMondayCloseTime()
    {
        return $this->mondayCloseTime;
    }

    /**
     * @param \DateTime $mondayCloseTime
     */
    public function setMondayCloseTime($mondayCloseTime)
    {
        $this->mondayCloseTime = $mondayCloseTime;
    }

    /**
     * @return bool
     */
    public function isMondayOpen24Hours()
    {
        return $this->isMondayOpen24Hours;
    }

    /**
     * @param bool $isMondayOpen24Hours
     */
    public function setIsMondayOpen24Hours($isMondayOpen24Hours)
    {
        $this->isMondayOpen24Hours = $isMondayOpen24Hours;
    }

    /**
     * @return \DateTime
     */
    public function getTuesdayOpenTime()
    {
        return $this->tuesdayOpenTime;
    }

    /**
     * @param \DateTime $tuesdayOpenTime
     */
    public function setTuesdayOpenTime($tuesdayOpenTime)
    {
        $this->tuesdayOpenTime = $tuesdayOpenTime;
    }

    /**
     * @return \DateTime
     */
    public function getTuesdayCloseTime()
    {
        return $this->tuesdayCloseTime;
    }

    /**
     * @param \DateTime $tuesdayCloseTime
     */
    public function setTuesdayCloseTime($tuesdayCloseTime)
    {
        $this->tuesdayCloseTime = $tuesdayCloseTime;
    }

    /**
     * @return bool
     */
    public function isTuesdayOpen24Hours()
    {
        return $this->isTuesdayOpen24Hours;
    }

    /**
     * @param bool $isTuesdayOpen24Hours
     */
    public function setIsTuesdayOpen24Hours($isTuesdayOpen24Hours)
    {
        $this->isTuesdayOpen24Hours = $isTuesdayOpen24Hours;
    }

    /**
     * @return \DateTime
     */
    public function getWednesdayOpenTime()
    {
        return $this->wednesdayOpenTime;
    }

    /**
     * @param \DateTime $wednesdayOpenTime
     */
    public function setWednesdayOpenTime($wednesdayOpenTime)
    {
        $this->wednesdayOpenTime = $wednesdayOpenTime;
    }

    /**
     * @return \DateTime
     */
    public function getWednesdayCloseTime()
    {
        return $this->wednesdayCloseTime;
    }

    /**
     * @param \DateTime $wednesdayCloseTime
     */
    public function setWednesdayCloseTime($wednesdayCloseTime)
    {
        $this->wednesdayCloseTime = $wednesdayCloseTime;
    }

    /**
     * @return bool
     */
    public function isWednesdayOpen24Hours()
    {
        return $this->isWednesdayOpen24Hours;
    }

    /**
     * @param bool $isWednesdayOpen24Hours
     */
    public function setIsWednesdayOpen24Hours($isWednesdayOpen24Hours)
    {
        $this->isWednesdayOpen24Hours = $isWednesdayOpen24Hours;
    }

    /**
     * @return \DateTime
     */
    public function getThursdayOpenTime()
    {
        return $this->thursdayOpenTime;
    }

    /**
     * @param \DateTime $thursdayOpenTime
     */
    public function setThursdayOpenTime($thursdayOpenTime)
    {
        $this->thursdayOpenTime = $thursdayOpenTime;
    }

    /**
     * @return \DateTime
     */
    public function getThursdayCloseTime()
    {
        return $this->thursdayCloseTime;
    }

    /**
     * @param \DateTime $thursdayCloseTime
     */
    public function setThursdayCloseTime($thursdayCloseTime)
    {
        $this->thursdayCloseTime = $thursdayCloseTime;
    }

    /**
     * @return bool
     */
    public function isThursdayOpen24Hours()
    {
        return $this->isThursdayOpen24Hours;
    }

    /**
     * @param bool $isThursdayOpen24Hours
     */
    public function setIsThursdayOpen24Hours($isThursdayOpen24Hours)
    {
        $this->isThursdayOpen24Hours = $isThursdayOpen24Hours;
    }

    /**
     * @return \DateTime
     */
    public function getFridayOpenTime()
    {
        return $this->fridayOpenTime;
    }

    /**
     * @param \DateTime $fridayOpenTime
     */
    public function setFridayOpenTime($fridayOpenTime)
    {
        $this->fridayOpenTime = $fridayOpenTime;
    }

    /**
     * @return \DateTime
     */
    public function getFridayCloseTime()
    {
        return $this->fridayCloseTime;
    }

    /**
     * @param \DateTime $fridayCloseTime
     */
    public function setFridayCloseTime($fridayCloseTime)
    {
        $this->fridayCloseTime = $fridayCloseTime;
    }

    /**
     * @return bool
     */
    public function isFridayOpen24Hours()
    {
        return $this->isFridayOpen24Hours;
    }

    /**
     * @param bool $isFridayOpen24Hours
     */
    public function setIsFridayOpen24Hours($isFridayOpen24Hours)
    {
        $this->isFridayOpen24Hours = $isFridayOpen24Hours;
    }

    /**
     * @return \DateTime
     */
    public function getSaturdayOpenTime()
    {
        return $this->saturdayOpenTime;
    }

    /**
     * @param \DateTime $saturdayOpenTime
     */
    public function setSaturdayOpenTime($saturdayOpenTime)
    {
        $this->saturdayOpenTime = $saturdayOpenTime;
    }

    /**
     * @return \DateTime
     */
    public function getSaturdayCloseTime()
    {
        return $this->saturdayCloseTime;
    }

    /**
     * @param \DateTime $saturdayCloseTime
     */
    public function setSaturdayCloseTime($saturdayCloseTime)
    {
        $this->saturdayCloseTime = $saturdayCloseTime;
    }

    /**
     * @return bool
     */
    public function isSaturdayOpen24Hours()
    {
        return $this->isSaturdayOpen24Hours;
    }

    /**
     * @param bool $isSaturdayOpen24Hours
     */
    public function setIsSaturdayOpen24Hours($isSaturdayOpen24Hours)
    {
        $this->isSaturdayOpen24Hours = $isSaturdayOpen24Hours;
    }

    /**
     * @return \DateTime
     */
    public function getSundayOpenTime()
    {
        return $this->sundayOpenTime;
    }

    /**
     * @param \DateTime $sundayOpenTime
     */
    public function setSundayOpenTime($sundayOpenTime)
    {
        $this->sundayOpenTime = $sundayOpenTime;
    }

    /**
     * @return \DateTime
     */
    public function getSundayCloseTime()
    {
        return $this->sundayCloseTime;
    }

    /**
     * @param \DateTime $sundayCloseTime
     */
    public function setSundayCloseTime($sundayCloseTime)
    {
        $this->sundayCloseTime = $sundayCloseTime;
    }

    /**
     * @return bool
     */
    public function isSundayOpen24Hours()
    {
        return $this->isSundayOpen24Hours;
    }

    /**
     * @param bool $isSundayOpen24Hours
     */
    public function setIsSundayOpen24Hours($isSundayOpen24Hours)
    {
        $this->isSundayOpen24Hours = $isSundayOpen24Hours;
    }

    /**
     * @return string
     */
    public function getOperateFrom()
    {
        return $this->operateFrom;
    }

    /**
     * @param string $operateFrom
     */
    public function setOperateFrom($operateFrom)
    {
        $this->operateFrom = $operateFrom;
    }

    /**
     * @return string
     */
    public function getConductIn()
    {
        return $this->conductIn;
    }

    /**
     * @param string $conductIn
     */
    public function setConductIn($conductIn)
    {
        $this->conductIn = $conductIn;
    }

    /**
     * @return bool
     */
    public function isNoiseCancelling()
    {
        return $this->noiseCancelling;
    }

    /**
     * @param bool $noiseCancelling
     */
    public function setNoiseCancelling($noiseCancelling)
    {
        $this->noiseCancelling = $noiseCancelling;
    }

    /**
     * @return bool
     */
    public function isMondayClosed()
    {
        return $this->isMondayClosed;
    }

    /**
     * @param bool $isMondayClosed
     */
    public function setIsMondayClosed($isMondayClosed)
    {
        $this->isMondayClosed = $isMondayClosed;
    }

    /**
     * @return bool
     */
    public function isTuesdayClosed()
    {
        return $this->isTuesdayClosed;
    }

    /**
     * @param bool $isTuesdayClosed
     */
    public function setIsTuesdayClosed($isTuesdayClosed)
    {
        $this->isTuesdayClosed = $isTuesdayClosed;
    }

    /**
     * @return bool
     */
    public function isWednesdayClosed()
    {
        return $this->isWednesdayClosed;
    }

    /**
     * @param bool $isWednesdayClosed
     */
    public function setIsWednesdayClosed($isWednesdayClosed)
    {
        $this->isWednesdayClosed = $isWednesdayClosed;
    }

    /**
     * @return bool
     */
    public function isThursdayClosed()
    {
        return $this->isThursdayClosed;
    }

    /**
     * @param bool $isThursdayClosed
     */
    public function setIsThursdayClosed($isThursdayClosed)
    {
        $this->isThursdayClosed = $isThursdayClosed;
    }

    /**
     * @return bool
     */
    public function isFridayClosed()
    {
        return $this->isFridayClosed;
    }

    /**
     * @param bool $isFridayClosed
     */
    public function setIsFridayClosed($isFridayClosed)
    {
        $this->isFridayClosed = $isFridayClosed;
    }

    /**
     * @return bool
     */
    public function isSaturdayClosed()
    {
        return $this->isSaturdayClosed;
    }

    /**
     * @param bool $isSaturdayClosed
     */
    public function setIsSaturdayClosed($isSaturdayClosed)
    {
        $this->isSaturdayClosed = $isSaturdayClosed;
    }

    /**
     * @return bool
     */
    public function isSundayClosed()
    {
        return $this->isSundayClosed;
    }

    /**
     * @param bool $isSundayClosed
     */
    public function setIsSundayClosed($isSundayClosed)
    {
        $this->isSundayClosed = $isSundayClosed;
    }

}

