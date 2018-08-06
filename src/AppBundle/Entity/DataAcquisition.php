<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\StringableInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * DataAcquisition
 *
 * @ORM\Table(name="data_acquisition")
 * @Gedmo\Loggable(logEntryClass="AppBundle\Entity\LogEntry")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DataAcquisitionRepository")
 */
class DataAcquisition implements StringableInterface
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
     * @ORM\Column(name="experience", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $experience;

    /**
     * @var Supplier
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Supplier", inversedBy="dataAcquisition")
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
     * Set experience
     *
     * @param string $experience
     *
     * @return DataAcquisition
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;

        return $this;
    }

    /**
     * Get experience
     *
     * @return string
     */
    public function getExperience()
    {
        return $this->experience;
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
     * @param Supplier $supplier
     *
     * @return array
     */
    public static function getExperiences(Supplier $supplier)
    {
        if ($supplier->isOutSourcing()) {
            return [
                'We are experts at it',
                'We do it regularly',
                'We do it on occasions',
                'We could do it if we really had to',
                'We have very limited experience',
                'Its not something we can do quickly',
                'We are not able to source customer data for outbound campaigns',
            ];
        }

        if ($supplier->isVirtualAssistant()) {
            return [
                'I\'m an expert at it',
                'I do it regularly',
                'I do it on occasions',
                'I could do it if I really had to',
                'I have very limited experience',
                'It\'s not something I can organise quickly',
                'I am not able to provide data',
            ];
        }

        return [];
    }

    public function requireProviders()
    {
        return in_array($this->experience, [
            'I\'m an expert at it',
            'I do it regularly',
            'I do it on occasions',
            'I could do it if I really had to',
        ]);
    }

    /**
     * @inheritDoc
     */
    public function toString()
    {
        return Profile::PROFILE_DATA_ACQUISITION;
    }

}

