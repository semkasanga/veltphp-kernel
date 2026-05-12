# Velt Kernel

## Vue d’ensemble

Le Velt Kernel est la couche fondamentale du framework Velt.

Il constitue le **socle d’exécution (runtime)** sur lequel tous les autres modules reposent.

Son rôle est de fournir une infrastructure minimale, stable et indépendante permettant d’initialiser et d’orchestrer une application Velt.

Le Kernel est volontairement :

- indépendant de tout module (HTTP, CLI, UI, Database, Preview)
- minimaliste
- testable en isolation
- extensible via contrats et providers

---

## Philosophie

Le Kernel repose sur les principes suivants :

- **Simplicité** : uniquement ce qui est essentiel au fonctionnement du framework
- **Isolation** : aucune dépendance vers les autres modules Velt
- **Extensibilité** : tout passe par des contrats et des extensions propres
- **Testabilité** : le kernel doit fonctionner seul, sans autre composant
- **Prévisibilité** : pas de magie cachée, comportement explicite

---

## Responsabilités du Kernel

Le Kernel est responsable de :

### 1. Cycle de vie de l’application

Il définit les étapes principales d’exécution :

- initialisation (bootstrap)
- enregistrement des services
- démarrage des services (boot)
- exécution du runtime
- terminaison

---

### 2. Configuration

Il fournit un système simple de gestion de configuration (repository).

---

### 3. Container de services

Un conteneur minimal de dépendances permettant :

- d’enregistrer des services
- de les résoudre
- de gérer des instances partagées

---

### 4. Service Providers

Le Kernel permet d’étendre le framework via des providers.

Chaque module peut enregistrer ses services proprement.

---

### 5. Système d’événements (synchrone)

Un dispatcher simple permettant la communication interne entre composants.

---

### 6. Gestion des exceptions

Un gestionnaire centralisé pour uniformiser les erreurs du framework.

---

### 7. Environnement

Chargement des variables d’environnement (`.env`) et gestion des modes :

- local
- testing
- production

---

## 8. Contracts fondamentaux

Le Kernel définit un ensemble de contrats publics qui servent de langage commun entre tous les modules du framework Velt.

Ces contrats ne contiennent aucune logique métier : ils définissent uniquement les règles de communication.

### Liste des contrats publics

1. **ApplicationInterface**

Représente l'application Velt en cours d'execution, il definit le noyau public de l'application.

- accès container
- accès configuration
- accès au chemin racine
- gestion environement minimale

Elle expose :

```array
- basePatch() : chemin racine du projet
- container() : container service
- config() : repository de configuration
- environment() : environnement courant
```

et permet de detecter :

```array
- isLocal()
- isProduction()
- isTesting()
```

2. **ContainerInterface**

Représente le système d’injection de dépendances.

Permet :

```array
- bind() : lier un service
- singleton() : service partagé
- make() : résoudre un service
- has() : verifier une entrée
```

3. **ConfigRepositoryInterface**

Représente le système de configuration.

Permet :

```array
- get(string $key, mixed $default = null)
- set(string $key, mixed $value)
- has(string $key)
- all(): array
```

Les clés utilisent la notation par points :

```text
database.connections.mysql.host
```

4. **ArrayableInterface**

Permet de convertir un objet en tableau.

```text
toArray(): array
```

5. **JsonableInterface**

Permet de convertir un objet en JSON.

```text
toJson(): string
```

6. **RenderableInterface**

Permet de rendre un objet en chaîne de caractères.

```text
render(): string
```

### Role des contrats

Les contrats du Kernel :

- définissent le langage du framework
- permettent la communication entre modules
- évitent le couplage entre implémentations
- servent de base à toutes les futures implémentations

### Règles importantes

Les contrats :

- ne contiennent aucune logique métier
- ne dépendent d’aucun module Velt
- doivent rester stables dans le temps
- constituent l’API publique du Kernel

## Ce que le Kernel ne fait PAS

Le Kernel ne doit jamais gérer :

- le routage HTTP
- les commandes CLI
- le rendu UI / templates
- l’accès base de données
- le système de preview mobile
- la logique métier applicative

Ces responsabilités appartiennent à des modules dédiés.

---

## Règles d’architecture

Le Kernel impose une règle stricte de dépendances :

```text
Kernel → indépendant de tous les modules
Modules → dépendent du Kernel
```

Les dépendances suivantes sont interdites dans le Kernel :

```text
- Velt\Http
- Velt\Cli
- Velt\UI
- Velt\Database
- Velt\Preview
```

---

## Installation (future)

Le Kernel sera installé via Composer dans un projet Velt :
```bash
composer require velt/kernel
```

---

## Tests et validation

Le Kernel est validé uniquement via des tests automatisés.

Lancer les tests, depuis le dossier du package :

```bash
cd packages/kernel
vendor/bin/phpunit
```

---

## Exemple de test actuel

```php
use PHPUnit\Framework\TestCase;
use Velt\Kernel\Application;

final class ApplicationTest extends TestCase
{
    public function test_version_est_disponible(): void
    {
        $app = new Application();

        $this->assertSame('0.1.0', $app::VERSION);
    }
}
```

---

## Philosophie des tests

Les tests du Kernel doivent respecter :

```text
aucune dépendance HTTP / CLI / UI / Database
uniquement PHP + PHPUnit + Kernel
utilisation de fixtures pour simuler des comportements
validation du comportement, pas de l’implémentation interne
```

---

## Critères de validation

Le Kernel est considéré comme valide si :

```text
 - l’autoload Composer fonctionne (PSR-4)
 - la classe Application est instanciable
 - les tests PHPUnit passent
 - aucune dépendance vers d’autres modules Velt
 - le Kernel fonctionne en isolation totale
```
