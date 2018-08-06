<?php
/**
 * Created by PhpStorm.
 * User: Lixing
 * Date: 7/6/18
 * Time: 11:57 PM
 */

namespace AppBundle\Form;


use AppBundle\Entity\CommissionModel;

class CommissionModelData
{

    /**
     * @var CommissionModel
     */
    private $commissionModel;

    private $name;

    private $isFlatRate;

    private $file;

    private $flatRate;

    public function __construct(CommissionModel $commissionModel)
    {
        $this->commissionModel = $commissionModel;
        $this->name = $commissionModel->getName();
        $this->isFlatRate = $commissionModel->isFlatRate();
        $this->flatRate = $commissionModel->getFlatRate();
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function isFlatRate()
    {
        return $this->isFlatRate;
    }

    /**
     * @param mixed $isFlatRate
     */
    public function setIsFlatRate($isFlatRate)
    {
        $this->isFlatRate = $isFlatRate;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getFlatRate()
    {
        return $this->flatRate;
    }

    /**
     * @param mixed $flatRate
     */
    public function setFlatRate($flatRate)
    {
        $this->flatRate = $flatRate;
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