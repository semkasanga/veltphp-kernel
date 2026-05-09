# Issue 02 - Creer les contrats fondamentaux

## Labels

`module:1-foundations`, `area:kernel`, `type:feature`, `priority:p0`, `status:ready`

## Objectif

Definir les interfaces communes qui serviront de contrat entre les sous-modules Velt.

## Contrats a creer

- `ApplicationInterface`
- `ContainerInterface`
- `ConfigRepositoryInterface`
- `RenderableInterface`
- `ArrayableInterface`
- `JsonableInterface`

## Role des contrats

`ApplicationInterface` represente l'application Velt chargee. Elle expose au minimum le chemin racine du projet, la configuration et le container.

`ContainerInterface` permet de lier et resoudre des services par cle ou classe.

`ConfigRepositoryInterface` permet de lire des valeurs de configuration avec notation par points, par exemple `database.connections.mysql.host`.

`RenderableInterface`, `ArrayableInterface` et `JsonableInterface` seront utiles au moteur UI, aux responses HTTP et a la preview mobile.

## Contraintes

- Les interfaces doivent rester generiques.
- Ne pas faire reference a une implementation concrete.
- Ne pas imposer Laravel ou Symfony.
- Documenter chaque methode avec PHPDoc.

## Criteres d'acceptation

- Les interfaces sont utilisables par les autres sous-modules sans dependance circulaire.
- Les noms sont stables et coherents avec le namespace `Velt\Kernel\Contracts`.
- Les tests peuvent verifier qu'une implementation fake respecte les contrats.

## Definition of Done

- Contrats crees.
- PHPDoc ajoutee.
- Tests unitaires avec implementations factices.
- README mis a jour avec la liste des contrats publics.

