<?php
/**
 * Created by PhpStorm.
 * User: Lixing
 * Date: 30/11/17
 * Time: 11:07 PM
 */

namespace AppBundle\Service;


use AppBundle\Entity\Form;
use AppBundle\Entity\Lead;
use AppBundle\Entity\LeadTracker;
use AppBundle\Entity\PotentialSupplier;
use AppBundle\Entity\Supplier;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class SupplierFactory
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var Form
     */
    private $form;


    public function __construct($manager, $request)
    {
        $this->request = $request;
        $this->manager = $manager;
    }

    protected function parseData()
    {
        $supplierData = json_decode($this->request->getContent(), true);
        $formData = json_decode($this->form->getRawData());
        $result = [];
        foreach ($supplierData as $key => $value) {
            if (empty($value)) {
                continue;
            }

            if (!preg_match('/^(\d+)\.?(\d+)?$/', $key, $matches)) {
                continue;
            }

            $questionId = $matches[1];

            foreach ($formData->fields as $index => $field) {
                if (!isset($result[$questionId]['question']) && $field->id == $questionId) {
                    $result[$questionId]['question'] = $field->label;
                    $result[$questionId]['index'] = $index;
                }

                if (isset($field->choices) && is_array($field->choices)) {
                    foreach ($field->choices as $choice) {
                        if ($choice->value === $value && (!isset($result[$questionId]['answer']) || !in_array($choice->text,$result[$questionId]['answer']))) {
                            $result[$questionId]['answer'][] = $choice->text;
                        }
                    }
                }

            }

            if (!isset($result[$questionId]['answer']) || $questionId == Form::ID_JOIN_FORM_NAME || $questionId == Form::ID_JOIN_FORM_ADDRESS) {
                $result[$questionId]['answer'][] = $value;
                $result[$questionId]['answer'] = array_unique($result[$questionId]['answer']);
            }
        }

        uasort($result, function ($a, $b) {
            return ($a['index'] < $b['index']) ? -1 : 1;
        });

        return $result;
    }

    public function process()
    {
        $responseArray = json_decode($this->request->getContent(), true);
        $this->form = $this->manager->getRepository('AppBundle:Form')->findOneBy(['gravityFormId' => $responseArray['form_id'], 'source' => $this->request->headers->get('Source') ]);
        $parsedResponse = $this->parseData();
        $potentialSupplier = new PotentialSupplier();
        $potentialSupplier->setFirstName($parsedResponse[Form::ID_JOIN_FORM_NAME]['answer'][0]);
        $potentialSupplier->setLastName($parsedResponse[Form::ID_JOIN_FORM_NAME]['answer'][1]);
        $potentialSupplier->setBusinessName(current($parsedResponse[Form::ID_JOIN_FORM_BUSINESS_NAME]['answer']));
        $potentialSupplier->setAbnNumber(current($parsedResponse[Form::ID_JOIN_FORM_ABN_NUMBER]['answer']));
        $potentialSupplier->setEmail(current($parsedResponse[Form::ID_JOIN_FORM_EMAIL]['answer']));
        $potentialSupplier->setJobTitle(current($parsedResponse[Form::ID_JOIN_FORM_JOB_TITLE]['answer']));
        $potentialSupplier->setAddress(implode(' ', $parsedResponse[Form::ID_JOIN_FORM_ADDRESS]['answer']));
        $potentialSupplier->setContactNumber(current($parsedResponse[Form::ID_JOIN_FORM_CONTACT_NUMBER]['answer']));
        $potentialSupplier->setWebsite(current($parsedResponse[Form::ID_JOIN_FORM_WEBSITE]['answer']));
        $potentialSupplier->setTotalSeats(current($parsedResponse[Form::ID_JOIN_FORM_TOTAL_SEATS]['answer']));
        $potentialSupplier->setLocations($parsedResponse[Form::ID_JOIN_FORM_LOCATIONS]['answer']);
        $potentialSupplier->setYearsOfOperations(current($parsedResponse[Form::ID_JOIN_FORM_YEARS_OF_OPERATIONS]['answer']));
        $potentialSupplier->setBusinessDirectory(current($parsedResponse[Form::ID_JOIN_FORM_BUSINESS_DIRECTORY]['answer']));
        $potentialSupplier->setUniqueID(current($parsedResponse[Form::ID_JOIN_FORM_UNIQUE_ID]['answer']));
        $potentialSupplier->setInitialPassword('cxconnect');
        $potentialSupplier->setUsername($parsedResponse[Form::ID_JOIN_FORM_NAME]['answer'][2].$parsedResponse[Form::ID_JOIN_FORM_NAME]['answer'][1]);
        $potentialSupplier->setStatus(PotentialSupplier::STATUS_POTENTIAL);
        $this->manager->persist($potentialSupplier);
        $this->manager->flush();
    }
}