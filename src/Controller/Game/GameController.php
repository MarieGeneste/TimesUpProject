<?php

namespace App\Controller\Game;

use App\Repository\GameModeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(GameModeRepository $gameRep)
    {
        $isCurentGame = false;
        $gameModes = $gameRep->findAll();
        
        return $this->render('game/index.html.twig', [
            'gameModes' => $gameModes,
            'isCurentGame' => $isCurentGame,
        ]);
    }
}
