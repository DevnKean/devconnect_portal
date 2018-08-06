<?php

namespace AppBundle\Controller;

use AppBundle\Form\ResetPasswordTokenType;
use AppBundle\Form\ResetPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return Response
     */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('AppBundle:Security:login.html.twig', compact('error','lastUsername'));
    }

    /**
     * @Route("/reset-password", name="reset_password")
     *
     * @param Request $request
     * @param $mailer
     *
     * @return Response
     */
    public function resetPasswordAction(Request $request, \Swift_Mailer $mailer)
    {

        $manager = $this->getDoctrine()->getManager();
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $username = $data['username'];

            if (stripos($username,'@') !== false) {
                $user = $manager->getRepository('AppBundle:User')->findOneBy(['email' => $username]);
            } else {
                $user = $manager->getRepository('AppBundle:User')->findOneBy(['username' => $username]);
            }

            if (!$user) {
                $form->addError(new FormError('User not found'));
            } else {
                $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
                $user->setConfirmationToken($token);
                $message = (new \Swift_Message('Password Reset'))
                    ->setFrom('admin@cxconnect.com.au', 'CX Connect')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'emails/reset-password.html.twig',
                            ['user' => $user, 'token' => $token]
                        ),
                        'text/html'
                    );
                $mailer->send($message);
                $manager->persist($user);
                $manager->flush();
                return $this->render('@App/Security/reset-password-send-mail.html.twig');
            }

        }

        return $this->render('@App/Security/reset-password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/reset-password/{token}", name="reset_password_token")
     *
     * @param $token
     * @param $request
     * @param $passwordEncoder
     *
     * @return Response
     */
    public function resetPasswordTokenAction(Request $request, $token, UserPasswordEncoderInterface $passwordEncoder)
    {
        $manager = $this->getDoctrine()->getManager();
        $form = $this->createForm(ResetPasswordTokenType::class);
        $form->handleRequest($request);

        $user = $manager->getRepository('AppBundle:User')->findOneBy(['confirmationToken' => $token]);

        if (null === $user) {
            return $this->render('@App/Security/reset-password.error.html.twig');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user->setConfirmationToken(null);
            $user->setPasswordRequestedAt(null);
            $password = $passwordEncoder->encodePassword($user, $data['plainPassword']);
            $user->setPassword($password);
            $manager->persist($user);
            $manager->flush();
            return $this->render('@App/Security/reset-password.success.html.twig');
        }

        return $this->render('@App/Security/reset-password-token.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
