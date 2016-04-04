Proget INFO606 - Agenda Universitaire

test
Sujet
----
Le but du projet est de développer un système de gestion d'emploi du temps pour des formations universitaires (comme par exemple les Master 2), en s'inspirant de Google Agenda. Le projet devra être réalisé en Symfony 2.8.
 
 
Pour commencer à coder :
````
git clone https://github.com/bonna023/agenda.git
````
Ensuite élécharger [Composer.phar](https://getcomposer.org/composer.phar) à la racine du projet puis :
````
php composer.phar install
````
C'est bon !

Si vous utilisez une distribution GNU/Linux :
````
rm -rf app/cache/*
rm -rf app/logs/*
sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
````

Mémo sur l'utilisation de Git
----
Pour commencer, installer [Git (pour Windows)](https://git-scm.com/download/win), pour les linuxiens je vous laisse faire ! ;). 
 
Une fois instalé, rendez-vous dans le répertoire de votre serveur web local ("C:\wamp\www" par exemple), 
ensuite **"clique droit"**, puis **"Git Bash Here"**. 
 
A ce stade vous avez un terminal lancé dans le bon répertoire, on va pouvoir passer aux commandes Git. 
 
Commande à réaliser une seule fois à la création du dépot sur votre machine :
````
$ git clone https://github.com/bonna023/agenda.git
````

Les trois commandes incontournables (qu'on utilisera tout le temps) :
```
$ git pull          // met à jour votre dépot Git (à faire avant chaque commit). En lançant cette commande,
                    // des conflits de modification (entre local et remote) peuvent survenir.

$ git add [FILE...] // à utiliser pour ajouter les fichiers que vous créez (ou modifiez) au prochain commit.

$ git commit -a     // à faire à chaque étape de dev, en expliquant assez brièvement les changements.

$ git push          // envoie vos modifications sur le serveur (faites un pull avant pour bien être à jour)
                    // Il n'est pas obligatoire de "pusher" chaque commit aussitôt, mais ça facilite le suivi
                    // des conflits. Pensez en tout cas à "pusher" à la fin de vos sessions de travail.
```

Deux autres commandes utiles :
````
$ git status    // permet de voir les modifications qui n'ont pas encore "commité"

$ git diff      // permet de voir les ajouts/modifications/suppressions
````

Commandes de "stash" de Git (voyez ça comme une pile de changements qu'on met de côté, pour s'aider à bosser sur une chose à la fois) :
````
$ git stash [save [<id>]]     // permet d'empiler ses modifs (en local) pour les mettre de côté ;
                              // utile pour tester les commits de qqn d'autre avant de résoudre un conflit.
$ git stash list
$ git stash show [<id>]       // affiche la diff des modifs du "stash", indépendamment des modifs entre-temps
$ git stash pop|apply [<id>]  // permet de réappliquer les modifs empilées (Conflits possibles: stash/local)
$ git stash drop [<id>]       // supprime le stash. (si "pop" crée un conflit, le "drop" auto. n'a pas lieu)
````
Enfin, comment utiliser les "branch" de GitHub :
````
$ git branch    // vous voyez toutes vos branches

// La suite arrive, c'est pas évident à expliquer..
````

**Résumé :** Avant de bosser sur l'application vous faites un **pull** (met à jour votre dépot), puis à chaque fois que vous ajoutez une fonctionnalité, résolvez un bug, ecetera.. vous faites un **commit**, puis quand vous avez terminer faites un **pull** (re-mettre à jour votre dépot) avant d'uploader votre code sur GitHub avec un **push**.  
Sans oublier **add** pour ajouter les nouveaux fichiers.
````
Exemple : pull -> commit -> commit -> commit  [...] -> pull -> push
````
