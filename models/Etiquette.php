<?php

/**
 * Model pour les étiquettes
 * 
 * @author: Heloïse
 * @version: 1.0
 * @date: 2024-12-20
 * @copyright: 2024 Heloïse
 */

class Etiquette {
    private $conn;
    private $table_name = "etiquettes";
    public $id;
    public $nom;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function ajouterEtiquette($nom) {
        // Vérifier si le nom est vide
        if(empty($nom)) {
            return false;
        }

        try {
            $this->conn->beginTransaction();

            // Vérifier si l'étiquette existe déjà
            $query_check = "SELECT id FROM " . $this->table_name . " WHERE nom = :nom";
            $stmt_check = $this->conn->prepare($query_check);
            $stmt_check->bindParam(":nom", $nom);
            $stmt_check->execute();
            $etiquette_id = $stmt_check->fetchColumn();

            // Si l'étiquette n'existe pas, l'ajouter
            if (!$etiquette_id) {
                $query = "INSERT INTO " . $this->table_name . " (nom) VALUES (:nom)";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":nom", $nom);
                $stmt->execute();
            }

            $this->conn->commit();
            return true;
        } catch(PDOException $e) {
            $this->conn->rollBack();
            echo "Erreur lors de l'ajout de l'étiquette : " . $e->getMessage();
            return false;
        }
    }

    public function lireEtiquettes() {
        try {
            // Requête SQL pour lire toutes les étiquettes
            $query = "SELECT * FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourner les résultats sous forme de tableau associatif
        } catch(PDOException $e) {
            echo "Erreur lors de la lecture des étiquettes : " . $e->getMessage(); // Optionnel : journaliser l'erreur
            return false;
        }
    }

    public function supprimerEtiquette($id) {
        if(empty($id)) {
            return false;
        }
        try {
            $this->conn->beginTransaction();

            // Requête SQL pour supprimer une étiquette
            $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch(PDOException $e) {
            $this->conn->rollBack();
            echo "Erreur lors de la suppression de l'étiquette : " . $e->getMessage();
            return false;
        }
    }

    public function modifierEtiquette($id, $nom) {
        if(empty($id) || empty($nom)) { // Vérifier si l'ID ou le nom est vide
            return false;
        }
        try {
            $this->conn->beginTransaction(); // Démarrer la transaction

            // Requête SQL pour modifier une étiquette
            $query = "UPDATE " . $this->table_name . " SET nom = :nom WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":nom", $nom); // Utiliser le nom passé en paramètre
            $stmt->bindParam(":id", $id); // Utiliser l'ID passé en paramètre
            $stmt->execute();

            $this->conn->commit(); // Valider la transaction
            return true;
        } catch(PDOException $e) {
            $this->conn->rollBack(); // Annuler la transaction en cas d'erreur
            echo "Erreur lors de la modification de l'étiquette : " . $e->getMessage(); // Optionnel : journaliser l'erreur
            return false;
        }
    }

    public function etiquetteExiste($nom, $id = null) {
        try {
            // Si on modifie une étiquette, on exclut l'étiquette courante de la vérification
            $query = "SELECT id FROM etiquette WHERE nom = :nom";
            if ($id !== null) {
                $query .= " AND id != :id";
            }
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":nom", $nom);
            if ($id !== null) {
                $stmt->bindParam(":id", $id);
            }
            $stmt->execute();
            
            return $stmt->rowCount() > 0;
            
        } catch (PDOException $e) {
            error_log("Erreur lors de la vérification de l'existence de l'étiquette : " . $e->getMessage());
            throw new Exception("Erreur lors de la vérification de l'étiquette");
        }
    }

    public function getEtiquetteById($id) {
        try {
            $query = "SELECT * FROM etiquette WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'étiquette : " . $e->getMessage());
            throw new Exception("Erreur lors de la récupération de l'étiquette");
        }
    }

    public function estUtilisee($id) {
        try {
            $query = "SELECT COUNT(*) as count FROM livre_etiquette WHERE etiquette_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
            
        } catch (PDOException $e) {
            error_log("Erreur lors de la vérification de l'utilisation de l'étiquette : " . $e->getMessage());
            throw new Exception("Erreur lors de la vérification de l'étiquette");
        }
    }

    public function getEtiquetteByNom($nom) {
        try {
            $query = "SELECT id FROM etiquette WHERE nom = :nom";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":nom", $nom);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'étiquette : " . $e->getMessage());
            throw new Exception("Erreur lors de la récupération de l'étiquette");
        }
    }

    public function getEtiquettes() {
        try {
            $query = "SELECT * FROM etiquette";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des étiquettes : " . $e->getMessage());
            throw new Exception("Erreur lors de la récupération des étiquettes");
        }
    }
}