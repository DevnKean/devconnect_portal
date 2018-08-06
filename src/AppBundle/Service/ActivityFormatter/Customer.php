<?php
namespace AppBundle\Service\ActivityFormatter;

use AppBundle\Entity\LogEntryInterface;
use AppBundle\Service\ActivityLog\EntityFormatter\AbstractFormatter;
use AppBundle\Service\ActivityLog\EntityFormatter\FormatterInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Created by PhpStorm.
 * User: Lixing
 * Date: 14/11/17
 * Time: 4:57 PM
 */

class Customer extends AbstractFormatter implements FormatterInterface
{

    public function setEntityManger(EntityManagerInterface $manager)
    {
        parent::setEntityManager($manager);
    }


    /**
     * @param LogEntryInterface $log
     * @return array
     */
    public function format(LogEntryInterface $log)
    {
        $result = $log->toArray();


        if ($log->isCreate()) {
            $data = $log->getData();
            $result['message'] = sprintf('A new customer <strong><span class="font-green-jungle">(%s)</span></strong> has been created.', $data['name']);
            $result['message'] .= '<br /><br />';

            $text = '';

            foreach ($data as $field => $value) {
                if (empty($value)) {
                    continue;
                }
                $value = $this->normalizeValue($field, $value, 'H:i');
                $field = $this->fromCamelCase($field);
                $text .= sprintf('<dd>Field "<strong>%s</strong>" was created: %s</dd>', $field, $value);
            }
            $result['message'] .= $text;

        } else if ($log->isRemove()) {
            $result['message'] = sprintf('One of the <strong><span class="font-green-jungle">"%s"</span></strong> was removed.', $log->getName());
            $data = $log->getData();
            $text = '';
            foreach ($data as $field => $value) {
                if (empty($value)) {
                    continue;
                }
                $value = $this->normalizeValue($field, $value, 'd M Y');
                $field = $this->fromCamelCase($field);
                $text .= sprintf('<dd><strong>%s</strong>: %s</dd>', $field, $value);
            }
            $result['message'] .= $text;
        } else if ($log->isUpdate()) {
            $customer = $this->getCustomer($log->getObjectId());
            $result['message'] = '<dl><dt>Details for the customer <strong><span class="font-yellow-gold">"%s"</span></strong> have been updated.</dt>%s</dl>';
            $data = $log->getData();
            $oldData = $log->getOldData();

            $text = '';
            foreach ($data as $field => $value) {
                if (empty($value)) {
                    continue;
                }
                $value = $this->normalizeValue($field, $value, 'H:i');

                if (array_key_exists($field, $oldData)) {
                    $oldValue = $this->normalizeValue($field, $oldData[$field]);
                    $subText = sprintf('from <strong>%s</strong> to <strong>%s</strong>', $oldValue, $value);
                } else {
                    $subText = sprintf('to <strong>%s</strong>', $value);
                }
                $field = $this->fromCamelCase($field);
                $text .= sprintf('<dd>Field "<b>%s</b>" was changed: %s</dd>', $field, $subText);
            }

            $result['message'] = sprintf($result['message'], $customer->getName(), $text);
        } else {
            $result['message'] = "Undefined action: {$log->getAction()}.";
        }

        return $result;
    }

    public function getCustomer($id)
    {
        /** @var EntityManagerInterface $manger */
        $customer = $this->manager->getRepository('AppBundle:Customer')->find($id);

        if ($customer) {
            return $customer;
        }

        return null;
    }
}