<?php
/**
 * Created by PhpStorm.
 * User: Lixing
 * Date: 12/11/17
 * Time: 8:13 PM
 */

namespace AppBundle\Entity;

/**
 * Class ActivityLog
 *
 * @package AppBundle\Entity
 */
class ActivityLog
{

    /**
     * @var array
     */
    private $activityItems = [];

    /**
     * @var Supplier
     */
    private $supplier;

    public function __construct($supplier)
    {
        $this->supplier = $supplier;
        $this->populateLeadNotes();
        $this->populateAccountNotes();

        krsort($this->activityItems);
    }

    protected function populateLeadNotes()
    {
        foreach ($this->supplier->getLeadNotes() as $leadNote)
        {
            $this->activityItems[$leadNote->getDate()->format('d M Y')][] = $leadNote;
        }
    }

    protected function populateAccountNotes()
    {
        foreach ($this->supplier->getAccountNotes() as $accountNote)
        {
            $this->activityItems[$accountNote->getDate()->format('d M Y')][] = $accountNote;
        }
    }

    /**
     * @return array
     */
    public function getActivityItems()
    {
        return $this->activityItems;
    }

    /**
     * @param array $activityItems
     */
    public function setActivityItems($activityItems)
    {
        $this->activityItems = $activityItems;
    }

    /**
     * @return mixed
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * @param mixed $supplier
     */
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;
    }

}