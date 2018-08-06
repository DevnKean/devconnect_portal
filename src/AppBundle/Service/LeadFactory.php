<?php
/**
 * Created by PhpStorm.
 * User: Lixing
 * Date: 30/11/17
 * Time: 11:07 PM
 */

namespace AppBundle\Service;


use AppBundle\Entity\Client;
use AppBundle\Entity\Form;
use AppBundle\Entity\Lead;
use AppBundle\Entity\LeadTracker;
use Symfony\Component\HttpFoundation\Request;

class LeadFactory extends LeadManager
{

    /**
     * @var Request
     */
    private $request;

    public function __construct($manager, $request)
    {
        parent::__construct($manager);
        $this->lead = new Lead();
        $this->request = $request;
    }

    public function process()
    {
        $responseArray = json_decode($this->request->getContent(), true);
        $form = $this->manager->getRepository('AppBundle:Form')->findOneBy(['gravityFormId' => $responseArray['form_id'], 'source' => $this->request->headers->get('Source') ]);

        $this->lead->setRawData($this->request->getContent());
        $this->lead->setForm($form);
        $this->lead->setStatus(Lead::STATUS_PENDING);
        $parsedResponse = $this->parseData();
        $client = new Client();
        $client->setCompany(current($parsedResponse[Form::ID_BUSINESS_NAME]['answer']));
        $client->setFirstName(implode(' ', $parsedResponse[Form::ID_CONTACT_NAME]['answer']));
        $client->setEmail(current($parsedResponse[Form::ID_CONTACT_EMAIL]['answer']));
        $client->setPhone(current($parsedResponse[Form::ID_CONTACT_PHONE]['answer']));
//        $this->lead->setBusinessName(current($parsedResponse[Form::ID_BUSINESS_NAME]['answer']));
//        $this->lead->setContactName(implode(' ', $parsedResponse[Form::ID_CONTACT_NAME]['answer']));
//        $this->lead->setContactEmail(current($parsedResponse[Form::ID_CONTACT_EMAIL]['answer']));
//        $this->lead->setContactPhone(current($parsedResponse[Form::ID_CONTACT_PHONE]['answer']));
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
        $this->lead->setEntryId($responseArray['id']);
        $client->addLead($this->lead);
        $this->manager->persist($client);
        $this->manager->persist($this->lead);

        $tracker = new LeadTracker();
        $tracker->setLead($this->lead);
        $tracker->setStatus($this->lead->getStatus());
        $this->manager->persist($tracker);
        $this->manager->flush();
    }
}