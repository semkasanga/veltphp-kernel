# Issue 04 - Ajouter configuration et bootstrap application

## Labels

`module:1-foundations`, `area:kernel`, `type:feature`, `priority:p0`, `status:ready`

## Objectif

Finaliser le noyau minimal pour que les autres composants puissent demarrer une application Velt de maniere stable.

## Pourquoi cette issue est obligatoire

Les contrats et le container ne suffisent pas. Pour qu'un framework soit utilisable, il faut aussi un objet application capable de connaitre le chemin racine, charger la configuration et exposer le container.

## Travail attendu

- Creer `Application`.
- Creer `ConfigRepository`.
- Ajouter support de notation par points : `app.name`, `database.default`.
- Ajouter methode `basePath()`.
- Ajouter methode `config()`.
- Ajouter methode `container()`.
- Ajouter bootstrap minimal qui enregistre les services de base.

## Contraintes

- Ne pas charger HTTP, UI, CLI, Database ou Preview depuis le kernel.
- Ne pas implementer tout le systeme de service providers dans cette issue ; il est traite par l'issue 05.
- Preparer l'integration `.env`, mais garder le loader detaille dans l'issue 07.

## Criteres d'acceptation

- Une application peut etre instanciee avec un chemin racine.
- Une valeur de configuration peut etre lue avec notation par points.
- Une valeur absente retourne une valeur par defaut.
- Le container est accessible depuis l'application.
- Les tests prouvent que l'application ne depend d'aucun autre composant Velt.

## Definition of Done

- `Application` implemente.
- `ConfigRepository` implemente.
- Tests unitaires verts.
- README mis a jour avec un exemple de bootstrap.
