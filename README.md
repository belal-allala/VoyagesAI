# Vega Go

![Logo Vega Go](./public/images/VegaGo.png)

## Description du Projet

Vega Go est une plateforme web moderne visant à digitaliser et optimiser l'écosystème du transport en bus. Le projet couvre l'intégralité du cycle de vie du voyage, de la recherche et réservation de billets pour les voyageurs, à la gestion complète des flottes, des trajets (y compris la planification récurrente complexe), et des chauffeurs pour les compagnies de transport. La plateforme inclut également des outils dédiés pour les chauffeurs (validation de billets via QR code) et une interface d'administration globale.

L'objectif principal de Vega Go est de combler le manque d'outils numériques efficaces dans le secteur du transport en bus, en offrant une solution centralisée qui améliore l'expérience utilisateur et l'efficacité opérationnelle pour toutes les parties prenantes.

## Fonctionnalités

La plateforme offre des fonctionnalités riches pour différents types d'utilisateurs :

### Pour les Voyageurs

*   **Recherche de trajets :** Trouver des voyages en spécifiant villes de départ/arrivée et date.
*   **Visualisation des offres :** Affichage des trajets disponibles avec détails (horaires, prix, arrêts, compagnie, bus).
*   **Réservation de place(s) :** Sélection du nombre de passagers.
*   **Paiement sécurisé :** Intégration d'un système de paiement en ligne (Stripe).
*   **Billet électronique :** Génération et accès à un billet numérique (PDF téléchargeable avec QR Code).
*   **Consultation des réservations :** Historique et suivi des réservations.
*   **Annulation de réservation :** Possibilité d'annuler sous certaines conditions.
*   **Gestion du profil :** Consultation et mise à jour des informations personnelles.

### Pour les Employés (de Compagnie)

*   **Tableau de bord Compagnie :** Statistiques clés sur l'activité.
*   **Gestion des Bus :** CRUD (Création, Lecture, Mise à jour, Suppression) des bus.
*   **Gestion des Chauffeurs :** Affiliation et dissociation d'utilisateurs ayant le rôle chauffeur.
*   **Création de Trajets :** Définition d'itinéraires, attribution bus/chauffeur, ajout d'étapes (sous-trajets).
*   **Gestion des trajets existants :** Consultation, modification, suppression.
*   **Gestion de la Récurrence :** Configuration de la planification récurrente (quotidien, hebdomadaire, etc.) et génération des occurrences.
*   **Export de données :** Téléchargement de rapports (passagers, trajets).
*   **Gestion du profil :** Consultation et mise à jour.

### Pour les Chauffeurs

*   **Consultation des Trajets Assignés :** Vue des trajets planifiés, filtrable par date.
*   **Consultation de la Liste des Passagers :** Accès à la liste des passagers réservés pour un trajet/date.
*   **Validation des Billets :** Outil de scan QR code ou saisie manuelle pour vérifier et valider les billets.
*   **Gestion du profil :** Consultation et mise à jour.

### Pour l'Administrateur (Global)

*   **Gestion Globale des Utilisateurs :** Liste, recherche, filtre, modification (rôles, affiliation), suppression.
*   **Gestion Globale des Compagnies :** Liste, consultation des statistiques détaillées, CRUD des compagnies.
*   **Tableau de bord Admin :** Vue d'ensemble ou statistiques agrégées de la plateforme.
*   **Gestion du profil :** Consultation et mise à jour.

## Technologies Utilisées

*   **Backend:** PHP 8.x (Framework Laravel 10.x)
*   **Frontend:** HTML5, CSS3 (avec Tailwind CSS), JavaScript, Livewire (potentiellement pour des interactions dynamiques dans les vues)
*   **Base de données:** PostgreSQL
*   **Système de Paiement:** Stripe Integration
*   **Autres Bibliothèques Clés:**
    *   `barryvdh/laravel-dompdf` pour la génération de PDF (Billets).
    *   `simplesoftwareio/simple-qrcode` pour la génération de QR Codes.
    *   `maatwebsite/excel` pour les exports Excel.
    *   `carbon/carbon` pour la manipulation de dates/heures.
    *   `jsqr` (JavaScript) pour le scan QR code côté client.

