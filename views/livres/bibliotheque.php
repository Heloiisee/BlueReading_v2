<?php require_once __DIR__ . '/../v_header.php'; 
?>

<div class="container-fluid bibliotheque-section">
    <div class="row">
        <div class="col-12 col-md-10 offset-md-1 my-5">
            <div class="bibliotheque-header text-center mb-5">
                <h1>Ma <span>Bibliothèque</span></h1>
                <p class="lead mb-5">Retrouvez tous vos livres enregistrés</p>
            </div>

            <!-- Filtres -->
            <div class="bibliotheque-content mb-5">
                <div class="filters mb-4">
                    <div class="row g-3 justify-content-center">
                        <div class="col-md-3">
                            <select class="form-select" id="filterStatus">
                                <option value="all">Tous les statuts</option>
                                <option value="reading">En cours</option>
                                <option value="finished">Terminé</option>
                                <option value="toRead">À lire</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filterGenre">
                                <option value="all">Tous les genres</option>
                                <option value="roman">Roman</option>
                                <option value="fantasy">Fantasy</option>
                                <option value="thriller">Thriller</option>
                                <option value="sf">Science-Fiction</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group" id="search-group">
                                <input type="text" class="form-control" placeholder="Rechercher un livre..." id="searchInput">
                                <button class="btn btn-custom" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des livres -->
            <div class="books-grid" id="liste-livres">
                <div class="row g-4" id="booksContainer">
                    <?php if (!empty($livres)): ?>
                        <?php foreach ($livres as $livre): ?>
                            <div class="col-12 col-md-6 col-lg-4 book-item">
                                <div class="card h-100 book-card">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="book-cover-wrapper me-3">
                                                <?php if (!empty($livre['couverture'])): ?>
                                                    <img src="<?php echo htmlspecialchars($livre['couverture']); ?>" 
                                                        alt="Couverture de <?php echo htmlspecialchars($livre['titre']); ?>" 
                                                        class="book-cover">
                                                <?php endif; ?>
                                            </div>
                                            <div class="book-info flex-grow-1">
                                                <h5 class="book-title fw-bold mb-2">
                                                    <?php echo htmlspecialchars($livre['titre']); ?>
                                                </h5>
                                                <p class="book-author text-muted mb-2"><?php echo htmlspecialchars($livre['auteur']); ?></p>
                                                <div class="book-tags mb-3">
                                                    <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($livre['genre']); ?></span>
                                                    <span class="badge bg-success rounded-pill"><?php echo htmlspecialchars($livre['statut_lecture'] ?? 'Non défini'); ?></span>
                                                </div>
                                                <div class="book-actions">
                                                    <a href="index.php?action=modifier&id=<?php echo $livre['id']; ?>" class="btn btn-sm btn-outline-primary me-2">
                                                        <i class="fas fa-edit me-1"></i>Modifier
                                                    </a>
                                                    <form action="index.php?action=supprimerLivre" method="POST" style="display:inline;">
                                                        <input type="hidden" name="id" value="<?php echo $livre['id']; ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?');">
                                                            <i class="fas fa-trash-alt me-1"></i>Supprimer
                                                        </button>
                                                    </form>
                                                    <a href="index.php?action=lireLivre&id=<?php echo $livre['id']; ?>" class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-eye me-1"></i>Voir
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center no-books p-5 bg-light rounded">
                            <i class="fas fa-book-open mb-4" style="font-size: 4rem; color: #810e11;"></i>
                            <p class="lead mb-4">Votre bibliothèque est vide pour le moment</p>
                            <a href="?action=ajouter" class="btn btn-custom">
                                <i class="fas fa-plus-circle me-2"></i>Ajouter un livre
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../v_footer.php'; ?>
