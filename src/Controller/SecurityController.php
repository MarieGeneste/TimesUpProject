<?php

namespace App\Controller;

use App\Form\EmailType;
use App\Form\ResetPassType;
use App\Repository\UserRepository;
use App\Service\FrontSecurityService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/connexion", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        // $emailForm = $this->createForm(EmailType::class);

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            // 'emailForm' => $emailForm->createView(),
            'last_username' => $lastUsername, 
            'error' => $error
        ]);
    }

    /**
     * @Route("/oubli-mot-de-passe", name="app_forget_pwd")
     */
    public function forgetPwd (Request $request, UserRepository $userRep, FrontSecurityService $securityService, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator){

        $forgetEmail = $securityService->cleanTrimPostData($request->request->get('forgetEmail'));

        if(!empty($forgetEmail)) {
            $user = $userRep->findOneBy(['email' => $forgetEmail]);
    
            if (!$user) {
                throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
            }
    
            $token = $tokenGenerator->generateToken();
    
            try {
                $user->setActivationToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            } catch (\Throwable $th) {
                $this->addFlash('error', 'Une erreur est survenue : '. $th->getMessage());
                return $this->redirectToRoute('app_login');
            }
    
            $url = $this->generateUrl('reset_password', ['token'=> $token], UrlGeneratorInterface::ABSOLUTE_URL);
    
            // do anything else you need here, like send an email
            $message = (new \Swift_Message('Réinitialisation de votre mot de passe - VisioGames'))
                    ->setFrom('MissLibellule19@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView('email/forgottenPassword.html.twig', ['token' => $user->getActivationToken(), 'url' => $url]), 'text/html'
                    );
            
            $mailer->send($message);
    
            $this->addFlash('success', 'Un email de réinitialisation de mot de passe vous a été envoyé');
    
            return $this->redirectToRoute('home');
        } else {
            $this->addFlash('error', 'Une erreur est survenue : Vous devez obligatoirement remplir votre adresse email rattachée à votre compte');
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("/reinitialisation-mot-de-passe/{token}", name="reset_password")
     */
    public function reset_password ($token, UserRepository $userRep, Request $request, UserPasswordEncoderInterface $passwordEncoder){
        $user = $userRep->findOneBy(['activation_token' => $token]);

        if (!$user) {
            throw $this->createNotFoundException('Token inconnu');
        }

        $resetPasswordForm = $this->createForm(ResetPassType::class);
        $resetPasswordForm->handleRequest($request);

        if ($resetPasswordForm->isSubmitted() && $resetPasswordForm->isValid()) {

            try {
                $user->setActivationToken(null);
                
                // encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $resetPasswordForm->get('plain_password')->getData()
                    )
                );
        
                $em = $this->getDoctrine()->getManager();
                $em->flush();
        
                $this->addFlash('success', 'Votre nouveau mot de passe a bien été enregistré');
        
                return $this->redirectToRoute('home');
            } catch (\Throwable $th) {
                $this->addFlash('error', 'Une erreur est survenue : '. $th->getMessage());
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('security/resetPassword.html.twig', [
            'resetPasswordForm' => $resetPasswordForm->createView(),
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
