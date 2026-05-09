# Issue 05 - Ajouter service providers et lifecycle application

## Labels

`module:1-foundations`, `area:kernel`, `type:architecture`, `type:feature`, `priority:p0`, `status:ready`

## Objectif

Definir le cycle de vie officiel d'une application Velt et permettre aux packages de s'enregistrer via des service providers.

## Pourquoi cette issue est obligatoire

Sans lifecycle officiel, chaque module risque d'initialiser ses services a sa maniere. Sans provider, le kernel devra connaitre HTTP, UI, Database ou Preview, ce qui casse l'architecture modulaire.

## Cycle cible

```text
load environment
load configuration
register providers
boot providers
handle request or command
send response or output
terminate
```

## Travail attendu

- Creer `ServiceProviderInterface`.
- Creer une classe abstraite `ServiceProvider`.
- Ajouter `register(): void`.
- Ajouter `boot(): void`.
- Ajouter `Application::registerProvider(string|ServiceProviderInterface $provider): void`.
- Ajouter `Application::boot(): void`.
- Garantir que `register()` est appele avant `boot()`.
- Eviter les doubles boot.

## Criteres d'acceptation

- Un fake provider peut enregistrer un service dans le container.
- Un fake provider peut executer une logique de boot apres tous les `register`.
- L'ordre `register -> boot` est teste.
- Le kernel ne reference aucun provider concret des autres modules.

## Definition of Done

- Contrats crees.
- Implementation minimale ajoutee.
- Tests unitaires couvrant ordre, double boot et provider invalide.
- README kernel mis a jour.

