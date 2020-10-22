<?php

/**
 * Ceci est notre page principale, appelée à chaque fois pour une action.
 */

/*
PSR-4: chargement automatique de nos fichiers (Cf. README.md)
*/
require_once __DIR__ . '/vendor/autoload.php';

// Démarrage de la session pour garder les données de la partie
session_start();

// Import des bibliothèques que nous allons utiliser (grâce à l'autoloading)
use Blankoworld\Memory\Controleurs\ControleurJeu;

// On démarre le contrôleur principal de l'application (le seul dans ce cas)
$controleur = new ControleurJeu();

/*
Le paramètre `action` définit le chemin à emprunter. C'est du routing (
    redirection en fonction de l'URL).
On va donc appeler des contrôleurs qui s'occuperont de choisir les actions à
mener et les - éventuelles - vues à afficher. (Cf. README.md au sujet du
modèle MVC).
*/
if (isset($_GET['action'])) {
    // Pour chaque cas nous avons une action précise.
    switch (htmlspecialchars($_GET['action'])) {
        // Ici c'est la page contenant le jeu
        case 'jeu':
            $pseudonyme = null;
            if (isset($_POST['pseudonyme'])) {
                $pseudonyme = htmlspecialchars($_POST['pseudonyme']);
            }
            $controleur->jeu($pseudonyme);
            break;

        // La sauvegarde est un support pour enregistrer les données
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

        // Action par défaut quand le mot n'a pas d'action spécifique
        default:
            $controleur->accueil();
            break;
    }
} else {
    // Pas d'action dans l'URL ? On affiche quand même la page d'accueil :-)
    $controleur->accueil();
}
