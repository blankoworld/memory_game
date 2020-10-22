<?php

/*
PSR-4: chargement automatique de nos fichiers (Cf. autoloader dans
composer.yml)
*/
require_once __DIR__ . '/vendor/autoload.php';

// Démarrage de la session
session_start();

// Import des bibliothèques que nous allons utiliser
use Blankoworld\Memory\Controleurs\ControleurJeu;

// Initialisation de notre page
$controleur = new ControleurJeu();

// Choix d'une page plutôt qu'une autre
if (isset($_GET['action'])) {
    switch (htmlspecialchars($_GET['action'])) {
        case 'jeu':
            $pseudonyme = null;
            if (isset($_POST['pseudonyme'])) {
                $pseudonyme = htmlspecialchars($_POST['pseudonyme']);
            }
            $controleur->jeu($pseudonyme);
            break;

        case 'sauvegarde':
            if (
                isset($_POST['date_fin']) &&
                isset($_POST['date_debut']) &&
                    isset($_SESSION['pseudonyme'])
            ) {
                $controleur->sauvegardePartie(
                    $_SESSION['pseudonyme'],
                    htmlspecialchars($_POST['date_debut']),
                    htmlspecialchars($_POST['date_fin'])
                );
            } else {
                header('Location: /index.php');
            }
            break;

        default:
            $controleur->accueil();
            break;
    }
} else {
    $controleur->accueil();
}
