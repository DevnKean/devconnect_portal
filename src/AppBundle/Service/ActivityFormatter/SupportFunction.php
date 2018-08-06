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

class SupportFunction extends AbstractFormatter implements FormatterInterface
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

        if ($function = $this->getFunction($log->getObjectId())) {
            $fieldName = $function->getFunction();
        } else {
            $fieldName = 'point';
        }

        if ($log->isCreate()) {
            $result['message'] = sprintf('The <strong><span class="font-green-jungle">"%s"</span></strong> was created.', $log->getName());
            $result['message'] .= '<br /><br />';

            $data = $log->getData();
            $result['message'] .= sprintf('<dd><strong>%s</strong>: %s</dd>', $data['function'], number_format($data['point'],1));
        } else if ($log->isRemove()) {
            $result['message'] = sprintf('The <strong><span class="font-green-jungle">"%s"</span></strong> was removed.', $log->getName());
        } else if ($log->isUpdate()) {
            $result['message'] = '<dl><dt>The <strong><span class="font-yellow-gold">"%s"</span></strong> was updated.</dt>%s</dl>';
            $data = $log->getData();
            $oldData = $log->getOldData();

            $text = '';
            foreach ($data as $field => $value) {
                $value = $this->normalizeValue($field, $value);

                if (array_key_exists($field, $oldData)) {
                    $oldValue = $this->normalizeValue($field, $oldData[$field]);
                    $subText = sprintf('from "<b>%s</b>" to "<b>%s</b>".', number_format($oldValue, 1), number_format($value,1));
                } else {
                    $subText = sprintf('to "<b>%s</b>".', $value);
                }
                $text .= sprintf('<dd>Field "<b>%s</b>" was changed: %s</dd>', $fieldName, $subText);
            }

            $result['message'] = sprintf($result['message'], $log->getName(), $text);
        } else {
            $result['message'] = "Undefined action: {$log->getAction()}.";
        }

        return $result;
    }

    protected function getFunction($id)
    {
        /** @var EntityManagerInterface $manger */
        $function = $this->manager->getRepository('AppBundle:SupportFunction')->find($id);

        if ($function) {
            return $function;
        }

        return null;
    }

}