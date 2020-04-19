<?php

namespace App\Controller\UserAccount;

use App\Entity\User;
use App\Form\FriendType;
use App\Form\ProfileType;
use App\Repository\UserRepository;
use App\Form\UserProfileEditionType;
use App\Service\FrontSecurityService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

    /**
     * @Route("/mon-compte", name="account_")
     */
class AccountController extends AbstractController
{
    private $securityService;

    public function __construct(FrontSecurityService $securityService)
    {
        $this->securityService = $securityService;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(Request $request, UserRepository $userRep, UserRepository $userRepository)
    {
        $user = $this->getUser();
        $userProfile = $user->getUserProfile();
        
        $friendForm = $this->createForm(FriendType::class);
        $userProfileForm = $this->createForm(UserProfileEditionType::class, $user);
        $profileForm = $this->createForm(ProfileType::class, $userProfile);

        dump($user);
        dump($userProfile);

        return $this->render('userAccount/index.html.twig', [
            'user' => $user,
            'friendForm' => $friendForm->createView(),
            'profileForm' => $profileForm->createView(),
            'userProfileForm' => $userProfileForm->createView(),
        ]);
    }

    /**
     * @Route("/edition", name="edit")
     */
    public function edit(Request $request)
    {
        $user = $this->getUser();
        $userProfile = $user->getUserProfile();

        $user->setPlainPassword($user->getPassword());
        $userProfileForm = $this->createForm(UserProfileEditionType::class, $user);
        $profileForm = $this->createForm(ProfileType::class, $userProfile);

        $userProfileForm->handleRequest($request);
        $profileForm->handleRequest($request);

        dump($request);
        dump($profileForm);
        dump($userProfileForm->isSubmitted() && $userProfileForm->isValid());

        if ($userProfileForm->isSubmitted() && $userProfileForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La modification de votre profil a bien été enregistrée');
        }

        if ($profileForm->isSubmitted() && $profileForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La modification de votre photo de profil a bien été enregistrée');
        }

        return $this->redirectToRoute('account_index');
    }


    /**
     * @Route("/mes-amis", name="friend_search")
     */
    public function accountFriendSearch(Request $request, UserRepository $userRep)
    {
        $friendForm = $this->createForm(FriendType::class);
        $friendForm->handleRequest($request);

        if ($friendForm->isSubmitted() && $friendForm->isValid()) {
            $friendUsername = $friendForm->getData();
            $friend = $userRep->findOneBy(['username' => $friendUsername, 'isActive' => true]);

            $data["friendUsername"] = null;
            $data["friendId"] = null;
            $data["friendFound"] = false;
            
            if (!empty($friend)) {
                $data["friendFound"] = true;
                $data["friendUsername"] = $friend->getUsername();
                $data["friendId"] = $friend->getId();
            }
    
            return $this->json($data);
        }

        return $this->redirectToRoute('account_index');
    }

    /**
     * @Route("/mes-amis/damande", name="friend_invitation")
     */
    public function accountFriendInvitation(Request $request, UserRepository $userRep, \Swift_Mailer $mailer)
    {
        $curentUser = $this->getUser();

        $friendId = $this->securityService->cleanTrimPostData($request->request->get('friendId'));
        
        $friend = $userRep->findOneById($friendId);

        if (!$friend) {
            $this->addFlash('error', 'Une erreur est survenue : l\'utilisateur ajouté n\'existe pas');
            return $this->redirectToRoute('account_friend_search');
        } elseif ((array_search($friend->getUsername(), $curentUser->getFriendRequests())) !== false) {
        // } elseif ($curentUser->getFriendRequests()->contains($friend->getUsername())) {
            $this->addFlash('error', 'Vous avez déjà envoyé une demande d\'ami à cet utilisateur');
            return $this->redirectToRoute('account_friend_search');
        // } elseif (!empty($userRep->alreadyHaveThisFriend($curentUser, $friend))) {
        } elseif ($curentUser->getFriends()->contains($friend)) {
            $this->addFlash('error', 'Vous êtes déjà ami avec cet utilisateur cet utilisateur');
            return $this->redirectToRoute('account_friend_search');
        }

        $curentUser->addFriendRequest($friend->getUsername());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        dump(array_search($friend->getUsername(), $curentUser->getFriendRequests()));
        dump(array_search("test", $curentUser->getFriendRequests()));

        // do anything else you need here, like send an email
        $message = (new \Swift_Message('Demande d\'ami - Visio Game Party'))
                ->setFrom('MissLibellule19@gmail.com')
                ->setTo($friend->getEmail())
                ->setBody(
                    $this->renderView('email/friendAsk.html.twig', ['AskingUsername' => $curentUser->getUsername(), 'AskingUserId' => $curentUser->getId(), 'friendId' => $friend->getId()]), 'text/html'
                );
        
        $mailer->send($message);
        
        $this->addFlash('success', 'Votre demande a bien été envoyée par mail à '. $friend->getUsername());
        return $this->redirectToRoute('account_index');
    }

    /**
     * @Route("/mes-amis/ajout/{askingUser}/{friend}", name="friend_acceptation")
     */
    public function accountFriendAdd(User $askingUser, User $friend, UserRepository $userRep, \Swift_Mailer $mailer)
    {
        $askingUser->addFriend($friend);
        $askingUser->removeFriendRequest($friend->getUsername());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        
        $this->addFlash('success', 'Vous êtes maintenant ami avec '. $askingUser->getUsername());
        return $this->redirectToRoute('account_index');
    }
}
