<?php
/**
 * Created by PhpStorm.
 * User: Lixing
 * Date: 29/4/18
 * Time: 2:22 PM
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\StringableInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class WorkFromHome
 *
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @Gedmo\Loggable(logEntryClass="AppBundle\Entity\LogEntry")
 * @ORM\Table(name="work_from_home")
 */
class WorkFromHome implements StringableInterface
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
     * @ORM\Column(name="country", type="string")
     * @Gedmo\Versioned
     */
    private $country;

    /**
     * @var int
     * @ORM\Column(name="fte", type="integer")
     * @Gedmo\Versioned
     */
    private $fte;

    /**
     * @var Supplier
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Supplier", inversedBy="workFromHomes")
     * @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
     */
    private $supplier;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @inheritDoc
     */
    public function toString()
    {
        return Profile::PROFILE_WORK_FROM_HOME;
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
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return int
     */
    public function getFte()
    {
        return $this->fte;
    }

    /**
     * @param int $fte
     */
    public function setFte($fte)
    {
        $this->fte = $fte;
    }


    public static function getCountries()
    {
        return [
            'Australia',
            'New Zealand',
            'Philippines',
            'South Africa',
            'Fiji',
            'India',
            'United Kingdom',
            'Malaysia',
            'Thailand',
            'Singapore',
        ];
    }

}