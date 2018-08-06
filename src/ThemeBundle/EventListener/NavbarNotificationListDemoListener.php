<?php
/**
 * NavbarNotificationListDemoListener.php
 * avanzu-admin
 * Date: 23.02.14
 */

namespace ThemeBundle\EventListener;


use ThemeBundle\Event\NotificationListEvent;
use ThemeBundle\Model\NotificationModel;

class NavbarNotificationListDemoListener {


    public function onListNotifications(NotificationListEvent $event) {

        foreach($this->getNotifications() as $notify){
            $event->addNotification($notify);
        }

    }

    protected function getNotifications() {
        return array(
            new NotificationModel('some notification'),
            new NotificationModel('some more notices', 'success'),
        );
    }

}