## Installation et Configuration

Suivez ces étapes pour mettre en place le projet sur votre machine locale :

1.  **Cloner le dépôt :**
    ```bash
    git clone https://github.com/belal-allala/VoyagesAI.git
    cd vega-go
    ```

2.  **Installer les dépendances Composer :**
    ```bash
    composer install
    ```

3.  **Copier le fichier d'environnement :**
    ```bash
    cp .env.example .env
    ```

4.  **Générer la clé d'application :**
    ```bash
    php artisan key:generate
    ```

5.  **Configurer la base de données :**
    Ouvrez le fichier `.env` et configurez vos identifiants de connexion à la base de données PostgreSQL.
    ```dotenv
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=vega_go
    DB_USERNAME=votre_username
    DB_PASSWORD=votre_password
    ```

6.  **Configurer Stripe :**
    Obtenez vos clés API Stripe (Publishable Key et Secret Key) ainsi que la clé de signature du webhook depuis votre tableau de bord Stripe. Ajoutez-les au fichier `.env` :
    ```dotenv
    STRIPE_KEY=your_stripe_publishable_key
    STRIPE_SECRET=your_stripe_secret_key
    STRIPE_WEBHOOK_SECRET=your_stripe_webhook_secret
    ```
    *N'oubliez pas de configurer le webhook Stripe dans votre tableau de bord Stripe pour pointer vers `VOTRE_URL/stripe/webhook` et d'activer les événements `payment_intent.succeeded` et `payment_intent.payment_failed`.*

7.  **Exécuter les migrations de base de données :**
    ```bash
    php artisan migrate
    ```


8. **Lancer le serveur de développement :**
    ```bash
    php artisan serve
    ```

Le projet devrait maintenant être accessible à l'adresse `http://localhost:8000` (ou l'adresse spécifiée par `php artisan serve`).

## Utilisation

*   **Accès :** Ouvrez votre navigateur et naviguez vers l'URL de l'application (par défaut `http://localhost:8000`).
*   **Inscription/Connexion :** Utilisez les liens `Inscription` ou `Connexion` dans la barre de navigation pour créer un compte ou vous connecter. Vous pouvez choisir votre rôle lors de l'inscription (voyageur, employé, chauffeur). L'administrateur est généralement créé via un seeder ou manuellement dans la base de données.
*   **Navigation :** La barre de navigation s'adapte au rôle de l'utilisateur connecté pour afficher les liens pertinents vers les espaces Voyageur, Employé, Chauffeur ou Admin.

### Structure Principale du Projet

```bash
.
├── app/
│   ├── Console/Commands/
│   │   └── GenerateRecurringTrajets.php
│   ├── Exports/
│   │   ├── DailyTrajetsExport.php
│   │   └── PassagersExport.php
│   ├── Http/Controllers/
│   │   ├── AdminCompagnieController.php
│   │   ├── ... (Autres contrôleurs)
│   ├── Models/
│   │   ├── Billet.php
│   │   ├── Bus.php
│   │   ├── Compagnie.php
│   │   ├── Profile.php
│   │   ├── RecurringPattern.php
│   │   ├── Reservation.php
│   │   ├── SousTrajet.php
│   │   ├── Trajet.php
│   │   └── User.php
│   ├── Services/
│   │   ├── RecurringTrajetService.php
│   │   └── StripeService.php
│   └── ...
├── database/
│   ├── migrations/
│   │   └── ... (Vos fichiers de migration)
│   └── seeders/
│       └── ... (Vos seeders optionnels)
├── resources/
│   └── views/
│       ├── auth/
│       ├── chauffeur/
│       ├── components/ (Navbar, Footer, etc.)
│       ├── employe/
│       ├── layouts/ (app.blade.php)
│       ├── profile/
│       ├── voyageur/
│       └── welcome.blade.php
├── routes/
│   └── web.php
├── public/
│   └── storage/ (Lien symbolique vers storage/app/public)
├── storage/
│   └── app/public/
│       ├── compagnie_logos/
│       └── profile_pictures/
└── ... (Autres fichiers et dossiers Laravel)
```
