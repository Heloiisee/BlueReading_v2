<?php

/**
 * Model pour les livres
 * 
 * @author: Heloïse
 * @version: 1.0
 * @date: 2024-12-20
 * @copyright: 2024 Heloïse
 */

class Livre {
    private $conn;
    private $table_name = "livres";
    public $id; 
    public $titre; 
    public $auteur; 
    public $nombre_pages; 
    public $genre; 
    public $date_publication; 
    public $statut_lecture; 
    public $couverture; 
    public $fichier_ebook; 
    public $format_ebook;

    public function __construct($db) {
        $this->conn = $db;

    }

    public function ajouterLivre($etiquettes = [], $livreData) {
        try {
            $this->conn->beginTransaction();
            
            // Insertion du livre
            $query = "INSERT INTO " . $this->table_name . " (titre, auteur, nombre_pages, genre, date_publication, statut_lecture, couverture, fichier_ebook, format_ebook) VALUES (:titre, :auteur, :nombre_pages, :genre, :date_publication, :statut_lecture, :couverture, :fichier_ebook, :format_ebook)";
            $stmt = $this->conn->prepare($query);
            
            // Bind des paramètres...
            $stmt->bindParam(":titre", $livreData['titre']);
            $stmt->bindParam(":auteur", $livreData['auteur']);
            $stmt->bindParam(":nombre_pages", $livreData['nombre_pages']);
            $stmt->bindParam(":genre", $livreData['genre']);
            $stmt->bindParam(":date_publication", $livreData['date_publication']);
            $stmt->bindParam(":statut_lecture", $livreData['statut_lecture']);
            $stmt->bindParam(":couverture", $livreData['couverture']);
            $stmt->bindParam(":fichier_ebook", $livreData['fichier_ebook']);
            $stmt->bindParam(":format_ebook", $livreData['format_ebook']);
            $stmt->execute();
            $livre_id = $this->conn->lastInsertId();
            
            // Si des étiquettes ont été fournies
            if (!empty($etiquettes)) {
                foreach($etiquettes as $etiquette) {
                    // On n'ajoute que les étiquettes non vides
                    if (!empty(trim($etiquette))) {
                        // Vérifier si l'étiquette existe déjà
                        $etiquetteModel = new Etiquette($this->conn);
                        $etiquetteExistante = $etiquetteModel->getEtiquetteByNom($etiquette);
                        
                        if ($etiquetteExistante) {
                            $etiquette_id = $etiquetteExistante['id'];
                        } else {
                            // Créer une nouvelle étiquette
                            $query_etiquette = "INSERT INTO etiquette (nom) VALUES (:nom)";
                            $stmt_etiquette = $this->conn->prepare($query_etiquette);
                            $stmt_etiquette->bindParam(":nom", $etiquette);
                            $stmt_etiquette->execute();
                            $etiquette_id = $this->conn->lastInsertId();
                        }
                        
                        // Créer la liaison
                        $query_liaison = "INSERT INTO livre_etiquette (livre_id, etiquette_id) VALUES (:livre_id, :etiquette_id)";
                        $stmt_liaison = $this->conn->prepare($query_liaison);
                        $stmt_liaison->bindParam(":livre_id", $livre_id);
                        $stmt_liaison->bindParam(":etiquette_id", $etiquette_id);
                        $stmt_liaison->execute();
                    }
                    
                }

            }
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Modifier un livre
    public function modifierLivre($livre_id, $etiquettes = []) {
        try {
            $this->conn->beginTransaction();
            
            // Mise à jour des informations du livre
            $query = "UPDATE " . $this->table_name . " 
                    SET titre = :titre, 
                        auteur = :auteur, 
                        nombre_pages = :nombre_pages, 
                        genre = :genre, 
                        date_publication = :date_publication, 
                        statut_lecture = :statut_lecture, 
                        couverture = :couverture, 
                        fichier_ebook = :fichier_ebook, 
                        format_ebook = :format_ebook 
                    WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":titre", $this->titre);
            $stmt->bindParam(":auteur", $this->auteur);
            $stmt->bindParam(":nombre_pages", $this->nombre_pages);
            $stmt->bindParam(":genre", $this->genre);
            $stmt->bindParam(":date_publication", $this->date_publication);
            $stmt->bindParam(":statut_lecture", $this->statut_lecture);
            $stmt->bindParam(":couverture", $this->couverture);
            $stmt->bindParam(":fichier_ebook", $this->fichier_ebook);
            $stmt->bindParam(":format_ebook", $this->format_ebook);
            $stmt->bindParam(":id", $livre_id);
            
            $stmt->execute();
            
            // Supprimer toutes les anciennes liaisons d'étiquettes
            $query_delete = "DELETE FROM livre_etiquette WHERE livre_id = :livre_id";
            $stmt_delete = $this->conn->prepare($query_delete);
            $stmt_delete->bindParam(":livre_id", $livre_id);
            $stmt_delete->execute();
            
            // Ajouter les nouvelles étiquettes si présentes
            if (!empty($etiquettes)) {
                foreach($etiquettes as $etiquette) {
                    if (!empty($etiquette)) {
                        // Vérifier si l'étiquette existe déjà
                        $query_check = "SELECT id FROM etiquettes WHERE nom = :nom";
                        $stmt_check = $this->conn->prepare($query_check);
                        $stmt_check->bindParam(":nom", $etiquette);
                        $stmt_check->execute();
                        $etiquette_id = $stmt_check->fetchColumn();

                        // Si l'étiquette n'existe pas, l'ajouter
                        if (!$etiquette_id) {
                            $query_etiquette = "INSERT INTO etiquettes (nom) VALUES (:nom)";
                            $stmt_etiquette = $this->conn->prepare($query_etiquette);
                            $stmt_etiquette->bindParam(":nom", $etiquette);
                            $stmt_etiquette->execute();
                            $etiquette_id = $this->conn->lastInsertId();
                        }

                        // Créer la nouvelle liaison
                        $query_liaison = "INSERT INTO livre_etiquette (livre_id, etiquette_id) VALUES (:livre_id, :etiquette_id)";
                        $stmt_liaison = $this->conn->prepare($query_liaison);
                        $stmt_liaison->bindParam(":livre_id", $livre_id);
                        $stmt_liaison->bindParam(":etiquette_id", $etiquette_id);
                        $stmt_liaison->execute();
                    }
                }
            }
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
    // Supprimer un livre
    public function supprimerLivre($livre_id) {
        try {
            $this->conn->beginTransaction();
            
            // Supprimer d'abord les liaisons dans la table livre_etiquette
            $query_delete_liaisons = "DELETE FROM livre_etiquette WHERE livre_id = :livre_id";
            $stmt_liaisons = $this->conn->prepare($query_delete_liaisons);
            $stmt_liaisons->bindParam(":livre_id", $livre_id);
            $stmt_liaisons->execute();
            
            // Ensuite supprimer le livre
            $query_delete_livre = "DELETE FROM " . $this->table_name . " WHERE id = :livre_id";
            $stmt_livre = $this->conn->prepare($query_delete_livre);
            $stmt_livre->bindParam(":livre_id", $livre_id);
            $stmt_livre->execute();
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Récupérer tous les livres
    public function getLivres() {
        try {
            // Requête SQL pour récupérer les livres avec leurs étiquettes
            $query = "SELECT l.*, 
                    GROUP_CONCAT(e.nom) as etiquettes
                    FROM " . $this->table_name . " l
                    LEFT JOIN livre_etiquette le ON l.id = le.livre_id
                    LEFT JOIN etiquette e ON le.etiquette_id = e.id
                    GROUP BY l.id
                    ORDER BY l.date_publication DESC";
    
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Conversion des étiquettes en tableau
            foreach ($livres as &$livre) {
                $livre['etiquettes'] = $livre['etiquettes'] 
                    ? explode(',', $livre['etiquettes']) 
                    : [];
            }
            
            return $livres;
            
        } catch (PDOException $e) {
            // Log de l'erreur
            error_log("Erreur lors de la récupération des livres : " . $e->getMessage());
            return false;
        }
    }

    // Récupérer un livre par son ID
    public function getLivreById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>