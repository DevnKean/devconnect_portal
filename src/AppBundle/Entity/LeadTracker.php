<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * LeadTracker
 *
 * @ORM\Table(name="lead_tracker")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LeadTrackerRepository")
 */
class LeadTracker
{
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Lead
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Lead", inversedBy="leadTrackers")
     * @ORM\JoinColumn(name="lead_id", referencedColumnName="id")
     */
    private $lead;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=50)
     */
    private $status;

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
     * Set status
     *
     * @param string $status
     *
     * @return LeadTracker
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return Lead
     */
    public function getLead()
    {
        return $this->lead;
    }

    /**
     * @param Lead $lead
     */
    public function setLead($lead)
    {
        $this->lead = $lead;
    }

}

