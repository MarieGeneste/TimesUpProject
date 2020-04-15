<?php

namespace App\Service;

use App\Repository\GameModeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Service permettant de retourner des valeurs aléatoires
 */
class RandomService extends AbstractController
{

    private $gameModeRepo;

    public function __construct(GameModeRepository $gameModeRepo) {

        $this->gameModeRepo = $gameModeRepo;
    }

    // Permet de nettoyer les données envoyées en post

    /**
     * Retourne une Couleur aléatoire
     *
     * @return string
     */
    public function getRandomColor()
    {
        $result = array('rgb' => '', 'hex' => '');
        foreach (array('r', 'b', 'g') as $col) {
            $rand = mt_rand(0, 255);
            // $result['rgb'][$col] = $rand;
            $dechex = dechex($rand);
            if (strlen($dechex) < 2) {
                $dechex = '0' . $dechex;
            }
            $result['hex'] .= $dechex;
        }

        $rgbColor = "#". $result['hex'];
        return $rgbColor;
    }

    public function timesUpGameMode() {

        $timesUpCameMode = $this->gameModeRepo->findOneBy(['tag' => 'Times-Up']);
        return $timesUpCameMode;
    }
}
