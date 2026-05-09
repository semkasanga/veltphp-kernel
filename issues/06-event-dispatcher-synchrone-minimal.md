# Issue 06 - Ajouter EventDispatcher synchrone minimal

## Labels

`module:1-foundations`, `area:kernel`, `area:events`, `type:feature`, `priority:p1`, `status:ready`

## Objectif

Fournir un systeme d'evenements minimal pour les hooks internes du framework.

## Portee Module 1

Ce systeme n'est pas le module events applicatif complet. Il sert uniquement aux evenements synchrones de framework : application booted, provider registered, request handled, command executed, exception reported.

## Travail attendu

- Creer `EventDispatcherInterface`.
- Ajouter `listen(string $event, callable $listener): void`.
- Ajouter `dispatch(object|string $event, mixed $payload = null): mixed`.
- Supporter plusieurs listeners pour un meme evenement.
- Garder l'execution synchrone et simple.

## Criteres d'acceptation

- Un listener est appele quand l'evenement est dispatch.
- Plusieurs listeners sont appeles dans l'ordre d'inscription.
- Une exception dans un listener remonte clairement.
- Le dispatcher ne depend d'aucun module externe.

## Definition of Done

- Dispatcher implemente.
- Tests unitaires ajoutes.
- Difference avec les events applicatifs du Module 4 documentee.

