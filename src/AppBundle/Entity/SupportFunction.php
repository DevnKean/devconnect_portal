<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\StringableInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * SupportFunction
 *
 * @ORM\Table(name="support_functions")
 * @Gedmo\Loggable(logEntryClass="AppBundle\Entity\LogEntry")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SupportFunctionRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class SupportFunction implements StringableInterface
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
     * @ORM\Column(name="function", type="string", length=100)
     * @Gedmo\Versioned
     */
    private $function;

    /**
     * @var string
     *
     * @ORM\Column(name="point", type="decimal", precision=10, scale=2)
     * @Gedmo\Versioned
     */
    private $point;

    /**
     * @var Supplier
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Supplier", inversedBy="supportFunctions")
     * @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
     */
    private $supplier;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

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
     * Set function
     *
     * @param string $function
     *
     * @return SupportFunction
     */
    public function setFunction($function)
    {
        $this->function = $function;

        return $this;
    }

    /**
     * Get function
     *
     * @return string
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * Set point
     *
     * @param string $point
     *
     * @return SupportFunction
     */
    public function setPoint($point)
    {
        $this->point = $point;

        return $this;
    }

    /**
     * Get point
     *
     * @return string
     */
    public function getPoint()
    {
        return $this->point;
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

    public static function getFunctions()
    {
        return [
            'Executives',
            'Senior Management',
            'Account Management',
            'Team Leaders',
            'Workforce Management',
            'Learning & Development',
            'IT',
            'Quality Assurance',
            'Dialler Administration',
            'Reporting',
            'Average number of staff per Team Leader'
        ];
    }

    /**
     * @inheritDoc
     */
    public function toString()
    {
        return Profile::PROFILE_SUPPORT_FUNCTION;
    }

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param mixed $deletedAt
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }

}

