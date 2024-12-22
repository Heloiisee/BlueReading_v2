<?php
require_once __DIR__ . '/../v_header.php';

?>
<?php
// Initialisation des variables
$error = isset($error) ? $error : '';
$success = isset($success) ? $success : '';
?>

<div class="container-fluid" id="ajout-livre">
                <div class="row">
                    <div class="col-12 col-md-8 offset-md-2">
                        <div class="d-flex flex-column align-items-center mb-4">
                            <a href="javascript:history.back()" class="btn btn-custom_secondary align-self-start mb-3">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                            <h1 class="text-center">Ajouter un <span>livre</span></h1>
                        </div>

                        <?php if($error): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                        <?php endif; ?>

                        <?php if($success): ?>
                        <div class="alert alert-success">
                            Le livre a été ajouté avec succès !
                        </div>
                        <?php endif; ?>

                        <form class="bg-white p-4 rounded shadow-sm" id="bookForm" action="traitement-livre.php" method="POST" enctype="multipart/form-data">
                            <div id="firstStep">
                                <div class="form-group mb-4">
                                    <label for="titre" class="form-label">Titre du Livre <span class="required">*</span></label>
                                    <input type="text" class="form-control border-0 bg-light" id="titre" name="titre" required>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="auteur" class="form-label">Auteur <span class="required">*</span></label>
                                    <input type="text" class="form-control border-0 bg-light" id="auteur" name="auteur" required>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="pages" class="form-label">Nombre de pages <span class="required">*</span></label>
                                    <input type="number" class="form-control border-0 bg-light" id="pages" name="pages" required>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="genre" class="form-label">Genre <span class="required">*</span></label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="genre1" name="genres[]" value="Roman">
                                        <label class="form-check-label" for="genre1">Roman</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="genre2" name="genres[]" value="Science-Fiction">
                                        <label class="form-check-label" for="genre2">Science-Fiction</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="genre3" name="genres[]" value="Fantastique">
                                        <label class="form-check-label" for="genre3">Fantastique</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="genre4" name="genres[]" value="Policier">
                                        <label class="form-check-label" for="genre4">Policier</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="genre5" name="genres[]" value="Thriller">
                                        <label class="form-check-label" for="genre5">Thriller</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="genre6" name="genres[]" value="Biographie">
                                        <label class="form-check-label" for="genre6">Biographie</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="genre7" name="genres[]" value="Histoire">
                                        <label class="form-check-label" for="genre7">Histoire</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="genre8" name="genres[]" value="Jeunesse">
                                        <label class="form-check-label" for="genre8">Jeunesse</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="genre9" name="genres[]" value="Autre">
                                        <label class="form-check-label" for="genre9">Autre</label>
                                    </div>
                                    <style>
                                        .form-check {
                                            display: flex;
                                            align-items: center;
                                            margin-bottom: 0.5rem;
                                        }
                                    </style>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="date" class="form-label">Date de publication <span class="required">*</span></label>
                                    <input type="date" class="form-control border-0 bg-light" id="date" name="date" required>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="statut" class="form-label">Statut de lecture <span class="required">*</span></label>
                                    <select class="form-select border-0 bg-light" id="statut" name="statut" required>
                                        <option value="">Sélectionner un statut</option>
                                        <option value="À lire">À lire</option>
                                        <option value="En cours">En cours</option>
                                        <option value="Lu">Lu</option>
                                    </select>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="etiquettes" class="form-label">Étiquettes</label>
                                    <input type="text" class="form-control border-0 bg-light" id="etiquettes" name="etiquettes" placeholder="Séparez les étiquettes par des virgules">
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-custom px-4" onclick="toggleFormVisibility(true)">Suivant</button>
                                </div>
                            </div>

                            <div id="fileUploadForm" class="d-none">
                                <h4 class="mb-4">Fichiers du livre</h4>
                                <div class="mb-3">
                                    <label for="coverImage" class="form-label">Couverture du livre</label>
                                    <input type="file" class="form-control" id="coverImage" name="coverImage" accept="image/*">
                                </div>
                                <div class="mb-3">
                                    <label for="bookFile" class="form-label">Fichier du livre <span class="required">*</span></label>
                                    <input type="file" class="form-control" id="bookFile" name="bookFile" accept=".pdf,.epub" required>
                                    <small class="text-muted">Formats acceptés : PDF, EPUB</small>
                                </div>
                                <div class="d-flex gap-3 justify-content-end">
                                    <button type="button" class="btn btn-custom_secondary px-4" onclick="toggleFormVisibility(false)">Retour</button>
                                    <button type="submit" class="btn btn-custom px-4">Soumettre</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
require_once __DIR__ . '/../v_footer.php';
?>