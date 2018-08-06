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

class Experience extends AbstractFormatter implements FormatterInterface
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

        $experience = $this->getExperience($log->getObjectId());

        if ($log->isCreate()) {
            $data = $log->getData();
            $result['message'] = sprintf('<dd>"<strong>%s</strong>" was created. Years of Experience: %s, Self Rating: %s</dd>', $data['function'], $data['yearsExperience'], $data['selfRating']);
        } else if ($log->isRemove()) {
            $result['message'] = sprintf('The <strong><span class="font-green-jungle">"%s"</span></strong> was removed.', $experience->getFunction());
        } else if ($log->isUpdate()) {
            $result['message'] = '<dl>%s</dl>';
            $data = $log->getData();
            $oldData = $log->getOldData();

            $text = '';
            foreach ($data as $field => $value) {
                $value = $this->normalizeValue($field, $value);
                if (empty($value)) {
                    $value = 'No Selection';
                }
                if (array_key_exists($field, $oldData)) {
                    $oldValue = $this->normalizeValue($field, $oldData[$field]);

                    $subText = sprintf('from "<b>%s</b>" to "<b>%s</b>".', $oldValue, $value);
                } else {
                    $subText = sprintf('to "<b>%s</b>".', $value);
                }

                $text .= sprintf('<dd>The field for "<b>%s</b>" has been changed: %s</dd>', $experience->getFunction(), $subText);
            }

            $result['message'] = sprintf($result['message'], $text);
        } else {
            $result['message'] = "Undefined action: {$log->getAction()}.";
        }

        return $result;
    }

    protected function getExperience($id)
    {
        /** @var EntityManager $manger */
        $experience = $this->manager->getRepository('AppBundle:Experience')->find($id);

        if ($experience) {
            return $experience;
        }

        return null;
    }

}