<?php

/**
 * Controller pour les étiquettes
 * 
 * @author: Heloïse
 * @version: 1.0
 * @date: 2024-12-20
 * @copyright: 2024 Heloïse
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Etiquette.php';

class EtiquettesController {
    private $etiquetteModel;
    
    public function __construct() {
        $database = new Database();
        $this->etiquetteModel = new Etiquette($database->getConnection());
    }

    public function afficherAccueil() {
        require_once '../views/accueil.php';
    }
    
    public function index() {
        try {
            $etiquettes = $this->etiquetteModel->lireEtiquettes();
            require_once '../views/etiquettes/index.php';
        } catch (Exception $e) {
            $_SESSION['error'] = "Erreur lors de la récupération des étiquettes";
            header('Location: /');
            exit;
        }
    }
    
    public function ajouterEtiquette() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return require_once '../views/etiquettes/ajouter.php';
        }
        
        try {
            // Validation
            if (empty($_POST['nom'])) {
                throw new Exception("Le nom de l'étiquette est requis");
            }
            
            $nom = trim($_POST['nom']);
            
            // Vérifier si l'étiquette existe déjà
            if ($this->etiquetteModel->etiquetteExiste($nom)) {
                throw new Exception("Cette étiquette existe déjà");
            }
            
            // Ajouter l'étiquette
            if ($this->etiquetteModel->ajouterEtiquette($nom)) {
                $_SESSION['success'] = "Étiquette ajoutée avec succès";
                header('Location: /etiquettes');
                exit;
            }
            
            throw new Exception("Erreur lors de l'ajout de l'étiquette");
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return require_once '../views/etiquettes/ajouter.php';
        }
    }
    
    public function modifierEtiquette($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // Récupérer l'étiquette pour le formulaire
            try {
                $etiquette = $this->etiquetteModel->getEtiquetteById($id);
                if (!$etiquette) {
                    throw new Exception("Étiquette non trouvée");
                }
                return require_once '../views/etiquettes/modifier.php';
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header('Location: /etiquettes');
                exit;
            }
        }
        
        try {
            // Validation
            if (empty($_POST['nom'])) {
                throw new Exception("Le nom de l'étiquette est requis");
            }
            
            $nom = trim($_POST['nom']);
            
            // Vérifier si le nouveau nom existe déjà pour une autre étiquette
            if ($this->etiquetteModel->etiquetteExiste($nom, $id)) {
                throw new Exception("Une étiquette avec ce nom existe déjà");
            }
            
            // Modifier l'étiquette
            if ($this->etiquetteModel->modifierEtiquette($id, $nom)) {
                $_SESSION['success'] = "Étiquette modifiée avec succès";
                header('Location: /etiquettes');
                exit;
            }
            
            throw new Exception("Erreur lors de la modification de l'étiquette");
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return require_once '../views/etiquettes/modifier.php';
        }
    }
    
    public function supprimerEtiquette($id) {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Méthode non autorisée");
            }
            
            // Vérifier si l'étiquette est utilisée
            if ($this->etiquetteModel->estUtilisee($id)) {
                throw new Exception("Cette étiquette ne peut pas être supprimée car elle est utilisée par des livres");
            }
            
            if ($this->etiquetteModel->supprimerEtiquette($id)) {
                $_SESSION['success'] = "Étiquette supprimée avec succès";
            } else {
                throw new Exception("Erreur lors de la suppression de l'étiquette");
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        header('Location: /etiquettes');
        exit;
    }

    public function afficherEtiquettes() {
        $etiquettes = $this->etiquetteModel->getEtiquettes();
        require_once '../views/etiquettes/index.php';
    }
}