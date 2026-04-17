# FreelanceConnect

Plateforme de mise en relation entre clients et freelances, développée avec Symfony 7.

## Prérequis

- PHP >= 8.2
- Composer
- PostgreSQL 16
- Symfony CLI (optionnel)

## Installation

```bash
git clone <repo>
cd freelanceconnect
composer install
```

Copier le fichier d'environnement et le configurer :

```bash
cp .env .env.local
```

Modifier `.env.local` avec vos paramètres de base de données :

```
DATABASE_URL="postgresql://user:password@127.0.0.1:5432/freelanceconnect?serverVersion=16&charset=utf8"
```

Créer la base de données et exécuter les migrations :

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

Lancer le serveur :

```bash
symfony server:start
# ou
php -S localhost:8000 -t public/
```

## Rôles

| Rôle | Accès |
|---|---|
| `ROLE_ADMIN` | Administration complète |
| `ROLE_CLIENT` | Gestion des offres et missions |
| `ROLE_FREELANCER` | Candidatures et suivi des missions |

## Routes principales

### Authentification
| Méthode | URL | Description |
|---|---|---|
| GET/POST | `/login` | Connexion |
| GET/POST | `/register` | Inscription |
| ANY | `/logout` | Déconnexion |

### Admin
| Méthode | URL | Description |
|---|---|---|
| ANY | `/admin/dashboard` | Tableau de bord |
| GET | `/admin/missions` | Liste des offres à valider |
| POST | `/admin/missions/{id}/validate` | Valider une offre |
| POST | `/admin/missions/{id}/reject` | Rejeter une offre |

### Client
| Méthode | URL | Description |
|---|---|---|
| GET | `/client/dashboard` | Tableau de bord |
| GET | `/client/offres` | Mes offres |
| GET/POST | `/client/offres/new` | Créer une offre |
| GET | `/client/missions` | Mes missions en cours |
| GET | `/client/missions/{id}` | Détail d'une mission |
| POST | `/client/missions/{id}/close` | Clôturer une mission |
| POST | `/client/missions/{id}/firstpayment/acte` | Acter le premier paiement |
| GET | `/client/candidatures` | Candidatures reçues |

### Freelance
| Méthode | URL | Description |
|---|---|---|
| GET | `/freelancer/dashboard` | Tableau de bord |
| GET | `/freelance/offres` | Offres disponibles |
| GET/POST | `/freelance/offres/{id}/postuler` | Postuler à une offre |
| GET | `/freelance/missions` | Mes missions |
| GET | `/freelance/mission/{id}/time` | Suivi du temps |
| POST | `/freelance/mission/{id}/time/add` | Ajouter du temps |

### API publique
| Méthode | URL | Description |
|---|---|---|
| GET | `/api/missions/recent` | 5 dernières missions |
| GET | `/api/missions` | Toutes les missions |

## Postmortem

### Bugs rencontrés et corrections

**Conflit de noms de routes**
Un peut de temps perdu a confondre les controleur entre client et freelance. Attention au nommage.

**Token CSRF manquant**
Une erreur avec le champ `_token`. Revu la gestion des CSRF

**Mix anglais francais.**

**PSR-4 et nommage de fichier**
Le contrôleur API `PublicAPIController` était dans un fichier `missionController.php`. PHP ne pouvait pas autoloader la classe car le nom du fichier ne correspondait pas au nom de la classe. Résolu en renommant le fichier `MissionController.php` et la classe `MissionController`.

**Gestion des Payement**
Ca aurait ete plus propre avec des status.

**Commit**
Pas asses de commit trop focus sur le dev. Heureusement que j ai pas eu de gros probleme.

**Service**
Pas asses d'isolation de la logique (pas systematique) 

**API**
Je n'ai pas pu finir l'API
Revoir l'aspect acces de l'API
### Points d'amélioration identifiés

- **Nommage** — Établir une convention de nommage stricte dès le départ (routes, méthodes, entités) et choisir une seule langue (français ou anglais) pour tout le projet.
- **Commits** — Commiter plus régulièrement, après chaque fonctionnalité ou correction, pas uniquement en fin de session.
- **Services** — Systématiser l'isolation de la logique métier dans les services dès la conception, pas au cas par cas.
- **Gestion des paiements** — Modéliser les états de paiement avec une entité `PaymentStatus` dédiée plutôt que des booléens, pour plus de flexibilité.
- **Flash messages** — Les centraliser dans le layout de base plutôt que les dupliquer dans chaque template.
- **API** — Finir l'implémentation (authentification JWT, routes sécurisées) et définir un format de réponse JSON uniforme pour les erreurs.

## Variables d'environnement

| Variable | Description |
|---|---|
| `DATABASE_URL` | URL de connexion PostgreSQL |
| `FEATURE_VALIDATION_OFFRE_ADMIN` | Active la validation admin des offres (`true`/`false`) |
