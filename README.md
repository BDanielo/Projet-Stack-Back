# Event-app

Bienvenue dans Event-app !

Ce dépôt contient le code source (le back) d'une application de gestion d'événements centralisée construite avec Angular pour le frontend et Symfony pour le **backend**.

## Liens du Projet

- [Boardmix](https://boardmix.com/app/share/CAE.CLybCyABKhB4g7FPdNX3JAjTg5DubiooMAVAAQ/1XLLjX)
- [Répertoire GitHub](https://github.com/BDanielo/Projet-Stack)
- [Diagramme de la Base de Données](https://dbdiagram.io/d/Diagramme-bd-challenge-stack-65689c263be14957870faed9)
- [Tableau Trello](https://trello.com/invite/b/nbRqCxvU/ATTI9528480c65341b056eb02d1b143cffe672F80830/challenge-slack)
- [Figma](https://www.figma.com/file/5N6quj4Cok3OQEAgwdZ0pf/PartyEvent?type=design&node-id=0-1&mode=design)
- [Git front](https://github.com/CreatibOfficiel/events-app)

## Vue d'ensemble

Event-app vise à centraliser la gestion des événements pour diverses organisations telles que les BDE (Bureau des Étudiants), les bars, et plus encore.

### Installation

1. Clonez le dépôt GitHub :

```bash
git clone https://github.com/BDanielo/Projet-Stack.git
```

2. Accédez au répertoire du projet :

```bash
cd Projet-Stack
```

3. Installez les dépendances :

```bash
composer install
```

4. Configurez la base de données et lancez le serveur :

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
symfony server:start
```

   
