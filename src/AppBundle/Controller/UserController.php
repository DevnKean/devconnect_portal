<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Communication;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController
 *
 * @package AppBundle\Controller
 * @Route("/users")
 */
class UserController extends Controller
{

    /**
     *
     * @Route("/create", name="user_create")
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param \Swift_Mailer                $mailer
     *
     * @return Response
     */
    public function createAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $user->setLoginSentAt(new \DateTime());
            $em->persist($user);
            $em->flush();

            $message = (new \Swift_Message('Welcome to CX Connect'))
                ->setFrom('admin@cxconnect.com.au', 'CX Connect')
                ->setTo($user->getEmail())
                ->setBcc('admin@cxconnect.com.au', 'CX Connect')
                ->setBody(
                    $this->renderView(
                        'emails/registration.html.twig',
                        array('user' => $user)
                    ),
                    'text/html'
                );
            $mailer->send($message);
            $this->addFlash('success', 'User created and login email is sent successfully');

            return $this->redirect($this->generateUrl('user_all'));
        }

        return $this->render(
            'AppBundle:User:create.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @Route("/{id}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $manager = $this->getDoctrine()->getManager();
        $user = $manager->getRepository('AppBundle:User')->find($id);
        if (!$user) {
            throw new NotFoundHttpException();
        }
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            $this->addFlash('success', 'Your changes were saved!');

            return $this->redirect($this->generateUrl('user_all'));
        }

        return $this->render(
            'AppBundle:User:create.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @Route("", name="user_all")
     */
    public function allAction()
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $manager = $this->getDoctrine()->getManager();
        $users = $manager->getRepository('AppBundle:User')->findActiveUser();

        return $this->render('AppBundle:User:all.html.twig', compact('users'));
    }

    /**
     * @Route("/{id}/delete", name="user_delete")
     *
     * @param $id
     *
     * @return Response
     */
    public function deleteAction($id)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $manager = $this->getDoctrine()->getManager();

        $user = $manager->getRepository('AppBundle:User')->find($id);

        if (!$user) {
            throw new NotFoundHttpException();
        }

        $manager->remove($user);
        $manager->flush();
        $this->addFlash('success', 'User deleted successfully');

        return $this->redirectToRoute('user_all');
    }

    /**
     * @Route("/{id}/email", name="user_email")
     *
     * @param $id
     * @param $mailer
     *
     * @return Response
     */
    public function mailAction($id, \Swift_Mailer $mailer)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $manager->getRepository('AppBundle:User')->find($id);

        if (!$user) {
            throw new NotFoundHttpException();
        }

        $user->setPlainPassword('cxconnect');
        $user->setLoginSentAt(new \DateTime());
        $message = (new \Swift_Message('Welcome to CX Connect'))
            ->setFrom('admin@cxconnect.com.au', 'CX Connect')
            ->setTo($user->getEmail())
            ->setBcc('admin@cxconnect.com.au', 'CX Connect')
            ->setBody(
                $this->renderView(
                    'emails/registration.html.twig',
                    array('user' => $user)
                ),
                'text/html'
            );
        if ($mailer->send($message)) {
            $this->addFlash('success', 'Login email is sent successfully');
            $communication = new Communication();
            $communication->setUser($user);
            $communication->setAdmin($this->getUser());
            $communication->setType(Communication::TYPE_EMAIL);
            $communication->setSubject('Welcome to CX Connect');
            $communication->setContent(
                $this->renderView(
                    'emails/registration.html.twig',
                    array('user' => $user)
                )
            );
            $manager->persist($communication);
            $manager->flush();
        } else {
            $this->addFlash('danger', 'Login email is sent unsuccessfully');
        }

        return $this->redirectToRoute('user_all');
    }

    /**
     * @Route("/{id}/reset-password", name="supplier_reset_password")
     *
     * @param               $id
     * @param \Swift_Mailer $mailer
     *
     * @return Response
     */
    public function resetPasswordAction($id, \Swift_Mailer $mailer)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $manager->getRepository('AppBundle:User')->find($id);

        if (!$user) {
            throw new NotFoundHttpException();
        }

        $message = (new \Swift_Message('Reset your password'))
            ->setFrom('admin@cxconnect.com.au', 'CX Connect')
            ->setTo($user->getEmail())
            ->setBcc('admin@cxconnect.com.au', 'CX Connect')
            ->setBody(
                $this->renderView(
                    'emails/supplier/reset-password.html.twig',
                    array('user' => $user)
                ),
                'text/html'
            );

        if ($mailer->send($message)) {
            $this->addFlash('success', 'Reset password email is sent');
            $communication = new Communication();
            $communication->setUser($user);
            $communication->setAdmin($this->getUser());
            $communication->setType(Communication::TYPE_EMAIL);
            $communication->setSubject('Reset your password');
            $communication->setContent(
                $this->renderView(
                    'emails/supplier/reset-password.html.twig',
                    array('user' => $user)
                )
            );
            $manager->persist($communication);
            $manager->flush();
        } else {
            $this->addFlash('danger', 'Reset password email is sent unsuccessfully');
        }

        return $this->redirectToRoute('user_all');
    }

    /**
     * @Route("/{id}/communications", name="user_communications")
     * @param $id
     * @return Response
     */
    public function communicationsAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $manager->getRepository('AppBundle:User')->find($id);

        if (!$user) {
            throw new NotFoundHttpException();
        }

        return $this->render('@App/User/communications.html.twig', [
            'communications' => $user->getCommunications(),
            'user' => $user
        ]);
    }

}
