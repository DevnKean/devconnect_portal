<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * LeadStatusLog
 *
 * @ORM\Table(name="lead_status_log")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LeadStatusLogRepository")
 */
class LeadStatusLog
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
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=100)
     */
    private $status;

    /**
     * @var Lead
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Lead", inversedBy="leadStatusLogs")
     * @ORM\JoinColumn(name="lead_id", referencedColumnName="id")
     */
    private $lead;

    /**
     * @var Supplier
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Supplier", inversedBy="leadStatusLogs")
     * @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
     */
    private $supplier;

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
     * @return LeadStatusLog
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

}

