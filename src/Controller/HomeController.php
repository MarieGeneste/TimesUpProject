<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $isCurentGame = false;
        
        return $this->render('home/index.html.twig', [
            'isCurentGame' => $isCurentGame,
        ]);
    }
}
