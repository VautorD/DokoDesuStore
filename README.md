# Projet "Doko Desu Store" : plateforme e-Boutique de produits de la culture japonaise

## Sommaire

- [Informations sur le projet](#informations-sur-le-projet)
- [Commandes pour lancer le projet](#commandes-pour-lancer-le-projet)
- [Fonctionnalités principales](#fonctionnalités-principales)
- [Auteurs](#auteurs)

---

## Informations sur le projet

Ce projet est une marketplace spécialisée dans la culture japonaise, permettant aux utilisateurs de découvrir et d'acheter des produits de diverses boutiques physiques. Le site offre une expérience fluide et intuitive pour les utilisateurs, qu'ils soient des commerçants souhaitant promouvoir leur boutique ou des clients à la recherche de produits spécifiques.

---

## Commandes pour lancer le projet

### Installation

1. Clonez le dépôt :
    ```bash
    git clone https://github.com/VautorD/DokoDesuStore.git
    ```

2. Installez les dépendances PHP avec Composer :
    ```bash
    composer install
    ```

3. Installez les dépendances JavaScript avec npm :
    ```bash
    npm install
    npm run build
    ```

4. Configurez votre base de données dans le fichier `.env` :
    ```dotenv
    DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"
    ```
5. Créer la base de données :
    ```dotenv
    php bin/console doctrine:database:create
    ```

6. Exécutez les migrations pour créer les tables de base de données :
    ```bash
    php bin/console make:migration
    php bin/console doctrine:migrations:migrate
    ```

7. Chargez les fixtures pour ajouter des données de test :
    ```bash
    php bin/console doctrine:fixtures:load
    ```

8. Démarrez le serveur de développement :
    ```bash
    symfony server:start
    ```

9. Accédez à l'application via `http://localhost:8000`.

---

## Fonctionnalités principales

### Utilisateurs Quelconques

- **Voir les boutiques et les produits** : Les utilisateurs peuvent parcourir les boutiques et les produits disponibles, en filtrant par catégories si nécessaire.
**Rechercher les boutiques et les produits** : Les utilisateurs peuvent effectuer une recherche pour trouver une boutique ou un produit par nom dans la barre de recherche du header.
- **Ajouter au panier** : Les utilisateurs peuvent ajouter des produits à leur panier pour les acheter plus tard.
- **Réserver des produits** : Les utilisateurs peuvent finaliser leurs commandes et réserver des produits ( avec réception d'un mail de confirmation après chaque réservation).
- **Création de compte** : Les utilisateurs peuvent se créer un compte sur la plateforme.
- **Gérer ses informations** : Les utilisateurs inscrits sur la plateforme ont accès à leurs informations, peuvent les modifier et voir l'historique de leur commande.


### Utilisateurs Commerçants

- **Créer une boutique** : Les commerçants peuvent créer et gérer leur propre boutique sur la plateforme.
- **Ajouter des produits** : Les commerçants peuvent ajouter, modifier ou supprimer des produits de leur boutique.
- **Gérer les commandes** : Les commerçants peuvent voir et gérer les commandes passées dans leur boutique ( avec la réception d'un mail lors de chaque commandes reçues).



### Administrateur du site

- **Voir les utilisateurs** : L'administrateur peut voir l'ensemble des utilisateurs inscrits sur la plateforme et vérifier si leurs emails sont validés.
- **Gérer les boutiques** : L'administrateur peut ajouter (si nécessaire), modifier ou supprimer des boutiques.
- **Gérer les catégories** : L'administrateur peut ajouter, modifier ou supprimer des catégories de produits et de boutiques.
- **Gérer les abonnements** : L'administrateur peut ajouter, modifier ou supprimer des abonnements.

---

## Auteurs

|                                                                      |                                                                                |                                                                         |
| -------------------------------------------------------------------- | ------------------------------------------------------------------------------ | ----------------------------------------------------------------------- |
| <img src="https://github.com/VautorD.png" alt="Doriane" width="200"> | <img src="https://github.com/LauraKoNk.png" alt="Laura" width="200"> | <img src="https://github.com/leteinta.png" alt="Alix" width="200"> |
| [**Doriane**](https://github.com/VautorD)                                | [**Laura**](https://github.com/LauraKoNk)                         | [**Alix**](https://github.com/leteinta)                              |

---
