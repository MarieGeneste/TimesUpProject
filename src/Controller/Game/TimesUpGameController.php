<?php

namespace App\Controller\Game;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TimesUpGameController extends AbstractController
{
    /**
     * @Route("/Game/Times-Up", name="times_up")
     */
    public function index()
    {
        $isCurentGame = false;
        
        return $this->render('game/index.html.twig', [
            'isCurentGame' => $isCurentGame,
        ]);
    }
}
