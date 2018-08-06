<?php
namespace AppBundle\Service;
/**
 * Created by PhpStorm.
 * User: Lixing
 * Date: 23/9/17
 * Time: 10:14 PM
 */
use AppBundle\Entity\Lead;
use AppBundle\Entity\Form;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;

class LeadProcessor {

    /**
     * @var Lead;
     */
    private $lead;

    /**
     * @var Form
     */
    private $form;

    /**
     * @var string
     */
    private $content;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $manager;

    /**
     * @var Request
     */
    private $request;

    /**
     * LeadProcessor constructor.
     *
     * @param ObjectManager | Object $manager
     */
    public function __construct($manager)
    {
        $this->manager = $manager;
    }


    /**
     * @return Lead
     */
    public function getLead()
    {
        return $this->lead;
    }


    /**
     * @param Lead $lead
     */
    public function setLead($lead)
    {
        $this->lead = $lead;
    }

    /**
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param Form $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }



    public function saveLead()
    {
        $responseArray = json_decode($this->content, true);
        if (empty($this->form)) {
            $this->form = $this->manager->getRepository('AppBundle:Form')->findOneBy(['gravityFormId' => $responseArray['form_id']]);
        }


        if (empty($this->lead)) {
            $this->lead = new Lead();
            $this->lead->setSource($this->request->headers->get('Source'));
        }
        $this->lead->setRawData($this->content);
        $this->lead->setForm($this->form);
        $this->lead->setStatus(Lead::STATUS_PENDING);
        $parsedResponse = $this->parseResponse();
        $this->lead->setBusinessName(current($parsedResponse[Form::ID_BUSINESS_NAME]['answer']));
        $this->lead->setContactName(implode(' ', $parsedResponse[Form::ID_CONTACT_NAME]['answer']));
        $this->lead->setContactEmail(current($parsedResponse[Form::ID_CONTACT_EMAIL]['answer']));
        $this->lead->setContactPhone(current($parsedResponse[Form::ID_CONTACT_PHONE]['answer']));
        $this->lead->setEntryId($responseArray['id']);
        $this->manager->persist($this->lead);
        $this->manager->flush();
    }

    /**
     * @return array
     */
    public function parseResponse()
    {
        $leadData = json_decode($this->lead->getRawData(), true);
        $formData = json_decode($this->lead->getForm()->getRawData());
        $result = [];
        foreach ($leadData as $key => $value) {
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
                        if ($questionId == Form::ID_OUTSOURCER_ATTRIBUTES || $questionId == Form::ID_SURVEY_RANK) {
                            $values = explode(',', $value);
                            foreach ($values as $v) {
                                if ($choice->value === $v && (!isset($result[$questionId]['answer']) || !in_array($choice->text,$result[$questionId]['answer']))) {
                                    $result[$questionId]['answer'][] = $choice->text;
                                }
                            }
                            continue;
                        }
                        if ($choice->value === $value && (!isset($result[$questionId]['answer']) || !in_array($choice->text,$result[$questionId]['answer']))) {
                            $result[$questionId]['answer'][] = $choice->text;
                        }
                    }

                }
            }
            if (!isset($result[$questionId]['answer']) || $questionId == Form::ID_CONTACT_NAME) {
                $result[$questionId]['answer'][] = $value;
            }
        }

        uasort($result, function ($a, $b) {
           return ($a['index'] < $b['index']) ? -1 : 1;
        });

        return $result;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
        $this->content = $request->getContent();
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

}