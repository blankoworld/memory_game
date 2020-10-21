<?php

/*
Nous utiliserons les sessions pour transmettre certaines données d'une page à
l'autre.
On aurait pu utiliser les cookies ou transmettre les données dans l'URL.
*/
session_start(); // À toujours ajouter AVANT la moindre balise HTML.

$titre_page = 'Accueil'; // TODO : se débrouiller autrement pour les templates

/*
Grâce à `include` nous pouvons utiliser des gabarits (templates) communs à nos
pages. Cela réduit la redondance du code HTML et permet une maintenance plus
aisée des sites webs.
*/
include('gabarits/entete.php');     // écriture du doctype/html et <head>
include('gabarits/entete_fin.php'); // écriture du </head><body>
include('gabarits/titre.php');      // écriture du titre de la page

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
  die("Connection failed: " . $connexion->connect_error);
}
?>

    <section id="scores">
<?php
// On affiche les 10 derniers scores
if ($reponse = $connexion->query($requete_derniers_scores)) {
  echo "\t\t<ol>";
  while ($score = $reponse->fetch_assoc()) {
    echo "\n\t\t\t<li>" . $score['pseudo'] . ' ' . $score['diff'] . '</li>';
  }
  echo "\n\t\t</ol>";
}
?>

    </section>

<?php
/*
Quand l'utilisateur joue la première fois, on garde son pseudonyme dans un
cookie. Si c'est le cas, on réutilise son pseudonyme.
*/
$pseudo_par_defaut = isset($_COOKIE['pseudonyme']) ? htmlspecialchars($_COOKIE['pseudonyme']) : 'votrePseudo';
?>

    <section id="principale">
      <form method="post" action="jeu.php">
        <input type="text" name="pseudonyme" value="<?php echo $pseudo_par_defaut ?>"/>
        <input type="submit" value="Jouer&nbsp;!" />
      </form>
    </section>

<?php
// Fermeture de la connexion à la base de données
$connexion->close();

// Fermeture des balises du code HTML
include('gabarits/enqueue.php'); 
?>