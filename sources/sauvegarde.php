<?php

// Pour récupérer le pseudonyme
session_start();

// Enregistrement des données envoyées par un POST
if (isset($_POST['date_fin']) && isset($_POST['date_debut']) && isset($_SESSION['pseudonyme'])) {

    // Accès à la base de données
    $serveur = "db";
    $utilisateur = "root";
    $mot_de_passe = "oclock";
    $base = "oclock";

    // Connexion à la base de données
    $connexion = new mysqli($serveur, $utilisateur, $mot_de_passe, $base);

    if ($connexion->connect_error) {
        die("Connection failed: " . $connexion->connect_error);
    }

    // Création d'une requête préparée
    $requete_inserer_score = $connexion->prepare('INSERT INTO scores(pseudo, date_debut, date_fin) VALUES (?, ?, ?)');


    // Couplage des paramètres avec les valeurs à affecter
    $requete_inserer_score->bind_param("sss", $pseudonyme, $date_debut, $date_fin);

    // Valeur à enregistrer
    $date_debut = htmlspecialchars($_POST['date_debut']);
    $date_fin = htmlspecialchars($_POST['date_fin']);
    $pseudonyme = $_SESSION['pseudonyme'];

    // Exécution de la requête préparée
    $requete_inserer_score->execute();

    // Fermeture
    $requete_inserer_score->close();

    // Fermeture de la connexion
$connexion->close();

}
else {
    error_log("ELSE ELSE ELSE", 0);
    header('Location: /index.php');
    exit();
}
?>