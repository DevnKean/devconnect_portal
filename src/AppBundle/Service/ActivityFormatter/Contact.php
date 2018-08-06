<?php
namespace AppBundle\Service\ActivityFormatter;

use AppBundle\Entity\LogEntryInterface;
use AppBundle\Service\ActivityLog\EntityFormatter\AbstractFormatter;
use AppBundle\Service\ActivityLog\EntityFormatter\FormatterInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Created by PhpStorm.
 * User: Lixing
 * Date: 14/11/17
 * Time: 4:57 PM
 */

class Contact extends AbstractFormatter implements FormatterInterface
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

        $contact = $this->getContact($log->getObjectId());

        if ($contact) {
            $type = $contact->getType();
        } else {
            $type = '';
        }

        if ($log->isCreate()) {
            $result['message'] = sprintf('The <strong><span class="font-green-jungle">"%s"</span></strong> contact profile was created.', $type);
            $result['message'] .= '<br /><br />';

            $data = $log->getData();
            $text = '';

            foreach ($data as $field => $value) {
                $field = $this->fromCamelCase($field);
                $text .= sprintf('<dd>Field "<strong>%s</strong>" was created: %s</dd>', $field, $value);
            }
            $result['message'] .= $text;

        } else if ($log->isRemove()) {
            $result['message'] = sprintf('The <strong><span class="font-green-jungle">"%s"</span></strong> was removed.', $type);
        } else if ($log->isUpdate()) {
            $result['message'] = '<dl><dt>The <strong><span class="font-yellow-gold">"%s"</span></strong> has been updated.</dt>%s</dl>';
            $data = $log->getData();
            $oldData = $log->getOldData();

            $text = '';
            foreach ($data as $field => $value) {

                if (array_key_exists($field, $oldData)) {
                    $oldValue = $this->normalizeValue($field, $oldData[$field]);
                    $subText = sprintf('from "<b>%s</b>" to "<b>%s</b>".', $oldValue, $value);
                } else {
                    $subText = sprintf('to "<b>%s</b>".', $value);
                }
                $field = $this->fromCamelCase($field);
                $text .= sprintf('<dd>Field "<b>%s</b>" was changed: %s</dd>', $field, $subText);
            }

            $result['message'] = sprintf($result['message'], $type, $text);
        } else {
            $result['message'] = "Undefined action: {$log->getAction()}.";
        }

        return $result;
    }

    protected function getContact($id)
    {
        /** @var EntityManager $manger */
        $contact = $this->manager->getRepository('AppBundle:Contact')->find($id);

        if ($contact) {
            return $contact;
        }

        return null;
    }
}