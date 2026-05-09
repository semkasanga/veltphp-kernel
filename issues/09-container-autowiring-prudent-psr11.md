# Issue 09 - Renforcer container avec autowiring prudent et compatibilite PSR-11

## Labels

`module:1-foundations`, `area:kernel`, `area:container`, `type:feature`, `priority:p1`, `status:ready`

## Objectif

Ameliorer le container sans le rendre magique : singleton, aliases, resolution par classe et compatibilite avec les principes PSR-11.

## Travail attendu

- Garder `bind`, `singleton`, `instance`, `has`, `get`.
- Ajouter `alias(string $abstract, string $alias): void` si utile.
- Autoriser la resolution d'une classe concrete sans binding si son constructeur est resolvable.
- Supporter constructor injection simple via Reflection.
- Lancer une exception claire si une dependance scalaire ou inconnue ne peut pas etre resolue.
- Aligner les noms et comportements avec PSR-11 autant que possible.

## Contraintes

- Pas de contextual bindings dans le Module 1.
- Pas d'attributs PHP complexes.
- Pas de magie silencieuse : les erreurs doivent expliquer quelle dependance manque.

## Criteres d'acceptation

- Une classe sans constructeur est resolue.
- Une classe avec dependance objet est resolue.
- Un singleton reste identique entre deux resolutions.
- Une dependance non resolvable produit une exception explicite.

## Definition of Done

- Container renforce.
- Tests autowiring simples.
- Documentation des limites.

