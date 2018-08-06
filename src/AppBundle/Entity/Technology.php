<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\StringableInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Technology
 *
 * @ORM\Table(name="technology")
 * @Gedmo\Loggable(logEntryClass="AppBundle\Entity\LogEntry")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TechnologyRepository")
 */
class Technology implements StringableInterface
{
    const TYPE_DEFAULT = 'Default';
    const TYPE_CUSTOM = 'Custom';

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
     * @Assert\NotBlank()
     * @ORM\Column(name="technology", type="string", length=30)
     * @Gedmo\Versioned
     *
     */
    private $technology;

    /**
     * @var string
     *
     * @ORM\Column(name="vendor", type="string", length=30, nullable=true)
     * @Gedmo\Versioned
     */
    private $vendor;

    /**
     * @Assert\NotBlank()
     * @var string
     * @ORM\Column(name="experience_level", length=30)
     * @Gedmo\Versioned
     */
    private $experienceLevel;

    /**
     * @var string
     * @ORM\Column(name="type", length=10)
     * @Gedmo\Versioned
     */
    private $type;

    /**
     * @var Supplier
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Supplier", inversedBy="technologies")
     * @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
     */
    private $supplier;

    /**
     * Technology constructor.
     */
    public function __construct()
    {
        $this->type = self::TYPE_CUSTOM;
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
     * @return string
     */
    public function getTechnology()
    {
        return $this->technology;
    }

    /**
     * @param string $technology
     *
     * @return Technology
     */
    public function setTechnology($technology)
    {
        $this->technology = $technology;

        return $this;
    }

    /**
     * @return string
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * @param string $vendor
     *
     * @return Technology
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;

        return $this;
    }

    /**
     * @return string
     */
    public function getExperienceLevel()
    {
        return $this->experienceLevel;
    }

    /**
     * @param string $experienceLevel
     *
     * @return Technology
     */
    public function setExperienceLevel($experienceLevel)
    {
        $this->experienceLevel = $experienceLevel;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $type
     */
    public function setType($type)
    {
        $this->type = empty($type) ? self::TYPE_CUSTOM : $type;
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
     * @return Technology
     */
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;

        return $this;
    }

    /**
     * @param Supplier $supplier
     *
     * @return array
     */
    public static function getTechnologies(Supplier $supplier)
    {
        if ($supplier->isOutSourcing()) {
            return [
                'Touch Tone IVR',
                'Natural Language IVR',
                'Automatic Dialler',
                'CRM',
                'Call Recording',
                'Screen Recording',
                'Screen Sharing',
                'PCI DSS Compliance',
                'Voice Biometrics',
                'CTI',
                'Live Chat',
                'Web Voice Synchronisation',
                'Multi-Channel Agent Capability',
                'Knowledge Management',
                'CRM Tool',
                'Speech/Voice Analytics',
                'Social Media Management',
                'Social Media Monitoring',
                'Quality Assurance/Monitoring',
                'Business Intelligence',
            ];
        }

        if ($supplier->isVirtualAssistant()) {
            return [
                'Automatic Dialler',
                'CRM',
                'Call Recording',
                'Screen Recording',
                'Screen Sharing',
                'Live Chat',
                'Social Media Management',
                'Social Media Monitoring',
            ];
        }
        return [];
    }

    /**
     * @param Supplier $supplier
     *
     * @return array
     */
    public static function getExperienceLevels(Supplier $supplier)
    {
        if ($supplier->isOutSourcing()) {
            return [
                'Expert' => 'Expert',
                'Proficient' => 'Proficient',
                'Still learning' => 'Still learning',
                'We don\'t have it yet' => 'We don\'t have it yet'
            ];
        }

        if ($supplier->isVirtualAssistant()) {
            return [
                'Expert' => 'Expert',
                'Proficient' => 'Proficient',
                'Still learning' => 'Still learning',
                'I don\'t have it yet' => 'I don\'t have it yet'
            ];
        }

        return [];
    }

    /**
     * @inheritDoc
     */
    public function toString()
    {
        return Profile::PROFILE_TECHNOLOGY;
    }
    
    /**
     * @param ExecutionContextInterface $context
     * @param                           $payload
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->getExperienceLevel() == 'We don\'t have it yet' && empty($this->getVendor())) {
            $context->buildViolation('Vendor Name is required')
                ->atPath('vendor')->addViolation();
        }
    }

}

