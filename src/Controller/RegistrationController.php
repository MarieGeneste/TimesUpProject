<?php

namespace App\Controller;

use Swift_Mailer;
use Swift_Message;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\UserAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/inscription", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UserAuthenticator $authenticator, \Swift_Mailer $mailer): Response
    {
        $user = new User();
        $userProfile = new UserProfile();

        $user->setUserProfile($userProfile);

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plain_password')->getData()
                )
            );

            $user->setActivationToken(\md5(\uniqid()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email
            $message = (new \Swift_Message('activation de votre compte - Visio Game Party'))
                    ->setFrom('MissLibellule19@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView('email/accountActivation.html.twig', ['token' => $user->getActivationToken()]), 'text/html'
                    );
            
            $mailer->send($message);
            $this->addFlash('success', 'Un email de validation a bien été envoyé. Merci de cliquer sur le lien pour activer votre compte.');

            return $this->redirectToRoute('home');

            // return $guardHandler->authenticateUserAndHandleSuccess(
            //     $user,
            //     $request,
            //     $authenticator,
            //     'main' // firewall name in security.yaml
            // );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/activation/{token}", name="activation")
     */
    public function activation ($token, UserRepository $userRep){
        $user = $userRep->findOneBy(['activation_token' => $token]);

        if (!$user) {
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }

        $user->setActivationToken(null);
        $user->setIsActive(true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->addFlash('success', 'Votre compte a bien été activé !');

        return $this->redirectToRoute('home');
    }
}
