<?php
namespace ThemeBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use ThemeBundle\Event\ShowUserEvent;
use AppBundle\Entity\User;

class ShowUserListener {

    /**
     * @var User
     */
    private $user;

    public function __construct(ContainerInterface $container)
    {
        $this->user = $container->get('security.token_storage')->getToken()->getUser();
    }

    public function onShowUser(ShowUserEvent $event) {
        $event->setUser($this->user);
    }

}