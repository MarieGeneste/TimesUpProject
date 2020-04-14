<?php

    namespace App\Service;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

    /**
     * Service permettant de renforcer la sécurité de l'application
     */
    class FrontSecurityService extends AbstractController
    {

        // Permet de nettoyer les données envoyées en post
        public function cleanPostData($data)
        {
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        public function cleanTrimPostData($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
    }

    