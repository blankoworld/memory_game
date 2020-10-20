// Ceci est un commentaire JS.
/*
Les commentaires peuvent faire plusieurs lignes en utilisant une autre
méthode.
*/

/*
Ce script, grâce au mot clé `defer` dans le HTML, est exécuté à la fin
du chargement du HTML complet. L'impact du code ici présent est donc
immédiat. Nul besoin d'utiliser une quelconque fonction de Javascript.
*/

// Quelques variables utiles pour configurer notre application
const nbreCases = 28;           // nombre de cases du plateau de jeu
const maxImages = 18;           // nombre maximum d'images dont nous disposons
const delaiAffichage = 1.5;     // secondes d'affichage des cartes
const faceInitiale = "verso";   // `recto` ou `verso`
const dureePartie = 3;       // durée, en minutes, de la partie

// TODO: Erreur si nbreCases modulo 2 renvoie 0 => signifie nombre pair.
// TODO: Erreur si nbreCases supérieur à 18*2 (maxImages)

// Variables disponibles à toute l'application et permettant son fonctionnement
var nbreDouble = nbreCases / 2; // nombre de doubles à avoir.
var plateau = [];               // numéro des fruits utilisés pour le plateau
var pioche = [];                // cartes piochées par le joueur
var nbreReussites = 0;          // nombre de doubles trouvés par le joueur
var deroulementPartie = null;   // outil de rafraîchissement de la prograssion
var alerteFin = null;           // indicateur lorsque la partie est terminée
var scoresPartie = [];          // Garder en mémoire les infos de la partie

// Fonctions utiles
/* 
melanger : Mélange un tableau suivant la méthode de Knuth-Fisher-Yates.
Cf. https://poopcode.com/shuffle-randomize-an-array-in-javascript-using-knuth-fisher-yates-shuffle-algorithm/
*/
function melanger(tableau) {
    var m = tableau.length, temp, i;

    // Tant qu'il y a des éléments au tableau
    while (m) {
        // Choisi parmi les éléments restants
        i = Math.floor(Math.random() * m--);

        // Échange avec l'élément courant
        temp = tableau[m];
        tableau[m] = tableau[i];
        tableau[i] = temp;
    }

    // renvoi du tableau résultant
    return tableau;
}

/*
transformeDate : transforme les dates en un format compatible avec notre application
*/
function transformeDate(laDate) {
    return laDate.toISOString().substring(0, 19).replace('T', ' ');
}

/*
generePlateau : Génère un tableau contenant le numéro des cartes à afficher
sur le plateau de jeu.
*/
function generePlateau() {
    /*
    Le jeu étant un jeu de Memory utilisant les doubles, ça fait 14 cartes
    aléatoires à choisir.
    Cependant nous avons à disposition 18 images. Il faut donc choisir 14
    images parmi les 18.
    Puis les disposer sur le plateau aléatoirement, et deux fois chaque image.

    Idéalement on souhaiterait un tableau contenant les numéros des cartes à
    disposer sur le plateau de jeu.

    Plusieurs méthodes sont possibles.

    Voici la méthode choisie : 
    - on génère un tableau des chiffres de 0 à 17 (18 chiffres), c'est à dire
    le maximum de cartes dont nous disposons
    - on mélange ce tableau
    - on prend les 14 premiers chiffres (c'est à dire la moitié des cases du
    plateau)
    - on duplique chacun des chiffres obtenus : on obtient 28 chiffres dont
    14 doublons
    - on mélange à nouveau le tableau obtenu
    */

    // Initialisation des tableaux nécessaires
    let chiffres = [];
    let retenus = [];
    let resultat = [];
    for(let iterateur = 0; iterateur < maxImages; iterateur++) {
        /* 
        L'itérateur va de 0 à 17. Mais nos images, dans le CSS, sont nommées
        de 1 à 18.
        Il est donc nécessaire de penser à augmenter de 1 le chiffre obtenu.
        */
        let numeroImage = iterateur + 1;
        chiffres.push(numeroImage); // on ajoute le chiffre au tableau
    }

    // On mélange le tableau en utilisant la fonction `melanger`.
    chiffres = melanger(chiffres);
    
    // On affiche dans la console du navigateur Web le résultat
    console.log("Cartes disponibles, en pagaille : ", chiffres);

    // On utilise seulement les 14 premières.
    retenus = chiffres.slice(0, nbreDouble);
    
    // Avons-nous juste ? On affiche le résultat
    console.log("14 cartes choisies : ", retenus)

    // On double le tableau avant de mélanger
    /*
        La fonction concat permet de « concatener » des éléments, c'est à
        dire d'ajouter un à plusieurs éléments après un autre.
    */
    resultat = retenus.concat(retenus);

    // On retourne le résultat mélangé
    return melanger(resultat);
}

