<?php $this->layout('principal', ['title' => 'Accueil']) ?>

<?php $this->start('contenu') ?>
    <section id="scores">
        <ol>
            <?php foreach ($scores as $score) : ?>
                <li>
                    <?= $this->e($score['pseudo']) . ' ' . $this->e($score['diff']) ?>
                </li>
            <?php endforeach ?>
        </ol>
    </section>
    <form method="post" action="index.php?action=jeu">
        <input type="text" name="pseudonyme" value="<?= $this->e($pseudo_par_defaut) ?>"/>
        <input type="submit" value="Jouer&nbsp;!" />
    </form>
<?php $this->stop() ?>