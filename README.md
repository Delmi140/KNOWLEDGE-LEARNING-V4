# KNOWLEDGE LEARNING 



## Les prérequis : 

-Mise en place de maildev avec la ligne de commande « docker run -p 1080:1080 -p 1025:1025 maildev/maildev »

-Installation de EasyAdmin avec la ligne de commande « composer require easycorp/easyadmin-bundle »

-Installation de VichUploader avec la ligne de commande « composer require vich/uploader-bundle»

-Installation de Stripe avec la ligne de commande « composer require stripe/stripe-php»

-Installation de test avec la ligne de commande « composer require --dev symfony/test-pack »


## Configuration de la Base de Données

Pour configurer la base de données de ce projet Symfony, suivez les étapes ci-dessous :

### 1. Modifier le fichier `.env`

Ouvrez le fichier `.env` à la racine du projet et configurez la variable `DATABASE_URL` .

MySQL : DATABASE_URL="mysql://root:demo@127.0.0.1:3306/knowledge_learning?serverVersion=8.0.32&charset=utf8mb4"



## Installation du projet 

j'ai crée mon projet avec la ligne de commande "symfony new -- webapp knowledge_learning"



## Démarrage du serveur 

Pour démarrer le projet symfony il faut taper la ligne de commande suivante : run : symfony server:start



## Identifiant User Admin 

Email: ndelmi@hotmail.com

Password: Delmi1234

