<?php
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../models/MainManager.php';
    require_once __DIR__ . '/../views/v_header.php';
    $mainManager = new MainManager((new Database())->getConnection());
    $content = $mainManager->getAProposContent();
    
?>

<div class="container-fluid apropos-section">
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2 my-5">
            <div class="about-section text-center">
                <h1 class="mb-5">À propos de <span class="logo-blue">Blue</span><span class="logo-reading">Reading</span></h1>
                
                <div class="about-content">
                    <!-- Section Histoire -->
                    <div class="feature-block mb-5">
                        <i class="fas fa-book-reader mb-4" style="font-size: 3.5rem; color: #810e11;"></i>
                        <h3 class="mb-4"><?= $content['histoire']['titre'] ?></h3>
                        <p class="lead"><?= $content['histoire']['description'] ?></p>
                    </div>

                    <!-- Section Fonctionnalités -->
                    <div class="row features g-4 mb-5">
                        <?php foreach($content['fonctionnalites'] as $feature): ?>
                        <div class="col-md-6">
                            <div class="feature-card p-4 h-100">
                                <i class="<?= $feature['icone'] ?> mb-3" style="font-size: 2.5rem; color: #810e11;"></i>
                                <h4><?= $feature['titre'] ?></h4>
                                <p><?= $feature['description'] ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Section Contact -->
                    <div class="contact-section mb-5">
                        <h3 class="mb-4"><?= $content['contact']['titre'] ?></h3>
                        <p class="lead mb-4"><?= $content['contact']['description'] ?></p>
                        <div class="contact-info">
                            <p class="mb-4"><?= $content['contact']['invitation'] ?></p>
                            <a href="mailto:<?= $content['contact']['email'] ?>" class="btn btn-custom">
                                <i class="fas fa-paper-plane me-2"></i>Me Contacter
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    require_once __DIR__ . '/../views/v_footer.php';
?>
