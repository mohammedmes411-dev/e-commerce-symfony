# Projet E-Commerce avec Symfony - EHEI 2026

## Introduction

Ce projet a été réalisé dans le cadre du module Symfony en 4ème année Génie Logiciel à l'EHEI.
L'objectif est de mettre en pratique les connaissances acquises durant l'année en développant un site e-commerce fonctionnel.

## Description du Projet

Ce site e-commerce permet aux utilisateurs de parcourir des produits par catégorie, de gérer leur panier d'achats, et de créer un compte ou se connecter pour accéder à leur profil.

Le projet est structuré autour du framework **Symfony 8**, en respectant les bonnes pratiques de développement (principes SOLID, architecture en couches, séparation des responsabilités).

## Technologies Utilisées

- **Symfony 8** - Framework PHP
- **Doctrine ORM** - Gestion de la base de données
- **Twig** - Moteur de templates
- **Bootstrap 5** - Framework CSS
- **MySQL** - Base de données

## Installation et Lancement

```bash
# 1. Installer les dépendances
composer install

# 2. Configurer la base de données dans .env
DATABASE_URL="mysql://user:password@127.0.0.1:3306/ecommerce_db"

# 3. Créer la base de données
php bin/console doctrine:database:create

# 4. Exécuter les migrations
php bin/console doctrine:migrations:migrate

# 5. Lancer le serveur
symfony serve
```

## Étapes du Projet

### Étape 1 - Intégration des templates

Intégration des pages HTML statiques dans le projet Symfony en tant que templates Twig, avec création des routes et contrôleurs correspondants.

Pages réalisées :
- [x] Page de connexion / inscription
- [x] Page de profil utilisateur
- [x] Page détails d'un produit
- [x] Page d'accueil (liste des produits)
- [x] Page liste des catégories
- [x] Page produits par catégorie
- [x] Page panier

### Étape 2 - Entités et base de données

Création des entités Doctrine et mise en place de la base de données.

- [x] Entité `Produit` (nom, prix, description, stock)
- [x] Entité `Categorie` (nom, description)
- [x] Entité `Panier` (utilisateur, items)
- [x] Entité `ArticlePanier` (produit, quantité)
- [x] Entité `Utilisateur` (email, mot de passe, rôles)
- [x] Migrations de base de données

### Étape 3 - Gestion du panier en session

Implémentation du système de panier en session, en respectant les principes SOLID et le Pattern Strategy.

- [x] Interface `CartInterface`
- [x] Classe `PanierSession` (implémentation via session Symfony)
- [x] Classe `GestionnairePanier` (CartHandler)
- [x] DTO `AjoutArticleDTO` pour le transfert de données
- [x] Routes d'ajout / suppression d'articles

### Étape 4 - Authentification et sécurité

Mise en place du système de connexion, d'inscription et de sécurisation des pages.

- [x] Formulaire d'inscription avec hashage du mot de passe
- [x] Formulaire de connexion via le système de sécurité Symfony
- [x] Sécurisation de la page profil (accès réservé aux utilisateurs connectés)
- [x] Gestion des rôles (`ROLE_USER`)

## Structure du Projet

```
src/
├── Controller/     ← Contrôleurs des pages
├── Entity/         ← Entités Doctrine
├── Dto/            ← Objets de transfert de données
├── Helpers/        ← Classes utilitaires
├── Services/       ← Services métier
└── Repository/     ← Repositories Doctrine
templates/
└── files/          ← Templates Twig
```

## Auteur

Étudiant en 4ème année Génie Logiciel - EHEI 2026