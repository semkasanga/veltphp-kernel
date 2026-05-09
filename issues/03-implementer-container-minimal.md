# Issue 03 - Implementer le container minimal

## Labels

`module:1-foundations`, `area:kernel`, `type:feature`, `type:tests`, `priority:p0`, `status:ready`

## Objectif

Fournir un container de services minimal pour enregistrer et resoudre les dependances internes de Velt.

## Fonctionnalites attendues

- `bind(string $id, callable|string $resolver): void`
- `singleton(string $id, callable|string $resolver): void`
- `instance(string $id, object $instance): void`
- `has(string $id): bool`
- `get(string $id): mixed`

## Comportement attendu

- `bind` cree une nouvelle instance a chaque resolution si le resolver le demande.
- `singleton` conserve l'instance apres la premiere resolution.
- `instance` enregistre directement un objet existant.
- `get` lance une exception dediee si le service n'existe pas.

## Contraintes

- Pas d'autowiring complexe dans le MVP.
- Pas de reflexion avancee obligatoire.
- Les erreurs doivent etre explicites.

## Criteres d'acceptation

- Le container peut enregistrer un service simple.
- Le container peut enregistrer un singleton.
- Le container peut retourner une instance deja creee.
- Une exception claire est lancee si le service est absent.

## Definition of Done

- Implementation terminee.
- Tests unitaires couvrant bind, singleton, instance, has et erreur.
- Documentation avec exemple d'utilisation.

