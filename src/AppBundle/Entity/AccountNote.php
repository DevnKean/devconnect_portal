<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\ActivityItem;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * AccountNote
 *
 * @ORM\Table(name="account_note")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AccountNoteRepository")
 */
class AccountNote implements ActivityItem
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
     * @ORM\Column(name="note", type="text")
     */
    private $note;

    /**
     * @var Supplier
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Supplier", inversedBy="accountNotes")
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
     * Set note
     *
     * @param string $note
     *
     * @return AccountNote
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
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
     *
     * @return AccountNote
     */
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getActivityType()
    {
        return 'Account Note';
    }

    /**
     * @return string
     */
    public function getDetails()
    {
        return $this->note;
    }

}

