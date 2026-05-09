# Issue 08 - Ajouter ExceptionHandler centralise

## Labels

`module:1-foundations`, `area:kernel`, `area:errors`, `type:architecture`, `type:feature`, `priority:p0`, `status:ready`

## Objectif

Centraliser la transformation des exceptions en erreurs exploitables par HTTP, CLI et Preview.

## Travail attendu

- Creer `ExceptionHandlerInterface`.
- Ajouter `report(Throwable $e): void`.
- Ajouter `render(Throwable $e, mixed $context = null): mixed`.
- Differencier mode debug et mode production.
- Prevoir une structure d'erreur generique sans dependre de `veltphp/http`.
- Documenter comment HTTP transformera cette structure en HTML ou JSON.

## Criteres d'acceptation

- Une exception PHP generique peut etre reportee.
- En mode debug, le message est disponible pour le developpeur.
- En mode production, le message public reste neutre.
- Le handler ne depend pas de `Response`.

## Definition of Done

- Interface et handler par defaut crees.
- Tests debug/production.
- Integration avec le lifecycle documentee.

