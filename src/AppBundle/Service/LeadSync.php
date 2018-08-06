<?php
/**
 * Created by PhpStorm.
 * User: Lixing
 * Date: 30/11/17
 * Time: 11:11 PM
 */

namespace AppBundle\Service;

use AppBundle\Entity\Form;
use AppBundle\Entity\Lead;

class LeadSync extends LeadManager
{

    /**
     * @var GravityClient
     */
    private $client;

    /**
     * LeadSync constructor.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager|Object $manager
     * @param             Lead                                  $lead
     * @param             GravityClient                         $client
     */
    public function __construct($manager, Lead $lead, GravityClient $client)
    {
        parent::__construct($manager);
        $this->lead = $lead;
        $this->client = $client;
    }

    public function process()
    {
        $entry = $this->client->getEntry($this->lead->getEntryId());
        $this->lead->setRawData(json_encode($entry));
        $parsedResponse = $this->parseData();
        $this->lead->setBusinessName(current($parsedResponse[Form::ID_BUSINESS_NAME]['answer']));
        $this->lead->setContactName(implode(' ', $parsedResponse[Form::ID_CONTACT_NAME]['answer']));
        $this->lead->setContactEmail(current($parsedResponse[Form::ID_CONTACT_EMAIL]['answer']));
        $this->lead->setContactPhone(current($parsedResponse[Form::ID_CONTACT_PHONE]['answer']));
        $this->lead->setCampaignLength(current($parsedResponse[Form::ID_CAMPAIGN_LENGTH]['answer']));
        $this->lead->setEstimateType(current($parsedResponse[Form::ID_ESTIMATE_TYPE]['answer']));
        $this->lead->setUniqueID(current($parsedResponse[Form::ID_UNIQUE_ID]['answer']));
        if (isset($parsedResponse[Form::ID_OPTION_HEADCOUNT])) {
            $estimateTypeOption = current($parsedResponse[Form::ID_OPTION_HEADCOUNT]['answer']);
        }else if (isset($parsedResponse[Form::ID_OPTION_INBOUND_VOLUMES])) {
            $estimateTypeOption = current($parsedResponse[Form::ID_OPTION_INBOUND_VOLUMES]['answer']);
        }else if (isset($parsedResponse[Form::ID_OPTION_OUTBOUND_CALL])) {
            $estimateTypeOption = current($parsedResponse[Form::ID_OPTION_OUTBOUND_CALL]['answer']);
        } else {
            $estimateTypeOption = '';
        }
        $this->lead->setEstimateTypeOption($estimateTypeOption);
        $this->manager->persist($this->lead);
        $this->manager->flush();
    }
}