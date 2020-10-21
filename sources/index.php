<?php

/*
Nous utiliserons les sessions pour transmettre certaines données d'une page à
l'autre.
On aurait pu utiliser les cookies ou transmettre les données dans l'URL.
*/
session_start(); // À toujours ajouter AVANT la moindre balise HTML.

$titre_page = 'Accueil';
/*
Quand l'utilisateur joue la première fois, on garde son pseudonyme dans un
cookie. Si c'est le cas, on réutilise son pseudonyme.
*/
$pseudo_par_defaut = isset($_COOKIE['pseudonyme']) ? htmlspecialchars($_COOKIE['pseudonyme']) : 'votrePseudo';

// Base de données : configuration de la connexion
$serveur = "db";
$utilisateur = "root";
$mot_de_passe = "oclock";
$base = "oclock";

// Autres valeurs utiles
$requete_derniers_scores = 'SELECT pseudo, TIMEDIFF(date_fin, date_debut) as diff FROM scores ORDER BY diff ASC';

// Connexion initiale et …
$connexion = new mysqli($serveur, $utilisateur, $mot_de_passe, $base);
// … interruption volontaire à la moindre erreur (histoire d'éviter d'afficher les données de connexion à un internaute
if ($connexion->connect_error) {
    die("Échec de connexion : " . $connexion->connect_error);
}

$contenu = '<section id="scores">';

// On affiche les 10 derniers scores
if ($reponse = $connexion->query($requete_derniers_scores)) {
    $contenu .= "\t\t<ol>";
    while ($score = $reponse->fetch_assoc()) {
        $contenu .= "\n\t\t\t<li>" . $score['pseudo'] . ' ' . $score['diff'] . '</li>';
    }
    $contenu .= "\n\t\t</ol>";
}
$contenu .= "</section>";

// Fermeture de la connexion à la base de données
$connexion->close();

// Préparation du contenu de la page (un formulaire pour insérer le pseudo)
$contenu .= <<<HTML
      <form method="post" action="jeu.php">
        <input type="text" name="pseudonyme" value="$pseudo_par_defaut"/>
        <input type="submit" value="Jouer&nbsp;!" />
      </form>
HTML;

require 'gabarits/principal.php';
