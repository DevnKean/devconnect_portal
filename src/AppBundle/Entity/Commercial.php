<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Interfaces\StringableInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Commercial
 *
 * @ORM\Table(name="commercial")
 * @Gedmo\Loggable(logEntryClass="AppBundle\Entity\LogEntry")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommercialRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Commercial implements StringableInterface
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
     * @ORM\Column(name="models", type="json_array")
     * @Gedmo\Versioned
     */
    private $models;

    /**
     * @var Supplier
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Supplier", inversedBy="commercial")
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
     * Set model
     *
     * @param string $models
     *
     * @return Commercial
     */
    public function setModels($models)
    {
        $this->models = $models;

        return $this;
    }

    /**
     * Get model
     *
     * @return string
     */
    public function getModels()
    {
        return $this->models;
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
    public static function getCommercialModels(Supplier $supplier)
    {
        if ($supplier->isVirtualAssistant()) {
            return [
                'Hourly Rate',
                'High hourly rate with a small performance incentive',
                'Monthly retainer plus per transaction rate or per minute',
                'Per call/lead/appointment (transaction based)',
                'Per minute (consumption based)',
                'Lower hourly rate with a high performance incentive',
                'Success only (e.g. per lead/appointment/sale)'
            ];
        }
        if ($supplier->isOutSourcing()) {
            return [
                'Hourly Rate',
                'High hourly rate with a small performance incentive',
                'Monthly retainer plus per transaction rate or per minute',
                'Per call/transaction (consumption based)',
                'Per minute (consumption based)',
                'Lower hourly rate with a high performance incentive',
                'Commission Only'
            ];
        }

        return [];
    }

    /**
     * @inheritDoc
     */
    public function toString()
    {
        return Profile::PROFILE_COMMERCIAL;
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

