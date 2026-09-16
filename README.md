# Dipla — Plateforme de découverte de commerces locaux

Dipla est une plateforme qui permet à des particuliers de trouver des produits et services proposés par des commerces de proximité, avec une logique de recherche géolocalisée (distance, itinéraire) plutôt qu'un simple annuaire.

Deux types de comptes cohabitent :
- **Utilisateur (particulier)** : cherche, consulte, met en favoris, pose des questions, laisse des avis.
- **Entreprise (commerce)** : publie des produits/services, répond aux questions et avis, suit ses statistiques.

> ⚠️ **Statut du projet : site vitrine / démonstration technique.**
> Ce dépôt est une reconstruction complète et personnelle du projet Dipla, réalisée à des fins d'apprentissage et de portfolio. **Le site n'est pour l'instant pas ouvert à de vrais commerces ni à de vrais utilisateurs** : il n'y a pas de données réelles et aucune entreprise n'y est actuellement référencée en production. C'est une démonstration de ce que le site peut faire, pas un service commercial actif.

---

## Sommaire
- [Accès au site](#acces-au-site)
- [Pourquoi cette refonte](#pourquoi-cette-refonte)
- [Utilisation de l'IA dans ce projet](#utilisation-de-lia-dans-ce-projet)
- [Stack technique](#stack-technique)
- [Architecture du projet](#architecture-du-projet)
- [Modèle de données](#modèle-de-données)
- [Fonctionnalités](#fonctionnalités)
- [Déploiement](#déploiement)
- [Limites connues / reste à faire](#limites-connues--reste-à-faire)
- [Licence](#licence)

---

## Accès au site

> **Remarque :** Le site est hébergé sur l'offre gratuite de Render. En raison de la mise en veille automatique après une période d'inactivité, le premier chargement peut prendre entre 30 secondes et 1 minute.

Des comptes de démonstration ont été créés pour vous permettre d'explorer l'ensemble des fonctionnalités de Dipla :

| Type de compte | Adresse e-mail | Mot de passe |
| :--- | :--- | :--- |
| **Commerçant** | `commerce<N>@exemple.com` | `mdpcommerce<N>` |
| **Utilisateur** | `user<N>@exemple.com` | `mdpuser<N>` |

*(Remplacer `<N>` par le numéro de compte souhaité)*

Connectez-vous avec ces identifiants pour accéder aux différents tableaux de bord.

## Pourquoi cette refonte

Le tout premier Dipla (dépôt `romlbl/Dipla`) était un projet en PHP procédural pur, sans framework, avec une base MySQL en accès direct par PDO, du jQuery pour l'interactivité, une recherche via TNTSearch/TNTGeoSearch, et HERE Maps pour la cartographie. Le site fonctionnait, mais accumulait une dette technique importante :

- connexions à la base de données codées en dur dans plusieurs fichiers ;
- mots de passe hashés en MD5 ;
- pas de responsive réellement pensé (CSS non mobile-first) ;
- stockage d'images en local sur un disque éphémère (perdu à chaque redéploiement sur Render).

Plutôt que de corriger ces problèmes un par un sur l'existant, le choix a été fait de repartir de zéro avec une stack moderne, documentée, testable, et pensée dès le départ pour un déploiement cloud propre. Le comportement fonctionnel du site d'origine (double type de compte, recherche géolocalisée, fiches produits, avis, questions/réponses, favoris, historique) a été analysé en détail avant la reconstruction pour ne perdre aucune fonctionnalité utile.

## Utilisation de l'IA dans ce projet

Dans un soucis de rapidité et d'efficacité ce projet a été développé avec l'aide d'un assistant IA (Claude, d'Anthropic) utilisé comme outil de génération de code assistée, dans un rôle similaire à de la pair-programmation. **Le site n'a pas été créé « à l'aveugle » en déléguant tout à l'IA.** :

- L'IA a été utilisée pour générer des blocs de code ciblés : composants Livewire, migrations, méthodes de modèles Eloquent, requêtes SQL, scripts Alpine.js, gabarits Blade, configuration Docker, etc.
- **Chaque bloc de code généré a été relu, compris et vérifié manuellement** avant d'être intégré au projet — aucun code n'a été copié-collé aveuglément. Les choix d'architecture (guards d'authentification séparés, structure des tables, organisation des dossiers, politique de sécurité) ont été discutés et validés au fil de l'eau plutôt que délégués intégralement.

L'objectif de cette mention est la transparence : ce dépôt reflète un travail de développement personnel assisté par IA, pas un projet généré automatiquement sans supervision.

## Stack technique

| Composant | Choix | Pourquoi |
|---|---|---|
| Framework backend | **Laravel 13** | ORM (Eloquent), migrations versionnées, routing, validation, sécurité intégrée. |
| Interactivité front | **Livewire 3** + **Alpine.js** | Composants dynamiques (recherche live, favoris, filtres, modales) sans API séparée, tout en restant en PHP/Blade. |
| CSS | **Tailwind CSS v4** | Framework utilitaire, responsive-first par design, configuration via `@theme` dans `resources/css/app.css` (plus de `tailwind.config.js`). |
| Base de données | **PostgreSQL** hébergée sur **Neon** (`eu-central-1`, `sslmode=require`) | Plus robuste que MySQL sur le long terme, tier gratuit persistant (contrairement au disque éphémère de Render), recherche full-text native (`to_tsvector`). |
| Authentification | **Laravel Fortify** + guards séparés (`web` / `company`) | Inscription, connexion, mot de passe oublié, vérification email, 2FA, passkeys — hash bcrypt (fini le MD5 non salé de la v1). |
| Stockage des images | **Cloudinary** (`cloudinary/cloudinary_php` SDK v3.1.3) | Le disque de Render (offre gratuite) est éphémère : les images ne peuvent pas être stockées localement en production. |
| Cartes / géolocalisation | **Leaflet.js + OpenStreetMap + Nominatim (autocomplete) + OSRM (itinéraires)** | Aucune clé API payante requise, contrairement à HERE Maps utilisé dans la v1. |
| Recadrage d'images | **Cropper.js v1.6.2** | Recadrage côté client avant upload (photo de couverture 16:7, photo carte 2:3, avatar rond). |
| Réordonnancement | **SortableJS** | Glisser-déposer pour réorganiser les photos produit. |
| Fond animé | **@firecms/neat** (WebGL) | Arrière-plan animé sur les pages publiques d'authentification et d'accueil. |
| Hébergement | **Render** (Docker multi-stage) | Build reproductible, binding dynamique du port via `docker/apache-port.sh`. |

### Pourquoi pas une stack full JS (Next.js, etc.) ?

C'était une option envisagée, mais elle impliquait de réapprendre un écosystème complet (React/Vue, TypeScript, Prisma…) en plus de refaire tout le site. Laravel + Livewire donne un résultat tout aussi moderne avec une courbe d'apprentissage plus douce en partant de PHP. Si le projet devait un jour grossir significativement, une migration vers une architecture API + frontend séparé reste possible sans tout jeter.

## Architecture du projet

```
DiplaV2/
├── app/
│   ├── Models/              → User, Company, Product, ProductImage, Review, Discussion, Favorite, ViewHistory
│   ├── Http/Controllers/    → ProductController, Company/ProductController
│   ├── Livewire/            → Search, CompanyStorefront, Account/*, Company/*, Product/*
│   ├── Policies/            → ProductPolicy (autorisation entreprise ↔ produit)
│   └── Services/            → CloudinaryService (upload/suppression d'images)
├── database/
│   ├── migrations/          → schéma versionné complet
│   └── seeders/
├── resources/
│   ├── views/                → Blade + composants Livewire (layouts public / app / company / user)
│   ├── css/                  → Tailwind v4 (tokens dans app.css)
│   └── js/                   → Alpine components (carte, recadrage, géolocalisation, drag & drop)
├── routes/
│   ├── web.php
│   ├── company-auth.php      → routes du guard "company"
│   └── settings.php
├── docker/                   → config Apache + script de binding du port Render
├── Dockerfile                → build multi-stage (Node pour les assets, PHP 8.4 + Apache)
└── .env.example
```

## Modèle de données

| Table | Description |
|---|---|
| `users` | Comptes particuliers (nom, email, mot de passe, adresse optionnelle, lat/lng) |
| `companies` | Comptes entreprise (nom, email, mot de passe, adresse **obligatoire**, lat/lng, photo de couverture, photo carte, avatar, description, horaires JSON) |
| `products` | Produits/services publiés par une entreprise (titre, prix, description, type, mots-clés, adresse, lat/lng, `search_vector` pour le full-text) |
| `product_images` | Table dédiée aux photos produit (URL Cloudinary, position d'affichage) |
| `reviews` | Avis utilisateurs (note, sujet, contenu, réponse de l'entreprise) |
| `discussions` | Questions/réponses, structure threadée via `parent_id` |
| `favorites` | Relation many-to-many utilisateur ↔ produit |
| `view_history` | Historique de consultation, plafonné à 7 entrées par utilisateur (purge FIFO automatique) |

Toutes les relations sont définies avec suppression en cascade (`cascadeOnDelete`) pour garantir la cohérence des données lors de la suppression d'un compte, d'une entreprise ou d'un produit.

## Fonctionnalités

### Espace utilisateur
- Inscription / connexion (email + mot de passe, passkeys, 2FA optionnelle)
- Géolocalisation du profil (carte interactive + autocomplete d'adresse)
- Favoris, avis, questions posées, historique de consultation (max 7, purge automatique)
- Modification du profil, de l'email, du mot de passe
- Suppression de compte avec confirmation par mot de passe

### Espace entreprise
- Inscription avec adresse **obligatoire** (le commerce doit être localisable)
- Dashboard avec statistiques (vues cumulées, favoris reçus, note moyenne, nombre de produits)
- Gestion de la devanture : photo de couverture (16:7), photo carte (2:3), avatar rond, description, horaires d'ouverture par jour
- CRUD produits complet avec upload et recadrage d'images (jusqu'à 4 photos, glisser-déposer pour réorganiser)
- Gestion et réponse aux avis clients
- Gestion et réponse aux questions clients (avec badge de questions en attente)
- Paramètres de compte, suppression de compte (avec nettoyage Cloudinary en cascade)

### Recherche & découverte
- Recherche plein texte (PostgreSQL full-text, `to_tsvector`)
- Trois modes : recherche par mot-clé, "à proximité" (rayon 20 km), "découvrir" (aléatoire)
- Filtres : distance max, prix max, type (produit / service / commerce)
- Calcul de distance par formule de Haversine (SQL natif, pas de dépendance PostGIS)
- Persistance de la position de recherche choisie (store Alpine + `localStorage`), avec priorité à l'adresse du compte à la connexion

### Fiche produit
- Galerie d'images au format portrait (2:3)
- Note moyenne, avis, questions/réponses en direct (Livewire, sans rechargement de page)
- Itinéraire (Leaflet + OSRM) avec estimation de temps à pied / vélo / voiture
- Produits similaires (suggestion par mots-clés partagés)
- Ajout automatique à l'historique de consultation
- Indicateur "ouvert / fermé actuellement" calculé à partir des horaires


## Déploiement

Le projet n'étant actuellement utilisé que comme portfolio, il est configuré pour un déploiement Docker sur Render, ce qui fournit une solution gratuite et suffisante. Cependant, pour une utilisation réelle, un changement d'hébergeur serait nécessaire.


## Limites connues / reste à faire

- Audit RGPD (mentions légales, politique de confidentialité, bandeau cookies) avant toute mise en production réelle
- Page et fonctionnalité manquante (récupération de mot de passe, page de contact,...)

Tant que ces points ne sont pas finalisés, le site reste une démonstration technique et n'a pas vocation à héberger de vrais commerces.

## Licence

Projet personnel à but d'apprentissage et de démonstration. Aucune licence d'utilisation commerciale n'est accordée sur ce dépôt sauf mention contraire.
