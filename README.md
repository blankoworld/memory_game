# Memory : un jeu de mémoire

Le Memory ici présent est le résultat d'un exercice pour tester ses connaissances en HTML, SASS, JS et PHP.

Pour aider un éventuel étudiant à comprendre le code, voici quelques paragraphes sur le projet.

Nous allons commencer par lister les dépendances logicielles du projet, puis expliquer l'arborescence des dossiers du projet pour finalement terminer sur des notes permettant une meilleure compréhension du projet.

**Note**&nbsp;: Pour savoir **pourquoi le code et les fichiers sont écrits en Français** je vous renvoie à la section *Pourquoi l'utilisation du Français ?*.

# Dépendances

Ce projet a plusieurs niveaux de dépendances suivant ce que vous souhaitez en faire.

## Dépendances obligatoires

Ce qu'il faut absolument installer sur votre machine : 

  * l'outil [composer](https://getcomposer.org/) qui installe les dépendances PHP
  * l'outil [sass](https://sass-lang.com/) pour créer des fichiers CSS utilisable par le projet final

Avec ces outils vous devriez lancer les commandes suivantes : 

```bash
# installe les dépendances PHP
composer install
# transpile le code SASS en CSS
sassc sources/style.scss -t expanded > memory.css
```

## Dépendances optionnelles

Pour faciliter la vie du développeur, un fichier **Makefile** est disponible. Il s'utilise avec la commande `make`.

Une fois installé, vous avez accès aux commandes suivantes&nbsp;: 

  * `make`&nbsp;: génère le fichier CSS du Memory (nécessite `sassc`)
  * `make clean`&nbsp;: supprime le fichier CSS précédent
  * `make check`&nbsp;: vérifie le code source au regard du standard PSR12 et d'autres normes pour indenter le code
  * `make fix`&nbsp;: corrige les éventuels problèmes relevés par la commande `make check`. Du moins une partie.
  * `make run`&nbsp;: à n'utiliser **que si vous posséder Docker et docker-compose**. Permet de lancer une instance complète de l'application avec une base de données MySQL.

# Informations sur le projet en Docker

Pour déployer le projet sous GNU/Linux&nbsp;: 

```bash
make run
```

Patientez un instant, les conteneurs vont se lancer et MySQL s'intialiser.

Vous aurez ainsi accès aux adresses suivantes&nbsp;: 

  * http://localhost:8081/ : une interface d'administration de la base de données
  * http://localhost:8888/ : le projet Memory

## Accès à la base de données

Vous devriez connaître les informations suivantes&nbsp;: 

  * Serveur MySQL&nbsp;: **db**
  * Utilisateur&nbsp;: **root**
  * Mot de passe&nbsp;: **oclock**
  * Base de données&nbsp;: **oclock**

# Arborescence des dossiers

Nous avons 3 dossiers principaux&nbsp;: 

  * **base_de_donnees**&nbsp;: contient les instructions pour générer la base de données MySQL initial avec quelques données
  * **sources**&nbsp;: les fichiers PHP de notre application
  * **vendor**&nbsp;: les dépendances PHP installées par la commande `compose install`

**base_de_donnees** est utilisé par Docker. Inutile de s'attarder dessus.

Tout comme **vendor**, installé par `composer install`.

## L'application Memory (dossier **sources**)

Au final, notre jeu de Memory se trouve dans le dossier **sources**.

Nous avons découpé en 3 dossiers, suivant l'[architecture MVC](https://fr.wikipedia.org/wiki/Mod%C3%A8le-vue-contr%C3%B4leur)&nbsp;: 

  * **Modeles**&nbsp;: c'est la couche qui a accès à la base de données et renvoie le résultat à la couche *Controleurs*
  * **Controleurs**&nbsp;: c'est l'initiateur des demandes de données à la couche *Modeles*
  * **Vues**&nbsp;: une fois les données récupérées par le *Controleur* il utilise un gabarit (une vue) pour insérer l'information dedans

En procédant à ce découpage on **minimise la répétition de code** et on **augmente la possibilité pour plusieurs développeurs de participer sans se marcher sur les pieds**.

# Quelques notes diverses

## Implémentation de la norme PSR-4

L'exercice imposait de suivre la [norme PSR-4](https://www.php-fig.org/psr/psr-4/).

Il s'agit de **pouvoir accéder aux fichiers sources/*.php en tapant `\Blankoworld\Memory\MonDossier\MonFichier`**.

L'utilisation de l'[autoloading de `composer`](https://getcomposer.org/doc/01-basic-usage.md#autoloading) a permis cela de manière aisée **en ajoutant une section `autoload`** dans **composer.json** sous la forme suivante&nbsp;: 

```json
    "autoload": {
        "psr-4": {
            "Blankoworld\\Memory\\": "sources"
        }
    },
```

L'*autoloading* est mis à jour chaque fois que `composer install` est utilisé.

L'ajout de la ligne suivante dans **index.php** permet ainsi d'utiliser le résultat dans nos fichiers : 

```bash
require __DIR__ . '/vendor/autoload.php';
```

## Utilisation d'un moteur de template (gabarits)

[Plates](https://platesphp.com/) a été utilisé pour créer les vues de l'application.

Ce système permet d'**employer plusieurs fois un même gabarit (template)** pour plusieurs pages.

Il permet donc la factorisation des vues et indirectement une simplification de ces dernières.

## Pourquoi l'utilisation du Français ?

La **bonne pratique est de coder en anglais**.

Cependant, dans le cadre d'un apprentissage d'un langage, lui-même en anglais, on mélange souvent les mots-clés du langage et nos variables.

Cela créé parfois même des erreurs car nous utilisons un mot-clé du langage.

**L'utilisation du français** dans les noms de dossiers, les fichiers, les classes, les objets, les variables, etc. **est donc volontaire**.

On peut ainsi faire la différence entre les langages utilisés et les choix du développeur de l'application.