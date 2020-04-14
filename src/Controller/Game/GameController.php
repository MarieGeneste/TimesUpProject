<?php

namespace App\Controller\Game;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $isCurentGame = false;
        
        return $this->render('game/index.html.twig', [
            'isCurentGame' => $isCurentGame,
        ]);
    }
}
