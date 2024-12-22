<?php
    // views/v_header.php
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/BlueReading_v2/public/css/styles.css">    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="icon" href="/BlueReading_v2/public/images/favicon.ico"> 
    <title>BlueReading</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <header>
                <nav class="navbar navbar-expand-lg">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="./">
                            <span class="logo-blue">Blue</span><span class="logo-reading">Reading</span>
                        </a>
                        <button class="navbar-toggler ms-auto border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        
                        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                            <ul class="navbar-nav gap-4">
                                <li class="nav-item">
                                    <a class="nav-link" aria-current="page" href="./">
                                        <i class="fas fa-home me-2"></i>Accueil
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " href="?action=ajouter">
                                        <i class="fas fa-plus-circle me-2"></i>Ajouter un livre
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " href="?action=bibliotheque">
                                        <i class="fas fa-book me-2"></i>Mes livres
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " href="?action=apropos">
                                        <i class="fas fa-info-circle me-2"></i>A propos
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>
        </div>
    </div>
