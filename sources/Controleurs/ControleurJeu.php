<?php

namespace Blankoworld\Memory\Controleurs;

use Blankoworld\Memory\Modeles\GestionScores;

class ControleurJeu
{
    public function accueil()
    {
        // récupération des meilleurs scores
        $gestion_des_scores = new GestionScores();
        $scores = $gestion_des_scores->meilleursScores();

        // récupération du pseudonyme
        $pseudo_par_defaut = isset($_COOKIE['pseudonyme']) ? htmlspecialchars($_COOKIE['pseudonyme']) : 'votrePseudo';

        // Affichage du rendu en utilisant `Plates`
        $templates = new \League\Plates\Engine(__DIR__ . '/../Vues');
        echo $templates->render(
            'accueil', [
            'pseudo_par_defaut' => $pseudo_par_defaut,
            'scores' => $scores
            ]
        );
    }

    public function jeu($pseudonyme = null)
    {
        // Variables pour le gabarit
        $script_js = 'memory.js';

        /*
        Pseudonyme :
        - on utilise un cookie pour pré-remplir le champ la prochaine fois qu'on vient sur le site
        - on utilise la session pour transmettre la donnée entre les pages
        */
        if ($pseudonyme) {
            // Cookie : pour le formulaire d'index.php
            setcookie('pseudonyme', $pseudonyme, time() + (1 * 24 * 3600));
            // Session : pour les pages PHP suivantes
            $_SESSION['pseudonyme'] = $pseudonyme;
        } else {
            header('Location: /index.php');
            exit();
        }

        // Affichage du rendu en utilisant `Plates`
        $templates = new \League\Plates\Engine(__DIR__ . '/../Vues');
        echo $templates->render('jeu', ['script_js' => $script_js]);
    }

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
