<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\StringableInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * LocationTimetable
 *
 * @ORM\Table(name="location_timetable")
 * @Gedmo\Loggable(logEntryClass="AppBundle\Entity\LogEntry")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LocationTimetableRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class LocationTimetable implements StringableInterface
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
     * @var Location
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Location", inversedBy="locationTimetables")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     */
    private $location;

    /**
     * @var string
     *
     * @ORM\Column(name="open_day", type="string", length=20)
     * @Gedmo\Versioned
     */
    private $openDay;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="open_time", type="time", nullable=true)
     * @Gedmo\Versioned
     */
    private $openTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="close_time", type="time", nullable=true)
     * @Gedmo\Versioned
     */
    private $closeTime;

    /**
     * @var \DateTime
     * @ORM\Column(name="is_open_whole_day", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $isOpenWholeDay;

    /**
     * @var string
     * @ORM\Column(name="address", type="string")
     * @Gedmo\Versioned
     */
    private $address;


    public function __construct()
    {
        $this->openTime = (new \DateTime())->setTime(8,0,0);
        $this->closeTime = (new \DateTime())->setTime(20,0,0);
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
     * Set location
     *
     * @param Location $location
     *
     * @return LocationTimetable
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set businessDay
     *
     * @param string $openDay
     *
     * @return LocationTimetable
     */
    public function setOpenDay($openDay)
    {
        $this->openDay = $openDay;

        return $this;
    }

    /**
     * Get businessDay
     *
     * @return string
     */
    public function getOpenDay()
    {
        return $this->openDay;
    }

    /**
     * Set openTime
     *
     * @param \DateTime $openTime
     *
     * @return LocationTimetable
     */
    public function setOpenTime($openTime)
    {
        $this->openTime = $openTime;

        return $this;
    }

    /**
     * Get openTime
     *
     * @return \DateTime
     */
    public function getOpenTime()
    {
        return $this->openTime;
    }

    /**
     * Set closeTime
     *
     * @param \DateTime $closeTime
     *
     * @return LocationTimetable
     */
    public function setCloseTime($closeTime)
    {
        $this->closeTime = $closeTime;

        return $this;
    }

    /**
     * Get closeTime
     *
     * @return \DateTime
     */
    public function getCloseTime()
    {
        return $this->closeTime;
    }

    public static function getBusinessDays()
    {
        return [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday',
        ];
    }

    /**
     * @return \DateTime
     */
    public function getIsOpenWholeDay()
    {
        return $this->isOpenWholeDay;
    }

    /**
     * @param \DateTime $isOpenWholeDay
     */
    public function setIsOpenWholeDay($isOpenWholeDay)
    {
        $this->isOpenWholeDay = $isOpenWholeDay;
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
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setAddress()
    {
        $this->address = $this->location->getFullAddress();
    }
}

