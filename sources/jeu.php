<?php

session_start(); // Rappelez-vous la session d'index.php ;-)


/*
Pseudonyme :
- on utilise un cookie pour pré-remplir le champ la prochaine fois qu'on vient sur le site
- on utilise la session pour transmettre la donnée entre les pages
*/
if (isset($_POST['pseudonyme'])) {
    $pseudo = htmlspecialchars($_POST['pseudonyme']);
    // Cookie : pour le formulaire d'index.php
    setcookie('pseudonyme', $pseudo, time() + (1 * 24 * 3600));
    // Session : pour les pages PHP suivantes
    $_SESSION['pseudonyme'] = $pseudo;
}

// Variables utiles pour le gabarit principal
$titre_page = 'Le jeu';
$script_js = 'memory.js';
$affiche_chronometre = true;

require('gabarits/principal.php');
