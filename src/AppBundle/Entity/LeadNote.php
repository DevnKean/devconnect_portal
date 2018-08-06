<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\ActivityItem;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * LeadNote
 *
 * @ORM\Table(name="lead_notes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LeadNoteRepository")
 */
class LeadNote implements ActivityItem
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
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Lead", inversedBy="leadNotes")
     * @ORM\JoinColumn(name="lead_id", referencedColumnName="id")
     */
    private $lead;

    /**
     * @var Supplier
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Supplier", inversedBy="leadNotes")
     * @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
     */
    private $supplier;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text")
     */
    private $note;

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
     * Set lead
     *
     * @param Lead $lead
     *
     * @return LeadNote
     */
    public function setLead($lead)
    {
        $this->lead = $lead;

        return $this;
    }

    /**
     * Get lead
     *
     * @return Lead
     */
    public function getLead()
    {
        return $this->lead;
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
     * Set message
     *
     * @param string $note
     *
     * @return LeadNote
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    public static function getNoteStatuses()
    {
        return [
            'Need to action',
            'Scheduled to contact',
            'Spoke to client, preparing proposal',
            'Spoke to client, not proceeding',
            'Unable to contact client',
            'Sent proposal',
            'Negotiating with client',
            'Lost (advised by client)',
            'Success',
        ];
    }

    public static function getArchivedStatus()
    {
        return 'Archived';
    }

    public static function getInitialStatus()
    {
        return 'Need to action';
    }

    public static function getActiveStatuses()
    {
        return [
            'Scheduled to contact',
            'Spoke to client, preparing proposal',
            'Spoke to client, not proceeding',
            'Unable to contact client',
            'Sent proposal',
            'Negotiating with client',
            'Success'
        ];
    }

    public static function getArchiveStatus()
    {
        return 'Lost (advised by client)';
    }

    public function getActivityType()
    {
        return 'Lead Note';
    }

    public function getDetails()
    {
        return $this->note;
    }

    public function getDate()
    {
        return $this->createdAt;
    }
}

