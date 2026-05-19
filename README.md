# Sous-module 01 - Kernel Contracts

## Mission

Ce sous-module definit le coeur minimal de Velt. Il contient les contrats, les conventions, les exceptions communes, le container de services minimal et les helpers dont les autres composants auront besoin.

Il doit rester petit. Son role n'est pas de devenir un framework complet, mais de fournir un langage commun aux autres modules.

Apres audit, le kernel doit aussi cadrer les fondations invisibles du framework : cycle de vie application, service providers, events synchrones internes, environnement, exception handling et evolution du container. Ces elements ne sont pas des features avancees ; ce sont les rails qui permettront aux modules HTTP, UI, Database, CLI et Preview de s'enregistrer proprement sans couplage.

## Perimetre

Inclus :

- structure de package `velt/kernel` ;
- contrats de base ;
- container minimal avec trajectoire PSR-11 ;
- gestion simple de configuration ;
- loader `.env` minimal ;
- modes `local`, `testing`, `production` ;
- exceptions communes ;
- bootstrap d'application ;
- lifecycle `register`, `boot`, `handle`, `terminate` ;
- service providers minimaux ;
- event dispatcher synchrone minimal ;
- exception handler centralise ;
- helpers strictement necessaires.

Exclus :

- routing HTTP ;
- rendu UI ;
- acces database ;
- generation CLI avancee ;
- preview mobile.

## Comment tester sans les autres modules

Le kernel ne doit attendre aucun autre package Velt. Les tests doivent donc utiliser uniquement PHP, PHPUnit et des classes factices locales.

- Pour tester le container, creer de petites classes fake dans `tests/Fixtures`.
- Pour tester les providers, creer un `FakeServiceProvider` qui enregistre une valeur simple dans le container.
- Pour tester les events, creer un evenement `FakeBootedEvent` et un listener qui ajoute une entree dans un tableau.
- Pour tester l'exception handler, utiliser une exception generique et verifier qu'elle devient un objet d'erreur abstrait ou une structure compatible response, sans dependre de `veltphp/http`.
- Pour tester `.env`, utiliser un dossier temporaire avec un fichier `.env.testing` ou `.env` minimal.

Le kernel est termine seulement s'il peut prouver qu'il ne depend d'aucune classe `Velt\Http`, `Velt\UI`, `Velt\Database`, `Velt\Cli` ou `Velt\Preview`.

## Issues

- [Issue 01 - Initialiser le package Kernel](issues/01-initialiser-package-kernel.md)
- [Issue 02 - Creer les contrats fondamentaux](issues/02-creer-contrats-fondamentaux.md)
- [Issue 03 - Implementer le container minimal](issues/03-implementer-container-minimal.md)
- [Issue 04 - Ajouter configuration et bootstrap application](issues/04-ajouter-configuration-bootstrap-application.md)
- [Issue 05 - Ajouter service providers et lifecycle application](issues/05-service-providers-lifecycle-application.md)
- [Issue 06 - Ajouter EventDispatcher synchrone minimal](issues/06-event-dispatcher-synchrone-minimal.md)
- [Issue 07 - Ajouter Env loader et modes application](issues/07-env-loader-modes-application.md)
- [Issue 08 - Ajouter ExceptionHandler centralise](issues/08-exception-handler-centralise.md)
- [Issue 09 - Renforcer container avec autowiring prudent et compatibilite PSR-11](issues/09-container-autowiring-prudent-psr11.md)
