<?php

/**
 * Ensemble des contrôleurs de jeu pour l'application Memory.
 */

namespace Blankoworld\Memory\Controleurs;

use Blankoworld\Memory\Modeles\GestionScores;

/**
 * Cette classe va permettre de gérer les pages de l'application :
 * - l'accueil
 * - le jeu Memory
 * - la sauvegarde d'une partie
 */
class ControleurJeu
{
    /**
     * Affichage de la page d'accueil
     *
     * @return string Page d'accueil (HTML)
     */
    public function accueil()
    {
        // récupération des meilleurs scores
        $gestion_des_scores = new GestionScores();
        $scores = $gestion_des_scores->meilleursScores();

        // récupération du pseudonyme
        $pseudo_par_defaut = isset($_COOKIE['pseudonyme']) ? htmlspecialchars($_COOKIE['pseudonyme']) : 'votrePseudo';

        // Affichage du rendu en utilisant `Plates`
        $vue = new \League\Plates\Engine(__DIR__ . '/../Vues');
        echo $vue->render(
            'accueil',
            [
            'pseudo_par_defaut' => $pseudo_par_defaut,
            'scores' => $scores
            ]
        );
    }

    /**
     * Affichage de la page contenant le jeu de Memory
     *
     * @param string $pseudonyme Le pseudonyme du joueur
     *
     * @return string Page du jeu (HTML)
     */
    public function jeu($pseudonyme = null)
    {
        /*
        Pseudonyme : pour faciliter la saisie à la prochaine visite on garde
        l'information dans un cookie.
        En revanche on utilisera la session pour transmettre l'information à
        notre application.
        */
        if ($pseudonyme) {
            // Cookie : pour le formulaire d'index.php
            setcookie('pseudonyme', $pseudonyme, time() + (1 * 24 * 3600));
            // Session : pour les pages PHP suivantes
            $_SESSION['pseudonyme'] = $pseudonyme;
        } else {
            // Ceci est une manière de créer une redirection vers une autre URL
            header('Location: /index.php');
            exit();
        }

        // Variables pour la vue
        $script_js = 'memory.js';
        // Affichage du rendu en utilisant `Plates`
        $vue = new \League\Plates\Engine(__DIR__ . '/../Vues');
        echo $vue->render('jeu', ['script_js' => $script_js]);
    }

    /**
     * Sauve la partie en base de données
     *
     * @param string $pseudonyme Pseudonyme du joueur
     *
     * @param string $debut Date de début de la partie. Format YYYY-MM-SS H:i:s
     *
     * @param string $fin Date de fin de la partie. Format YYYY-MM-SS H:i:s
     *
     * @return void
     */
    public function sauvegardePartie($pseudonyme, $debut, $fin)
    {
        $gestion_des_scores = new GestionScores();
        $gestion_des_scores->ajouteScore(
            $pseudonyme,
            $debut,
            $fin
        );
    }
}
