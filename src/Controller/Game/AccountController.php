<?php

namespace App\Controller\Game;

use App\Form\FriendType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    /**
     * @Route("/mes-amis", name="friend_search")
     */
    public function accountFriendSearch(Request $request, UserRepository $userRep)
    {
        $friendForm = $this->createForm(FriendType::class);
        $friendForm->handleRequest($request);

        if ($friendForm->isSubmitted() && $friendForm->isValid()) {
            $friendUsername = $friendForm->getData();
            $friend = $userRep->findOneBy(['username' => $friendUsername]);

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

        return $this->render('game/friends.html.twig', [
            'friendForm' => $friendForm->createView(),
        ]);
    }
}
