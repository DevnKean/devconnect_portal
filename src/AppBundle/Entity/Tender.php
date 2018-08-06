<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\StringableInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Tender
 *
 * @ORM\Table(name="tender")
 * @Gedmo\Loggable(logEntryClass="AppBundle\Entity\LogEntry")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TenderRepository")
 */
class Tender implements StringableInterface
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
     * @ORM\Column(name="send_opportunities", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $sendOpportunities;

    /**
     * @var Supplier
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Supplier", inversedBy="tender")
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
     * @return string
     */
    public function getSendOpportunities()
    {
        return $this->sendOpportunities;
    }

    /**
     * @param string $sendOpportunities
     */
    public function setSendOpportunities($sendOpportunities)
    {
        $this->sendOpportunities = $sendOpportunities;
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
     * @inheritDoc
     */
    public function toString()
    {
        return Profile::PROFILE_TENDER;
    }

}

