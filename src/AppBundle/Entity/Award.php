<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\StringableInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Award
 *
 * @ORM\Table(name="award")
 * @Gedmo\Loggable(logEntryClass="AppBundle\Entity\LogEntry")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AwardRepository")
 */
class Award implements StringableInterface
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
     * @ORM\Column(name="name", type="string", length=20)
     * @Gedmo\Versioned
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="organisation", type="string", length=30)
     * @Gedmo\Versioned
     */
    private $organisation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Gedmo\Versioned
     */
    private $description;

    /**
     * @var Supplier
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Supplier", inversedBy="awards")
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
     * Set name
     *
     * @param string $name
     *
     * @return Award
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
     * Set organisation
     *
     * @param string $organisation
     *
     * @return Award
     */
    public function setOrganisation($organisation)
    {
        $this->organisation = $organisation;

        return $this;
    }

    /**
     * Get organisation
     *
     * @return string
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Award
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Award
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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
     * @return Award
     */
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toString()
    {
        return Profile::PROFILE_AWARD;
    }

}