/*
retourner : change les propriétés de la carte pour qu'elle change recto/verso
*/
function retourner(caseId) {
    console.log("Doit retourner : ", caseId);
    let element = $("article#" + caseId);
    let image = "recto" + plateau[caseId];
    if (element.hasClass("recto")) {
        element.removeClass("recto " + image);
        element.addClass("verso");
    } else {
        element.removeClass("verso");
        element.addClass("recto " + image);
    }
}

/*
cartesEgales : compare les cartes de la pioche.
- si les cartes sont identiques on les laisse affichées (aucun traitement)
- si différentes on retourne à nouveau les cartes
*/
function cartesEgales() {
    /*
    On a seulement le numéro des cases comme référence (1 à 28).
    Le numéro du fruit attaché se trouve dans le tableau `plateau`, à la même
    place que le numéro de la case.
    Par exemple la case 0 a le fruit `plateau[0]`.
    */
    let case1 = pioche[0];
    let case2 = pioche[1];
    let fruit1 = plateau[case1];
    let fruit2 = plateau[case2];
    if (fruit1 != fruit2) {
        return false;
    } else {
        return true;
    }
}

/*
desactiverCarte : enlève l'événement `onclick` de la case donnée.
*/
function desactiverCarte(caseId) {
    let element = $("article#" + caseId);
    element.off("click", jouer);
}

/*
finPartie : Actions à entreprendre quand la partie se termine : 
- désactivation de toutes les cartes (suppression du onClick)
- arrêt du chronomètre
- message pour alerter de la fin de partie (si gagné ou perdu)
*/
function finPartie(message) {
    // Désactivation de toutes les cartes
    for(let iterateur = 0; iterateur < plateau.length; iterateur++) {
        desactiverCarte(iterateur);
    };
    // Suppression de l'intervalle de rafraîchissement de la progression
    clearInterval(deroulementPartie);
    // Affichage des scores en console pour utilisation ultérieure
    console.log("Scores partie : ", scoresPartie);
    // Information à l'utilisateur
    alert(message);
    // On revient à la page d'accueil
    window.location.replace('/index.php');
}

/*
envoyerScores : Appel la page sauvegarde.php pour sauver les données
On utilise un appel AJAX vers la page de sauvegarde.
*/
function envoyerScores(dates) {
    var donnees_envoyees = {
        date_debut: transformeDate(dates[0]),
        date_fin: transformeDate(dates[1]), // garder un format spécifique
    }
    $.ajax({
        type: "POST",
        url: "sauvegarde.php",
        data: donnees_envoyees,
        success: function(result) {
            console.log("Retour requête AJAX : " + result);
        }
    });
}
/*
jouer : actions entreprises après que le joueur ait choisi une carte.
Chaque tour il choisit une carte, une deuxième puis on compare.
Si une carte est jouée, nous ne faisons rien de plus.
Si deux cartes sont jouées : on compare les deux.
*/
function jouer(event) {
    // Sélection de l'identifiant de la carte (numéro de la case du plateau)
    let numeroCase = event.target.id;
    console.log("Case choisie : ", numeroCase);

    // On ajoute la carte à la pioche (pour comparaison ultérieure)
    pioche.push(numeroCase);

    // On retourne la carte
    retourner(numeroCase);

    // On compare seulement si nous avons 2 cartes.
    if (pioche.length > 1) {
        // Pour laisser l'utilisateur voir la seconde carte avant de comparer.
        if (cartesEgales()) {
            nbreReussites++;
            if (nbreReussites >= nbreDouble) {
                // Ajout du temps de fin de partie au tableau des scores
                scoresPartie.push(new Date());
                // Arrêter le setTimeout de fin de partie
                clearTimeout(alerteFin);
                // Envoi des scores à la base de données via une requête POST
                envoyerScores(scoresPartie);
                // La partie se termine par une victoire !
                finPartie("Vous avez GAGN\xC9 !");
            }
            pioche.forEach(caseId => desactiverCarte(caseId));
        } else {
            /* Échec du tour de jeu : on laisse les cartes visibles quelques
            secondes, puis on les retourne.
            */
            let attente = delaiAffichage * 1000;
            setTimeout(retourner, attente, pioche[0]);
            setTimeout(retourner, attente, pioche[1]);
        };
        // La comparaison terminée, on réinitialise la pioche.
        pioche = [];
    }
}

