# Observations d'audit du Kernel VeltPHP

Ce document rassemble les constats techniques issus de l'analyse du package `packages/kernel` et des issues `01` a `09`.

L'objectif n'est pas de proposer du code, mais de rendre visibles:

- les ecarts entre la spec et l'implémentation actuelle;
- les risques pour le branchement futur de HTTP et CLI;
- les points a corriger en priorite avant d'aller plus loin.

## Synthese rapide

- Le kernel est fonctionnel et les tests passent.
- Le container est la zone la plus risquee.
- `Application` est globalement cohérente, mais son cycle de vie reste trop permissif.
- L'exception handling est propre en mode debug/production, mais pas encore pleinement orchestre.
- L'env loader, le dispatcher d'evenements et la config sont utilisables, mais encore simples.

## Etat par issue

| Issue | Etat | Conformite estimee | Observation principale |
|---|---:|---:|---|
| 01 | DONE | 95% | la structure du package et le socle existent |
| 02 | DONE | 90% | les contrats publics sont en place |
| 03 | DONE | 90% | le container minimal fonctionne |
| 04 | DONE | 90% | `Application` et `ConfigRepository` sont disponibles |
| 05 | PARTIAL | 80% | le lifecycle existe, mais il reste des zones de re-entrance |
| 06 | DONE | 90% | dispatcher synchrone minimal conforme |
| 07 | PARTIAL | 75% | `.env` et modes présents, mais encore fragiles |
| 08 | PARTIAL | 75% | handler centralise present, orchestration incomplete |
| 09 | PARTIAL | 65% | autowiring utile, compatibilite PSR-11 encore imparfaite |

## Container

Fichiers concernés:

- `packages/kernel/src/Container.php`
- `packages/kernel/src/Contracts/ContainerInterface.php`
- `packages/kernel/src/Exceptions/ContainerResolutionException.php`
- `packages/kernel/src/Exceptions/ServiceNotFoundException.php`

### Ce qui est bon

- `bind`, `singleton`, `instance`, `alias`, `has`, `get` existent.
- L'autowiring simple des classes concretes fonctionne.
- L'injection de dependances objet via reflection fonctionne pour des cas simples.
- Les services en singleton restent stables apres la premiere resolution.

### Ce qui pose probleme

- `has()` peut retourner vrai pour une classe concrete alors que `get()` peut echouer au moment de l'instanciation.
- La resolution des closures passe le container comme unique argument, ce qui rend certains callables non compatbles.
- Les dependances scalaires ne sont pas resolues automatiquement.
- Les dependances avec valeur par defaut ne sont pas vraiment exploitees.
- La compatibilite PSR-11 est encore seulement partielle, sans contrat explicitement porte par le package.

### Lecture architecturale

- `SAFE` pour des services internes simples.
- `RISKY` comme contrat public pour le futur HTTP/CLI.

## Application

Fichier principal:

- `packages/kernel/src/Application.php`

### Ce qui est bon

- `basePath()`, `container()`, `config()`, `events()`, `env()`, `exceptions()` existent.
- `registerProvider()` et `boot()` mettent en place un lifecycle utilisable.
- les bindings de base sont enregistrés dans le container.
- le chargement `.env` est effectif.

### Ce qui pose probleme

- le constructeur concentre beaucoup de responsabilités.
- `boot()` verrouille l'etat seulement apres la boucle des providers.
- `registerProvider()` accepte une classe string et instancie directement le provider.
- `environment()` depend de `APP_ENV` avec un default `production`, ce qui peut masquer un contexte local mal configuré.

### Lecture architecturale

- `SAFE` pour l'usage actuel.
- `RISKY` si on branche HTTP/CLI sans durcir le cycle de vie.

## Exceptions

Fichiers concernés:

- `packages/kernel/src/Exceptions/DefaultExceptionHandler.php`
- `packages/kernel/src/Contracts/ExceptionHandlerInterface.php`

### Ce qui est bon

- la distinction debug / production est bien présente.
- les informations sensibles ne sont pas exposées en production.
- la structure de sortie est simple et previsible.

