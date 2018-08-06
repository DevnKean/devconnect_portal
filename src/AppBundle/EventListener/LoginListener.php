<?php
/**
 * Created by PhpStorm.
 * User: Lixing
 * Date: 16/9/17
 * Time: 4:22 PM
 */
namespace AppBundle\EventListener;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener {

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var AuthorizationCheckerInterface
     */
    protected $checker;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * LoginListener constructor.
     *
     * @param RouterInterface               $router
     * @param AuthorizationCheckerInterface $checker
     * @param EventDispatcherInterface      $dispatcher
     */
    public function __construct(RouterInterface $router, AuthorizationCheckerInterface $checker, EventDispatcherInterface $dispatcher)
    {
        $this->router = $router;
        $this->checker = $checker;
        $this->dispatcher = $dispatcher;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $this->dispatcher->addListener(KernelEvents::RESPONSE, array($this, 'onKernelResponse'));
    }

    public function onKernelResponse(FilterResponseEvent $event) {
        if ($this->checker->isGranted('ROLE_SUPER_ADMIN')) {
            $response = new RedirectResponse($this->router->generate('admin_dashboard'));
        } else {
            $response = new RedirectResponse($this->router->generate('supplier_dashboard'));
        }

        $event->setResponse($response);
    }

}