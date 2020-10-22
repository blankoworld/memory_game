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

        // choix d'un titre pour la page
        $titre_page = 'Accueil';
        // récupération du pseudonyme
        $pseudo_par_defaut = isset($_COOKIE['pseudonyme']) ? htmlspecialchars($_COOKIE['pseudonyme']) : 'votrePseudo';

        // création du contenu
        $contenu = '<section id="scores">';
        // On affiche les 10 derniers scores
        if ($scores) {
            $contenu .= "\t\t<ol>";
            foreach ($scores as $score) {
                $contenu .= "\n\t\t\t<li>" . $score['pseudo'] . ' ' . $score['diff'] . '</li>';
            }
            $contenu .= "\n\t\t</ol>";
        }
        $contenu .= "</section>";
        // Préparation du contenu de la page (un formulaire pour insérer le pseudo)
        $contenu .= <<<HTML
            <form method="post" action="index.php?action=jeu">
                <input type="text" name="pseudonyme" value="$pseudo_par_defaut"/>
                <input type="submit" value="Jouer&nbsp;!" />
            </form>
        HTML;

        require(__DIR__ . '/../Vues/principal.php');
    }

    public function jeu($pseudonyme = null)
    {
        // Variables pour le gabarit
        $titre_page = 'Le jeu';
        $script_js = 'memory.js';
        $affiche_chronometre = true;

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

        require(__DIR__ . '/../Vues/principal.php');
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