/*
afficheChronometre : affiche le chronomètre de la partie.
*/
function afficheChronometre() {
    // affichage initial du chronomètre
    let progression = $("<progress>", {
        "class": "barreProgression",
        max: 100,
        value: 0,
    });
    progression.appendTo("section#partie");
}

/*
augmenteCompteur : augmente le compteur de 1 à 1
*/
var augmenteCompteur = (function() {
    let compteur = 0;
    return function() { compteur += 1; return compteur; };
})();

/*
demarreChronometre : initialise le chronomètre pour la partie.
On doit prévoir la fin du jeu : il faut créer une alerte au joueur !
Mais également de créer un événement qui met à jour la barre de progression
régulièrement.
*/
function demarreChronometre() {
    // Ajout de la date de début de partie aux scores/infos de la partie.
    scoresPartie.push(new Date());
    let nbreSecondes = dureePartie * 60; // nombre de secondes pour la partie
    let dureeMilliemes = nbreSecondes * 1000; // durée de la partie

    // Dans X minutes (variable dureePartie) le jeu s'arrête
    alerteFin = setTimeout(function() {
        // Au cas où l'intervalle de mise à jour est trop grand
        $("section#partie progress.barreProgression").attr(
            "value", 100);
        finPartie('Vous avez perduuu !');
    }, dureeMilliemes)

    /*
    En fonction de la durée du jeu, il n'est pas nécessaire de faire avancer
    la barre de progression toutes les secondes.
    Si on estime une partie de 5 min, soit 300 secondes (5 × 60), il est
    acceptable de mettre à jour la barre de progression toutes les 3 secondes.
    Si la partie dure 1 min, soit 120 secondes, toutes les 1.2 secondes irait.

    setInterval prend des millièmes, donc on multiplie par 1000.
    */
    let calculNonSavantMaisSavonneux = nbreSecondes / 100 * 1000;
    /* 
    Mise à jour dudit chronomètre. NB : utilisation d'une variable globale
    pour permettre de supprimer l'intervalle plus tard.
    */
    deroulementPartie = setInterval(function() {
        $("section#partie progress.barreProgression").attr(
            "value", augmenteCompteur);
    }, calculNonSavantMaisSavonneux)
}

/*
Nous utilisons ainsi une fonction anonyme (sans nom), exécutée au chargement
de ce script
*/
$(function() {
    /*
    Objectif : générer le plateau de jeu ; 28 cartes. Ajouter un chronomètre.
    */
    plateau = generePlateau();

    // On génère le plateau en HTML avec les images retenues pour les cartes
    for(let iterateur = 0; iterateur < plateau.length; iterateur++) {
        // création de la carte
        let carte = $("<article>", {
            "id": iterateur,
            "class": "carte ".concat(faceInitiale)
        })
        // ajout d'un évènement sur la carte
        carte.on("click", jouer);
        // ajout de la carte sur le plateau
        carte.appendTo("section#principale");
    };

    // On affiche un chronomètre
    afficheChronometre();
    // On débute la partie avec le chronomètre
    demarreChronometre();
});