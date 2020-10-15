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
const maxImages = 18;      // nombre maximum d'images dont nous disposons

// TODO: Erreur si nbreCases modulo 2 renvoie 0 => signifie nombre pair.
// TODO: Erreur si nbreCases supérieur à 18*2 (maxImages)

var nbreDouble = nbreCases / 2; // nombre de doubles à avoir.

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

    // On utilise que les 14 premières.
    retenus = chiffres.slice(0, nbreDouble);
    
    // Avons-nous juste ? On affiche le résultat
    console.log("14 cartes choisies : ", retenus)

    // On double le tableau avant de mélanger
    resultat = retenus.concat(retenus);

    // On retourne le résultat mélangé
    return melanger(resultat);
}

/*
Nous utilisons ainsi une fonction anonyme (sans nom), exécutée au chargement
de ce script
*/
$(function() {
    /*
    Objectif : générer le plateau de jeu ; 28 cartes.
    */
    let plateau = generePlateau();

    // On génère le plateau avec les images retenues
    for(let iterateur = 0; iterateur < plateau.length; iterateur++) {
        $("<article>", {
            "id": "image" + (plateau[iterateur]),
            "class": "carte"
        }).appendTo("section#principale")
    };
});