<?php
/**
 * Controller pour les livres
 *
 * @author: Heloïse
 * @version: 1.0
 * @date: 2024-12-20
 * @copyright: 2024 Heloïse
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Livre.php';

class LivresController {
    private $livresModel;
    private $conn;  

    public function __construct() {
        $this->conn = new Database();
        $this->livresModel = new Livre($this->conn);
    }

    // Méthode principale appelée par l'index
    

    public function ajouterLivre() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $livre = $this->livresModel;
            $livre->titre = filter_input(INPUT_POST, 'titre', FILTER_SANITIZE_SPECIAL_CHARS);
            $livre->auteur = filter_input(INPUT_POST, 'auteur', FILTER_SANITIZE_SPECIAL_CHARS);
            $livre->nombre_pages = filter_input(INPUT_POST, 'nombre_pages', FILTER_VALIDATE_INT);
            $livre->genre = filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_SPECIAL_CHARS);
            $livre->date_publication = filter_input(INPUT_POST, 'date_publication', FILTER_SANITIZE_SPECIAL_CHARS);
            $livre->statut_lecture = filter_input(INPUT_POST, 'statut_lecture', FILTER_SANITIZE_SPECIAL_CHARS);
            $livre->couverture = filter_input(INPUT_POST, 'couverture', FILTER_SANITIZE_SPECIAL_CHARS);
            $livre->fichier_ebook = filter_input(INPUT_POST, 'fichier_ebook', FILTER_SANITIZE_SPECIAL_CHARS);
            $livre->format_ebook = filter_input(INPUT_POST, 'format_ebook', FILTER_SANITIZE_SPECIAL_CHARS);
            
            $etiquettes = array_filter($_POST['etiquettes'] ?? [], function($etiquette) {
                return !empty(trim($etiquette));
            });
            
            if($livre->ajouterLivre($etiquettes)) {
                $_SESSION['success'] = "Le livre a été ajouté avec succès";
                header('Location: action=bibliotheque');
                exit;
            } else {
                $_SESSION['error'] = "Erreur lors de l'ajout du livre";
            }
        }
        
        require_once  'views/livres/ajouter.php';
    }

    public function modifierLivre($id) {
        if(!$id) {
            $_SESSION['error'] = "ID du livre invalide";
            header('Location: action=bibliotheque');
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $livre = $this->livresModel;
            $livre->id = $id;
            $livre->titre = filter_input(INPUT_POST, 'titre', FILTER_SANITIZE_SPECIAL_CHARS);
            $livre->auteur = filter_input(INPUT_POST, 'auteur', FILTER_SANITIZE_SPECIAL_CHARS);
            $livre->nombre_pages = filter_input(INPUT_POST, 'nombre_pages', FILTER_VALIDATE_INT);
            $livre->genre = filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_SPECIAL_CHARS);
            $livre->date_publication = filter_input(INPUT_POST, 'date_publication', FILTER_SANITIZE_SPECIAL_CHARS);
            $livre->statut_lecture = filter_input(INPUT_POST, 'statut_lecture', FILTER_SANITIZE_SPECIAL_CHARS);
            
            $etiquettes = array_filter($_POST['etiquettes'] ?? [], function($etiquette) {
                return !empty(trim($etiquette));
            });
            
            if($livre->modifierLivre($etiquettes)) {
                $_SESSION['success'] = "Le livre a été modifié avec succès";
                header('Location: action=bibliotheque');
                exit;
            } else {
                $_SESSION['error'] = "Erreur lors de la modification du livre";
            }
        }

        $livre = $this->livresModel->getLivreById($id);
        require 'views/livres/modifier.php';
    }

    public function supprimerLivre($id) {
        if(!$id) {
            $_SESSION['error'] = "ID du livre invalide";
            header('Location: action=bibliotheque');
            exit;
        }

        if($this->livresModel->supprimerLivre($id)) {
            $_SESSION['success'] = "Le livre a été supprimé avec succès";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression du livre";
        }

            header('Location: action=bibliotheque');
        exit;
    }

    public function afficherLivres() {
        $bd = new Database();
        $livre = new Livre($bd->getConnection());
        $livres = $livre->getLivres();
        require_once 'views/livres/bibliotheque.php';
    }

}