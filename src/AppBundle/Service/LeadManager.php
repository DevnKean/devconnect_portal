<?php
/**
 * Created by PhpStorm.
 * User: Lixing
 * Date: 30/11/17
 * Time: 9:41 PM
 */

namespace AppBundle\Service;

use AppBundle\Entity\Form;
use AppBundle\Entity\Lead;
use Doctrine\Common\Persistence\ObjectManager;

abstract class LeadManager
{
    /**
     * @var Lead;
     */
    protected $lead;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $manager;

    /**
     * LeadProcessor constructor.
     *
     * @param ObjectManager | Object $manager
     */
    public function __construct($manager)
    {
        $this->manager = $manager;
    }

    public function parseData()
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
    
}