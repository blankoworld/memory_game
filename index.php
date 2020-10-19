<?php

// Base de données : configuration de la connexion
$serveur = "db";
$utilisateur = "root";
$mot_de_passe = "oclock";
$base = "oclock";

// Autres valeurs utiles
$requete_derniers_scores = 'SELECT * FROM scores ORDER BY id DESC LIMIT 10';

// Connexion initiale et …
$connexion = new mysqli($serveur, $utilisateur, $mot_de_passe, $base);
// … interruption volontaire à la moindre erreur (histoire d'éviter d'afficher les données de connexion à un internaute
if ($connexion->connect_error) {
  die("Connection failed: " . $connexion->connect_error);
}

// On affiche les 10 derniers scores
if ($reponse = $connexion->query($requete_derniers_scores)) {
  while ($score = $reponse->fetch_assoc()) {
    echo $score['pseudo'] . ' ' . $score['date_debut'] . '<br />';
  }
}

$connexion->close();

?>
