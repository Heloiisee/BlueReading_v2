<?php

// controllers/MainController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/MainManager.php';

class MainController {
    private $mainManager;
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->mainManager = new MainManager($database->getConnection());
    }

    public function accueil() {
        try {
            // Récupération des statistiques
            $stats = $this->mainManager->getStatistiques();
            // Inclusion de la vue
            require_once 'views/accueil.php';
        } catch(Exception $e) {
            $_SESSION['error'] = "Une erreur est survenue lors du chargement de la page d'accueil";
            $this->pageNonTrouvee();

            // Vous pouvez rediriger vers une page d'erreur ici si vous le souhaitez
        }
    }
    public function apropos() {
        $apropos = $this->mainManager->getAProposContent();
        require_once 'views/apropos.php';
    }

    // Méthode pour gérer les erreurs 404
    public function pageNonTrouvee() {
        require_once 'views/404.php';
    }

    // controllers/MainController.php
public function pageStatique($page) {
    switch($page) {
        case 'conditions':
            require_once  '/../views/pages/conditions.html';
            break;
        case 'mentions':
            require_once '/../views/pages/mentions.html';
            break;
        default:
            $this->pageNonTrouvee();
        }
    }


}
