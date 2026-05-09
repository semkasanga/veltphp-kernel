# Issue 01 - Initialiser le package Kernel

## Labels

`module:1-foundations`, `area:kernel`, `type:architecture`, `priority:p0`, `status:ready`

## Objectif

Creer la base du composant `velt/kernel`, qui servira de dependance commune aux autres sous-modules du Module 1.

## Contexte

Velt doit evoluer comme un framework modulaire inspire des composants Laravel. Le kernel doit donc etre autonome, installable par Composer plus tard, et suffisamment stable pour que les equipes HTTP, CLI, UI, Database et Preview puissent l'utiliser sans couplage fort.

## Travail attendu

- Creer un `composer.json` pour le package kernel.
- Definir le namespace `Velt\Kernel`.
- Ajouter une structure source claire :
  - `src/Application.php`
  - `src/Config/ConfigRepository.php`
  - `src/Contracts/`
  - `src/Exceptions/`
  - `tests/`
- Ajouter un README package avec installation future, responsabilites et exemple minimal.
- Definir la version PHP cible : PHP 8.2 minimum.
- Configurer PHPUnit pour les tests unitaires du kernel.

## Contraintes

- Ne pas ajouter Symfony, Laravel ou autre framework complet dans ce package.
- Eviter toute dependance HTTP, database ou UI.
- Garder le package petit et stable.

## Criteres d'acceptation

- Le package peut etre installe localement via Composer path repository dans un futur skeleton.
- `Application::VERSION` ou equivalent expose une version lisible.
- Les tests unitaires du package peuvent etre lances.
- La documentation explique clairement ce que le kernel fait et ne fait pas.

## Definition of Done

- Structure creee.
- Autoload PSR-4 fonctionnel.
- README complet.
- Premier test PHPUnit vert.

