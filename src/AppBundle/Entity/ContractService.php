<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ContractService
 *
 * @ORM\Table(name="contracts_services")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContractServiceRepository")
 * @UniqueEntity(
 *     fields={"contract", "service", "commissionModel"},
 *     message="This contract is already allocated with this service",
 *     errorPath="service"
 * )
 */
class ContractService
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
     * @var Contract
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Contract", inversedBy="contractServices")
     * @ORM\JoinColumn(name="contract_id", referencedColumnName="id")
     */
    private $contract;

    /**
     * @var Service
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Service", inversedBy="allocatedContracts")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id")
     */
    private $service;

    /**
     * @var CommissionModel
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CommissionModel", inversedBy="allocatedContractServices")
     * @ORM\JoinColumn(name="commission_id", referencedColumnName="id")
     */
    private $commissionModel;

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
     * @return Contract
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * @param Contract $contract
     */
    public function setContract($contract)
    {
        $this->contract = $contract;
    }

    /**
     * @return Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param Service $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * @return CommissionModel
     */
    public function getCommissionModel()
    {
        return $this->commissionModel;
    }

    /**
     * @param CommissionModel $commissionModel
     */
    public function setCommissionModel($commissionModel)
    {
        $this->commissionModel = $commissionModel;
    }
}

