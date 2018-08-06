<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\StringableInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Experience
 *
 * @ORM\Table(name="experience")
 * @Gedmo\Loggable(logEntryClass="AppBundle\Entity\LogEntry")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ExperienceRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Experience implements StringableInterface
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
     * @ORM\Column(name="function", type="string", length=50)
     * @Gedmo\Versioned
     */
    private $function;

    /**
     * @var string
     *
     * @ORM\Column(name="years_experience", type="string", length=30)
     * @Gedmo\Versioned
     */
    private $yearsExperience;

    /**
     * @var string
     * @ORM\Column(name="self_rating", type="string", length=100, nullable=true)
     * @Gedmo\Versioned
     */
    private $selfRating;

    /**
     * @var Supplier
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Supplier", inversedBy="experiences")
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
     * @return string
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * @param string $function
     *
     * @return Experience
     */
    public function setFunction($function)
    {
        $this->function = $function;

        return $this;
    }

    /**
     * @return string
     */
    public function getYearsExperience()
    {
        return $this->yearsExperience;
    }

    /**
     * @param string $yearsExperience
     *
     * @return Experience
     */
    public function setYearsExperience($yearsExperience)
    {
        $this->yearsExperience = $yearsExperience;

        return $this;
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
     * @return Experience
     */
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;

        return $this;
    }

    public static function getYears()
    {
        return [
            'Nil' => 'Nil',
            'Under 12 months' => 'Under 12 months',
            '1-2 Years' => '1-2 Years',
            '2-3 Years' => '2-3 Years',
            '3-4 Years' => '3-4 Years',
            '4-5 Years' => '4-5 Years',
            '5 to 10 Years' => '5 to 10 Years',
            '10 to 20 Years' => '10 to 20 Years',
            '> 20 Years' => '> 20 Years'
        ];
    }

    /**
     * @inheritDoc
     */
    public function toString()
    {
        return Profile::PROFILE_EXPERIENCE;
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

    /**
     * @return mixed
     */
    public function getSelfRating()
    {
        return $this->selfRating;
    }

    /**
     * @param mixed $selfRating
     */
    public function setSelfRating($selfRating)
    {
        $this->selfRating = $selfRating;
    }

    public static function getSelfRatings()
    {
        return [
            'Exceptional',
            'Above average',
            'I\'m OK but still learning',
            'I\'ve just started and keen to prove I can do it',
            'It\'s not something I\'m interested in doing'
        ];
    }
}

