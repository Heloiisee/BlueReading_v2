<?php
    // views/v_accueil.php
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../models/MainManager.php';
    require_once __DIR__ . '/../views/v_header.php';
    
?>

<section class="header-section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-8 offset-md-2">
                <div class="text-center">
                    <h1 class="header-title mb-4">
                        Bienvenue sur<br>
                        <span class="logo-blue">Blue</span><span class="logo-reading">Reading</span>
                    </h1>
                    <p class="header-subtitle mb-5">Suivez vos lectures facilement avec BlueReading</p>
                    
                    <!-- Affichage des statistiques -->
                    <div class="stats-container mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="stat-box">
                                    <i class="fas fa-book"></i>
                                    <h3><?= htmlspecialchars($stats['total_livres'] ?? '0') ?></h3>
                                    <p>Livres dans la bibliothèque</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-box">
                                    <i class="fas fa-tags"></i>
                                    <h3><?= htmlspecialchars($stats['total_etiquettes'] ?? '0') ?></h3>
                                    <p>Étiquettes créées</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-box">
                                    <i class="fas fa-book"></i>
                                    <h3><?= htmlspecialchars($stats['derniers_livres'] ?? '0') ?></h3>
                                    <p>Derniers livres ajoutés</p>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="header-button">
                        <a href="?action=ajouter" class="btn btn-custom">
                            <i class="fas fa-plus-circle me-2"></i>
                            Ajouter un livre
                        </a>
                        <a href="?action=bibliotheque" class="btn btn-custom_secondary">
                            <i class="fas fa-book me-2"></i>
                            Voir les livres
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
    require_once __DIR__ . '/../views/v_footer.php';
?>