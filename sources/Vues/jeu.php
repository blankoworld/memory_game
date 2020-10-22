<?php $this->layout('principal', ['title' => 'Le jeu']) ?>

<?php $this->start('script_js') ?>
    <!-- Utilisation d'un site externe pour inclure Jquery.
        Notez le `defer` qui indique de charger tout ça à la fin du chargement
        complet de la page. -->
        <script
        src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
        crossorigin="anonymous" defer></script>
    <!-- Puis nous utilisons notre propre fichier JS pour le code de 
        l'application -->
    <script src="<?= $this->e($script_js) ?>" defer></script>
<?php $this->stop() ?>

<?php $this->start('chronometre') ?>
    <!-- Progression de la partie -->
    <section id="partie">
        <p id="chrono"></p>
    </section>
<?php $this->stop() ?>