### Ce qui pose probleme

- `report()` loggue toujours les details complets.
- `render()` ignore le contexte fourni.
- le handler existe, mais il n'est pas encore la frontiere d'erreur centrale du runtime.

### Lecture architecturale

- `SAFE` comme composant local.
- `RISKY` comme fondation de gestion d'erreurs globale tant que le runtime ne l'orchestre pas.

## Env

Fichier principal:

- `packages/kernel/src/Env/EnvRepository.php`

### Ce qui est bon

- chargement d'un fichier `.env` simple.
- support des conversions `true`, `false`, `null`, `empty`.
- `set`, `get`, `has`, `load` sont présents.

### Ce qui manque

- gestion plus robuste des cas invalides.
- documentation d'un `.env.example`.
- strategie plus nette pour le mode par defaut.

### Lecture architecturale

- `SAFE` pour le noyau.
- `RISKY` si HTTP/CLI depend d'une configuration trop implicite.

## Event system

Fichier principal:

- `packages/kernel/src/EventDispatcher.php`

### Observation

- dispatch synchrone.
- ordre FIFO respecté.
- propagation des exceptions preservee.
- implementation volontairement minimaliste.

### Lecture architecturale

- `SAFE` pour les hooks internes du kernel.
- `RISKY` si on attend un vrai bus applicatif plus tard sans extension formelle.

## Config

Fichier principal:

- `packages/kernel/src/Config/ConfigRepository.php`

### Observation

- la notation par points fonctionne.
- `set()` permet de construire des structures imbriquees.
- `has()` considere `null` comme absent.

### Risque

- divergence possible entre "cle presente" et "valeur manquante".

## Contrats

Fichiers concernés:

- `packages/kernel/src/Contracts/ApplicationInterface.php`
- `packages/kernel/src/Contracts/ContainerInterface.php`
- `packages/kernel/src/Contracts/ConfigRepositoryInterface.php`
- `packages/kernel/src/Contracts/EnvRepositoryInterface.php`
- `packages/kernel/src/Contracts/EventDispatcherInterface.php`
- `packages/kernel/src/Contracts/ExceptionHandlerInterface.php`
- `packages/kernel/src/Contracts/ServiceProviderInterface.php`
- `packages/kernel/src/Contracts/ArrayableInterface.php`
- `packages/kernel/src/Contracts/JsonableInterface.php`
- `packages/kernel/src/Contracts/RenderableInterface.php`

### Observation

- les contrats sont stables et lisibles.
- les interfaces sont generiques.
- les tests de fixtures confirment qu'elles sont utilisables sans dependance externe.

### Risque

- la documentation et l'implementation ont deja commence a diverger sur certains noms et intentions.

## Risques pour HTTP / CLI

### Bloquants

- container trop permissif;
- autowiring pas assez explicite sur les scalaires;
- lifecycle providers pas assez verrouille;
- gestion d'erreurs pas encore branchee au runtime global.

### Importants

- differenciation stricte entre `has()` et `get()`;
- semantique des valeurs `null`;
- stabilisation des messages d'exception;
- documentation commune entre README racine et README du package.

### Secondaires

- coverage des cas limites;
- exemples de bootstrap plus pedagogiques;
- documentation `.env.example`.

## Ordre de remédiation conseillé

1. Stabiliser le container.
2. Verrouiller le lifecycle de `Application`.
3. Brancher le handler d'exceptions dans le runtime.
4. Clarifier la semantique `config` et `env`.
5. Garder le dispatcher event minimal, sans l'alourdir.

## Lecture finale

Le kernel est sur de bonnes bases, mais il faut encore le durcir avant d'y brancher HTTP ou CLI.

Le bon critere n'est pas seulement "les tests passent".

Le vrai critere est:

- le contrat reste previsible;
- les erreurs sont explicites;
- les modules futurs peuvent s'appuyer dessus sans contournement;
- aucune couche HTTP/CLI n'entre par accident dans le kernel.


faut arranger
