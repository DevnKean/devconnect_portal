<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class ContactController
 *
 * @package AppBundle\Controller
 * @Route("/contacts")
 */
class ContactController extends Controller
{
    /**
     * @Route("/create")
     */
    public function createAction()
    {
        return $this->render('AppBundle:Contact:create.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("")
     */
    public function allAction()
    {
        return $this->render('AppBundle:Contact:all.html.twig', array(
            // ...
        ));
    }

}
