<?php
/**
 * NavbarController.php
 * avanzu-admin
 * Date: 23.02.14
 */

namespace ThemeBundle\Controller;


use ThemeBundle\Event\MessageListEvent;
use ThemeBundle\Event\NotificationListEvent;
use ThemeBundle\Event\ShowUserEvent;
use ThemeBundle\Event\TaskListEvent;
use ThemeBundle\Event\ThemeEvents;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Response;

class NavbarController extends Controller
{

    /**
     * @return EventDispatcher
     */
    protected function getDispatcher()
    {
        return $this->get('event_dispatcher');
    }


    public function notificationsAction($max = 5)
    {

        if (!$this->getDispatcher()->hasListeners(ThemeEvents::THEME_NOTIFICATIONS)) {
            return new Response();
        }

        $listEvent = $this->getDispatcher()->dispatch(ThemeEvents::THEME_NOTIFICATIONS, new NotificationListEvent());

        return $this->render(
                    'ThemeBundle:Navbar:notifications.html.twig',
                        array(
                            'notifications' => $listEvent->getNotifications(),
                            'total'         => $listEvent->getTotal()
                        )
        );

    }

    public function messagesAction($max = 5)
    {

        if (!$this->getDispatcher()->hasListeners(ThemeEvents::THEME_MESSAGES)) {
            return new Response();
        }

        $listEvent = $this->getDispatcher()->dispatch(ThemeEvents::THEME_MESSAGES, new MessageListEvent());

        return $this->render(
                    'ThemeBundle:Navbar:messages.html.twig',
                        array(
                            'messages' => $listEvent->getMessages(),
                            'total'    => $listEvent->getTotal()
                        )
        );
    }

    public function tasksAction($max = 5)
    {

        if (!$this->getDispatcher()->hasListeners(ThemeEvents::THEME_TASKS)) {
            return new Response();
        }
        $listEvent = $this->getDispatcher()->dispatch(ThemeEvents::THEME_TASKS, new TaskListEvent());

        return $this->render(
                    'ThemeBundle:Navbar:tasks.html.twig',
                        array(
                            'tasks' => $listEvent->getTasks(),
                            'total' => $listEvent->getTotal()
                        )
        );
    }

    public function userAction()
    {

        if (!$this->getDispatcher()->hasListeners(ThemeEvents::THEME_NAVBAR_USER)) {
            return new Response();
        }
        $userEvent = $this->getDispatcher()->dispatch(ThemeEvents::THEME_NAVBAR_USER, new ShowUserEvent());

        return $this->render(
                    'ThemeBundle:Navbar:user.html.twig',
                        array(
                            'user' => $userEvent->getUser()
                        )
        );
    }

}