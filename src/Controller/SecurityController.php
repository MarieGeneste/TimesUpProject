<?php

namespace App\Controller;

use App\Form\EmailType;
use App\Repository\UserRepository;
use App\Service\FrontSecurityService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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
     * @Route("/mot-de-passe-oublie", name="app_forget_pwd")
     */
    public function forgetPwd (Request $request, UserRepository $userRep, FrontSecurityService $securityService){

        $forgetEmail = $securityService->cleanTrimPostData($request->request->get('forgetEmail'));
        $user = $userRep->findOneBy(['email' => $forgetEmail]);

        if (!$user) {
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }

        $this->addFlash('success', 'Votre compte a bien été activé !');

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
