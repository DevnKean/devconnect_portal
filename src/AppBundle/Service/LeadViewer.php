<?php
/**
 * Created by PhpStorm.
 * User: Lixing
 * Date: 30/11/17
 * Time: 11:11 PM
 */

namespace AppBundle\Service;

use AppBundle\Entity\Lead;

class LeadViewer extends LeadManager
{

    /**
     * LeadSync constructor.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager|Object $manager
     * @param             Lead                                  $lead
     */
    public function __construct($manager, Lead $lead)
    {
        parent::__construct($manager);
        $this->lead = $lead;
    }

    public function process()
    {
       return $this->parseData();
    }
}