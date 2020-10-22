<?php

/**
 * Ensemble des modèles utilisés pour le jeu de l'application Memory
 */

namespace Blankoworld\Memory\Modeles;

use mysqli;

/**
 * Classe de gestion des scores : affichage et enregistrement dans la base.
 */
class GestionScores
{
    /**
     * Les 10 meilleurs scores
     *
     * @return array Scores ayant 2 champs : pseudo et diff (durée de partie)
     */
    public function meilleursScores()
    {
        $meilleurs_scores = array();

        $connexion = $this->connexionBDD();
        $requete_sql = <<<SQL
SELECT pseudo, TIMEDIFF(date_fin, date_debut) as diff
FROM scores
ORDER BY diff ASC
SQL;
        $resultat = $connexion->query($requete_sql);
        if ($resultat) {
            while ($score = $resultat->fetch_assoc()) {
                $meilleurs_scores[] = $score;
            }
        }
        $connexion->close();
        return $meilleurs_scores;
    }

    /**
     * Ajoute un score dans le tableau des scores
     *
     * @param string $pseudo Pseudonyme du joueur
     *
     * @param string $debut Date de début du jeu. Format YYYY-MM-SS H:i:s
     *
     * @param string $fin Date de fin du jeu. Format YYYY-MM-SS H:i:s
     *
     * @return bool Résultat de la requête SQL
     */
    public function ajouteScore($pseudo, $debut, $fin)
    {
        $requete_sql = <<<SQL
INSERT INTO scores(pseudo, date_debut, date_fin)
VALUES (?, ?, ?)
SQL;

        // connexion à la base de données, puis préparation d'une requête
        $connexion = $this->connexionBDD();
        $requete_preparee = $connexion->prepare($requete_sql);
        // on complète la requête et on l'exécute
        $requete_preparee->bind_param("sss", $pseudo, $debut, $fin);
        $resultat = $requete_preparee->execute();
        // Arrêt de l'utilisation de la requête préparée et fermeture de la BDD
        $requete_preparee->close();
        $connexion->close();
        return $resultat;
    }

    /**
     * Connexion à la base de données
     *
     * @return Object Objet mysqli de la connexion
     */
    private function connexionBDD()
    {
        // Données de connexion
        $serveur = "db";
        $utilisateur = "root";
        $mot_de_passe = "oclock";
        $base = "oclock";
        // Connexion initiale et …
        $connexion = new mysqli($serveur, $utilisateur, $mot_de_passe, $base);
        // … interruption volontaire si erreur
        if ($connexion->connect_error) {
            die("Échec de connexion : " . $connexion->connect_error);
        }
        // On retourne l'objet de connexion
        return $connexion;
    }
}
