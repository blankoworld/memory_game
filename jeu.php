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
	setcookie('pseudonyme', $pseudo, time() + (1*24*3600));
	// Session : pour les pages PHP suivantes
	$_SESSION['pseudonyme'] = $pseudo;
}

// Début du HTML
$titre_page = 'Le jeu';
include('gabarits/entete.php');

?>

	<!-- Utilisation d'un site externe pour inclure Jquery.
		Notez le `defer` qui indique de charger tout ça à la fin du chargement
		complet de la page. -->
	<script
		src="https://code.jquery.com/jquery-3.5.1.min.js"
		integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
		crossorigin="anonymous" defer></script>
	<!-- Puis nous utilisons notre propre fichier JS pour le code de 
		l'application -->
	<script src="memory.js" defer></script>
</head>
<body>

<?php include('gabarits/titre.php'); ?>

	<!-- Notre section principale de travail : pour le tableau des scores, ou
		encore pour le plateau de jeu.
		Notre code Javascript ajoutera les cartes de jeu. -->
	<section id="principale">
	</section>

	<!-- Progression de la partie -->
	<section id="partie">
		<p id="chrono"></p>
	</section>

<?php include('gabarits/enqueue.php'); ?>