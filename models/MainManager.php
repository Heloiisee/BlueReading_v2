<?php

// models/MainManager.php
class MainManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getStatistiques() {
        try {
            // Nombre total de livres
            $queryLivres = "SELECT COUNT(*) as total_livres FROM livres";
            $stmtLivres = $this->db->prepare($queryLivres);
            $stmtLivres->execute();
            $totalLivres = $stmtLivres->fetch(PDO::FETCH_ASSOC)['total_livres'];

            // Nombre total d'étiquettes
            $queryEtiquettes = "SELECT COUNT(*) as total_etiquettes FROM etiquettes";
            $stmtEtiquettes = $this->db->prepare($queryEtiquettes);
            $stmtEtiquettes->execute();
            $totalEtiquettes = $stmtEtiquettes->fetch(PDO::FETCH_ASSOC)['total_etiquettes'];

            // Derniers livres ajoutés
            $queryDerniersLivres = "SELECT * FROM livres ORDER BY date_ajout DESC LIMIT 5";
            $stmtDerniersLivres = $this->db->prepare($queryDerniersLivres);
            $stmtDerniersLivres->execute();
            $derniersLivres = $stmtDerniersLivres->fetchAll(PDO::FETCH_ASSOC);

            return [
                'total_livres' => $totalLivres,
                'total_etiquettes' => $totalEtiquettes,
                'derniers_livres' => count($derniersLivres) // On retourne le nombre pour l'affichage
            ];
        } catch(PDOException $e) {
            error_log($e->getMessage());
            return [
                'total_livres' => 0,
                'total_etiquettes' => 0,
                'derniers_livres' => 0
            ];
        }
    }


    // models/MainManager.php
public function getAProposContent() {
    // Dans un vrai projet, ces données pourraient venir de la base de données
    return [
        'histoire' => [
            'titre' => 'Notre Histoire',
            'description' => 'BlueReading est née de la passion d\'une jeune développeuse pour la lecture et le développement web. Ce projet personnel vise à créer une expérience simple et élégante pour tous les passionnés de lecture.'
        ],
        'fonctionnalites' => [
            [
                'icone' => 'fas fa-layer-group',
                'titre' => 'Une Interface Épurée',
                'description' => 'Une expérience utilisateur simple et intuitive pour gérer votre bibliothèque personnelle'
            ],
            [
                'icone' => 'fas fa-heart',
                'titre' => 'Fait avec Passion',
                'description' => 'Chaque fonctionnalité a été pensée avec soin pour les amoureux des livres'
            ]
        ],
        'contact' => [
            'titre' => 'Un Projet en Évolution',
            'description' => 'Actuellement disponible en version desktop, BlueReading continue de grandir et d\'évoluer. Vos retours sont précieux pour améliorer l\'expérience.',
            'invitation' => 'Une suggestion ? Une idée ? N\'hésitez pas à me contacter !',
            'email' => 'contact@bluereading.fr'
        ]
    ];
}
}

