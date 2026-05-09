# Issue 07 - Ajouter Env loader et modes application

## Labels

`module:1-foundations`, `area:kernel`, `area:config`, `type:feature`, `priority:p0`, `status:ready`

## Objectif

Ajouter une gestion minimale des variables d'environnement pour que la configuration, le debug, la database et les tests reposent sur une base stable.

## Travail attendu

- Creer `EnvRepositoryInterface`.
- Charger un fichier `.env` simple depuis le `basePath`.
- Supporter les valeurs par defaut avec `env('APP_ENV', 'local')` ou equivalent.
- Definir les modes officiels : `local`, `testing`, `production`.
- Exposer `app()->environment()` ou equivalent.
- Ne jamais afficher les secrets dans les erreurs.

## Contraintes

- Le Module 1 peut utiliser une implementation native simple ou wrapper propre autour d'une dependance dediee.
- Pas de config cache avance dans cette issue.
- Pas de gestion multi-fichiers complexe obligatoire.

## Criteres d'acceptation

- `APP_ENV=testing` est lisible pendant les tests.
- `APP_DEBUG=false` peut etre converti en booleen.
- Une variable absente retourne une valeur par defaut.
- Les secrets ne sont pas dumpes dans les messages d'erreur.

## Definition of Done

- Loader env minimal implemente.
- Tests avec dossier temporaire.
- Documentation `.env.example` minimale.

