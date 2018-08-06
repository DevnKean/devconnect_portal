<?php

namespace SupplierBundle\Controller;

use AppBundle\Entity\User;
use SupplierBundle\Form\SupplierUserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class UserController
 *
 * @package SupplierBundle\Controller
 * @Route("/users")
 */
class UserController extends Controller
{
    /**
     * @Route("", name="supplier_users_all")
     */
    public function allAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $manager = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $users = $manager->getRepository('AppBundle:User')->findBy(['supplier' => $user->getSupplier()]);

        return $this->render('SupplierBundle:User:all.html.twig', compact('users'));
    }

    /**
     * @Route("/create", name="supplier_users_create")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param \Swift_Mailer $mailer
     * @return Response
     */
    public function createAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $supplier = $user->getSupplier();
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $user->setSupplier($supplier);
        $form = $this->createForm(SupplierUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $manager->persist($user);
            $manager->flush();

            $message = (new \Swift_Message('Welcome to CX Connect'))
                ->setFrom('admin@cxconnect.com.au', 'CX Connect')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/registration.html.twig',
                        array('supplier' => $supplier)
                    ),
                    'text/html'
                );
            $mailer->send($message);

            $this->addFlash('success', 'User created successfully');
            return $this->redirectToRoute('supplier_users_all');
        }

        return $this->render('SupplierBundle:User:create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="supplier_users_edit")
     *
     * @param $request
     * @param $id
     *
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $manager = $this->getDoctrine()->getManager();
        /** @var User $currentUser */
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        /** @var User $user */
        $user = $manager->getRepository('AppBundle:User')->find($id);

        if (!$user) {
            throw new NotFoundHttpException('User Not Found');
        }

        if ($user->getSupplier() !== $currentUser->getSupplier()) {
            throw new AccessDeniedException();
        }

        if ($user->isAdmin()) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(SupplierUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'User updated successfully');
            return $this->redirectToRoute('supplier_users_all');
        }

        return $this->render('SupplierBundle:User:edit.html.twig', [
           'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/delete", name="supplier_users_delete")
     *
     * @param $id
     *
     * @return Response
     */
    public function deleteAction($id) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $manager = $this->getDoctrine()->getManager();
        /** @var User $currentUser */
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        /** @var User $user */
        $user = $manager->getRepository('AppBundle:User')->find($id);

        if (!$user) {
            throw new NotFoundHttpException('User Not Found');
        }

        if ($user->getSupplier() !== $currentUser->getSupplier()) {
            throw new AccessDeniedException();
        }

        if ($user->isAdmin()) {
            throw new AccessDeniedException();
        }

        $manager->remove($user);
        $manager->flush();
        $this->addFlash('success', 'User deleted successfully');
        return $this->redirectToRoute('supplier_users_all');
    }

}
