Instructions pour installer Developlyon

Pour installer l’application web Developlyon, il faut :
Cloner le repository suivant : https://github.com/aldaitzwild/DevelopLyon
Dans le répertoire Developlyon, lancer l’instruction composer install
Créer la base de données nécessaire au fonctionnement de l’application en prenant le contenu du fichier developlyondb.sql et en collant les instructions dans votre outil qui gère MySQL.
Paramétrer le fichier db.php qui se trouve dans le répertoire config en spécifiant votre identifiant et mot de passe à votre base de données et en mettant developlyon dans la variable APP_DB_NAME

L’application est installée. Pour la lancer, il faut dans le terminal, saisir l’instruction suivante : php -S localhost:8000 -t public

Quelques informations concernant les utilisateurs existant dans la base de données. 3 utilisateurs sont implémentés dans la base. Voici les mots de passe pour pouvoir les utiliser :
admin@develop.lyon : mot de passe => admin
jc@wild.com : mot de passe => 123456
fxl@wild.com : mot de passe => 123456

Comment fonctionne l’application :

Bienvenue sur le forum Developlyon. Vous allez pouvoir dans un premier temps naviguer dans l’application au travers des langages de programmation, des thèmes et des posts. Vous pouvez faire une recherche directement dans la barre de navigation sur un sujet particulier. La recherche vous donne la liste des posts les plus pertinents. La liste des suggestions au niveau de la recherche s'implémente au fur et à mesure des différentes recherches.

Pour pouvoir créer un post ou un message pour répondre à un post, il faut être connecté/identifié dans l’application. Si vous n’êtes pas connecté, vous serez directement envoyé sur la page de connexion.

Seul l’utilisateur à l'origine d’un post ou d’un message peut intervenir sur le post ou le message, soit pour le modifier soit pour le supprimer.

Chaque utilisateur peut personnaliser son profil en ajoutant une photo à son profil.
