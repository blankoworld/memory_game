<!DOCTYPE html>
<html>
<head>
    <!-- Ceci est un commentaire HTML.
        Nous l'utiliserons pour détailler notre code HTML. -->
    <title>Memory
<?= $title = isset($titre_page) ? ' - ' . $titre_page : ''; ?></title>

    <!-- Feuille de style à utiliser pour la mise en forme -->
    <link rel="stylesheet" href="memory.css">

<?php
if (isset($script_js)) {
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
    <script src="<?php echo $script_js ?>" defer></script>
    <?php
}
?>

</head>
<body>

    <!-- Les sections permettent, comme leur nom l'indique de découper la page
        en plusieurs espace spécifiques. L'espace qui suit sera l'entête. -->
    <section id="entete">
        <h1>Jeu de memory</h1>
        <p>Ceci <strong>devient</strong> un jeu de Memory.</p>
    </section>

    <!-- Notre section principale de travail : pour le tableau des scores, ou
        encore pour le plateau de jeu.
        Notre code Javascript ajoutera les cartes de jeu. -->
    <section id="principale">
        <?= $contenu ?>
    </section>

<?php
if ($affiche_chronometre) {
    ?>
    <!-- Progression de la partie -->
    <section id="partie">
        <p id="chrono"></p>
    </section>
    <?php
}
?>
    </body>
</html>