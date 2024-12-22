<?php
session_start();

// Activer l'affichage des erreurs pour le diagnostic
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclusion des fichiers essentiels
require_once 'config/database.php';
require_once 'controllers/LivresController.php';
require_once 'controllers/EtiquettesController.php';
require_once 'controllers/MainController.php';
require_once 'views/v_header.php';





// Sélection du contrôleur approprié
if(isset($_GET['action'])){
    $action = $_GET['action'];
    switch ($action) {
        case 'accueil':
            $controller = new MainController();
            $controller->accueil();
            break;
    case 'ajouter':
        $controller = new LivresController();
        $controller->ajouterLivre();
        break;

    case 'bibliotheque':
        $controller = new LivresController();
        $controller->afficherLivres();
        break;

    case 'apropos':
        $controller = new MainController();
        $controller->apropos();
        break;

    default:
        $controller = new MainController();
        $controller->accueil();
        break;
    }
} else {
    $controller = new MainController();
    $controller->accueil();
}
require_once 'views/v_footer.php';