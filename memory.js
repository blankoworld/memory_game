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
const nbreCases = 28;   // nombre de cases du plateau de jeu
const maxImages = 18;   // nombre maximum d'images dont nous disposons
const delaiAffichage = 1.5; // secondes d'affichage des cartes
const faceInitiale = "verso"; // `recto` ou `verso`

// TODO: Erreur si nbreCases modulo 2 renvoie 0 => signifie nombre pair.
// TODO: Erreur si nbreCases supérieur à 18*2 (maxImages)

// Variables disponibles à toute l'application et permettant son fonctionnement
var nbreDouble = nbreCases / 2; // nombre de doubles à avoir.
var plateau = [];               // numéro des fruits utilisés pour le plateau
var pioche = [];                // cartes piochées par le joueur

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
    // TODO: si recto, faire verso et inversement
    if (element.hasClass("recto")) {
        element.removeClass("recto");
        element.addClass("verso");
    } else {
        element.removeClass("verso");
        element.addClass("recto");
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
jouer : actions entreprises après que le joueur ait choisi une carte.
Chaque tour il choisit une carte, une deuxième puis on compare.
Si une carte est jouée, nous ne faisons rien de plus.
Si deux cartes sont jouées : on compare les deux.
*/
function jouer(event) {
    let elementChoisi = event.delegateTarget;
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
            // TODO: incrémenter le nombre de paires de réussites
            /* TODO: vérifier si on a tout trouvé avant le temps imparti. Si
            on arrive à nbreDouble, c'est gagné !
            */
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
Nous utilisons ainsi une fonction anonyme (sans nom), exécutée au chargement
de ce script
*/
$(function() {
    /*
    Objectif : générer le plateau de jeu ; 28 cartes.
    */
    plateau = generePlateau();

    // On génère le plateau en HTML avec les images retenues pour les cartes
    for(let iterateur = 0; iterateur < plateau.length; iterateur++) {
        let image = "recto" + plateau[iterateur];
        // création de la carte
        let carte = $("<article>", {
            "id": iterateur,
            "class": "carte ".concat(faceInitiale, " ", image)
        })
        // ajout d'un évènement sur la carte
        carte.on("click", jouer);
        // ajout de la carte sur le plateau
        carte.appendTo("section#principale");
    };
});