<?php
require_once '../config/database.php';
require_once '../models/Livre.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation des données
    $required_fields = ['titre', 'auteur', 'nombre_pages', 'date', 'statut', 'genres'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $error .= "Le champ $field est requis.<br>";
        }
    }

    // Vérification du fichier
    if (!isset($_FILES['bookFile']) || $_FILES['bookFile']['error'] !== UPLOAD_ERR_OK) {
        $error .= 'Le fichier du livre est requis.<br>';
    } else {
        // Vérifiez le type de fichier
        $allowed_types = ['application/pdf', 'application/epub+zip', 'application/mobi'];
        if (!in_array($_FILES['bookFile']['type'], $allowed_types)) {
            $error .= 'Format de fichier non autorisé.<br>';
        }
    }

    // Si aucune erreur, ajoutez le livre
    if (empty($error)) {
        $livreData = [
            'titre' => $_POST['titre'],
            'auteur' => $_POST['auteur'],
            'pages' => $_POST['nombre_pages'],
            'genre' => $_POST['genres'],
            'date_publication' => $_POST['date'],
            'statut' => $_POST['statut'],
            'file' => $_FILES['bookFile'],
            'couverture' => null,
            'fichier_path' => null
        ];

        // Créez une instance de Livre
        $livre = new Livre((new Database())->getConnection());

        // Traitement des fichiers
        $livreData['fichier_path'] = handleFileUpload('bookFile', 'books', $allowed_types);
        if (isset($_FILES['coverImage']) && $_FILES['coverImage']['error'] === UPLOAD_ERR_OK) {
            $livreData['couverture_path'] = handleFileUpload('coverImage', 'covers', ['image/jpeg', 'image/png', 'image/gif']);
        } else {
            $livreData['couverture_path'] = null;
        }

        // Ajoutez le livre à la base de données
        $result = $livre->ajouterLivre($livreData);

        if ($result) {
            $success = "Livre ajouté avec succès.";
            // Redirection ou autre traitement après succès
            header("Location: ind?action=bibliotheque");
            exit();
        } else {
            $error = "Erreur lors de l'ajout du livre.";

            
        }
    }
}

// Fonction pour gérer le téléchargement de fichiers
function handleFileUpload($fileKey, $directory, $allowedTypes) {
    if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['size'] === 0) {
        return null;
    }

    if (!in_array($_FILES[$fileKey]['type'], $allowedTypes)) {
        throw new Exception("Format de fichier non autorisé pour $fileKey");
    }

    $upload_dir = "/BlueReading_v2/public/uploads/$directory/";
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_path = $upload_dir . uniqid() . '_' . basename($_FILES[$fileKey]['name']);
    move_uploaded_file($_FILES[$fileKey]['tmp_name'], $file_path);

    return $file_path;
}
?>
>
    </div>
</body>
</html>