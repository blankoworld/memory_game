<!DOCTYPE html>
<html>
<head>
    <!-- Ceci est un commentaire HTML.
        Nous l'utiliserons pour détailler notre code HTML. -->
    <title>Memory<?= $this->e($titre_page) ?></title>

    <!-- Feuille de style à utiliser pour la mise en forme -->
    <link rel="stylesheet" href="memory.css">

<?php if ($this->section('script_js')) : ?>
    <?= $this->section('script_js') ?>
<?php endif ?>
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
<?php if ($this->section('contenu')) : ?>
    <?= $this->section('contenu') ?>
<?php endif ?>
    </section>

<?php if ($this->section('chronometre')) : ?>
    <?= $this->section('chronometre') ?>
<?php endif ?>

    </body>
</html>