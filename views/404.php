<?php
    require_once '../config/database.php';
    require_once '../models/MainManager.php';
    require_once '../views/v_header.php';
?>

<div class="container-fluid error-section">
    <div class="row min-vh-100 align-items-center">
        <div class="col-12 col-md-8 offset-md-2 text-center">
            <div class="error-content">
                <h1 class="display-1 mb-4">
                    <span class="logo-blue">4</span>
                    <span class="logo-reading">0</span>
                    <span class="logo-blue">4</span>
                </h1>
                
                
                    <h2 class="mb-4">Oups ! Page introuvable</h2>
                    <p class="lead">Il semble que vous vous soyez perdu dans notre bibliothèque...</p>
                

                <div class="error-image mb-5">
                    <i class="fas fa-book-reader" style="font-size: 6rem; color: #810e11;"></i>
                </div>

                <div class="error-actions">
                    <a href="#" onclick="handleReturnButton(event)" class="btn btn-custom me-3">
                        <i class="fas fa-home me-2"></i>
                        Retour à l'accueil
                    </a>
                    <a href="index.php?action=bibliotheque" class="btn btn-custom_secondary">
                        <i class="fas fa-book me-2"></i>
                        Voir mes livres
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../views/v_footer.php'; ?>