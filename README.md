# ToDo & Co

## Description
ToDo & Co est une application permettant de gérer ses tâches quotidiennes.

## Technologie

Le framework symfony est utilisé pour la creation de ce projet

## Prérequis

- PHP 8.2
- Symfony 7.1

## Installation

Suivez ces étapes pour lancer le projet :

**Cloner le dépôt**

```sh
git clone https://github.com/bln102/p8-TodoList.git
cd p8-TodoList
```

## Installer les dépendances :

```sh
composer install
```

**Configurer les variables d'environnement :\***

Créer un fichier `.env` et configurez-le selon votre environnement.

Mettez à jour l'URL de la base de données et d'autres paramètres spécifiques à l'environnement dans `.env.local`.



Créer la base de données :

```sh
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
```

Exécuter les migrations de base de données :

```sh
php bin/console doctrine:migrations:migrate
```

## Lancer le serveur local Symfony :

```sh
symfony server:start --port=8085
```

L'application est disponible à l'adresse `https://127.0.0.1:8085`.

## Tester
Pour tester l'application, il faut créer un fichier `.env.test.local` avec la même URL de la base de données.

Il faut créer la base de données avec des données de test
```sh
php bin/console --env=test doctrine:database:create    
symfony console doctrine:schema:update --env=test --force   
php bin/console doctrine:fixtures:load --env=test
```

On peut effectuer les tests et produire un rapport de couverture avec phpunit:
```sh
php bin/phpunit
php bin/phpunit --coverage-html html 
```

## Modifier le projet
Pour modifier l'application, il faut d'abord modifier les dossiers entity (entité) et controller (Contrôleur).

**Les entités :**
Pour ajouter une nouvelle entité ou modifier une entité existante :
```sh
php bin\console make:entity
php bin\console make:entity Task
```
Ensuite, vous avez la possibilité de modifier le fichier de l'entité ou son repository pour affiner vos critères. Enfin, vous devrez migrer les données vers la base de données.

**Les contrôleurs :**
Pour ajouter une nouvelle entité ou modifier un contrôleur existant :
```sh
php bin\console make:controller
```
Ensuite, vous avez la possibilité de modifier le fichier du contrôleur afin d'ajouter de nouvelles fonctionnalités, et les templates peuvent être adaptés pour répondre à vos besoins.
