<?php declare(strict_types = 1);

// odsl-C:\Users\Sem\Desktop\travail\Projets personnels\Velt\veltphp-kernel\packages\kernel\src\Contracts\WorkerLifecycleEventsInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Velt\Kernel\Contracts\WorkerLifecycleEventsInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3-31659fe44269c46bcf7a1ceb418bd147a9235ce74219ec6942cf031130966d99',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Velt\\Kernel\\Contracts\\WorkerLifecycleEventsInterface',
        'filename' => 'C:/Users/Sem/Desktop/travail/Projets personnels/Velt/veltphp-kernel/packages/kernel/src/Contracts/WorkerLifecycleEventsInterface.php',
      ),
    ),
    'namespace' => 'Velt\\Kernel\\Contracts',
    'name' => 'Velt\\Kernel\\Contracts\\WorkerLifecycleEventsInterface',
    'shortName' => 'WorkerLifecycleEventsInterface',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Centralized worker lifecycle hook names.
 *
 * Future worker runtimes can dispatch these events without the Kernel
 * knowing any queue or process implementation details.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 13,
    'endLine' => 26,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'WORKER_STARTED' => 
      array (
        'declaringClassName' => 'Velt\\Kernel\\Contracts\\WorkerLifecycleEventsInterface',
        'implementingClassName' => 'Velt\\Kernel\\Contracts\\WorkerLifecycleEventsInterface',
        'name' => 'WORKER_STARTED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'worker.started\'',
          'attributes' => 
          array (
            'startLine' => 15,
            'endLine' => 15,
            'startTokenPos' => 31,
            'startFilePos' => 326,
            'endTokenPos' => 31,
            'endFilePos' => 341,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 15,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 51,
      ),
      'WORKER_ITERATION_STARTED' => 
      array (
        'declaringClassName' => 'Velt\\Kernel\\Contracts\\WorkerLifecycleEventsInterface',
        'implementingClassName' => 'Velt\\Kernel\\Contracts\\WorkerLifecycleEventsInterface',
        'name' => 'WORKER_ITERATION_STARTED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'worker.iteration.started\'',
          'attributes' => 
          array (
            'startLine' => 17,
            'endLine' => 17,
            'startTokenPos' => 42,
            'startFilePos' => 389,
            'endTokenPos' => 42,
            'endFilePos' => 414,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 71,
      ),
      'WORKER_ITERATION_ENDED' => 
      array (
        'declaringClassName' => 'Velt\\Kernel\\Contracts\\WorkerLifecycleEventsInterface',
        'implementingClassName' => 'Velt\\Kernel\\Contracts\\WorkerLifecycleEventsInterface',
        'name' => 'WORKER_ITERATION_ENDED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'worker.iteration.ended\'',
          'attributes' => 
          array (
            'startLine' => 19,
            'endLine' => 19,
            'startTokenPos' => 53,
            'startFilePos' => 460,
            'endTokenPos' => 53,
            'endFilePos' => 483,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 67,
      ),
      'WORKER_MEMORY_CHECKED' => 
      array (
        'declaringClassName' => 'Velt\\Kernel\\Contracts\\WorkerLifecycleEventsInterface',
        'implementingClassName' => 'Velt\\Kernel\\Contracts\\WorkerLifecycleEventsInterface',
        'name' => 'WORKER_MEMORY_CHECKED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'worker.memory.checked\'',
          'attributes' => 
          array (
            'startLine' => 21,
            'endLine' => 21,
            'startTokenPos' => 64,
            'startFilePos' => 528,
            'endTokenPos' => 64,
            'endFilePos' => 550,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 65,
      ),
      'WORKER_STOPPING' => 
      array (
        'declaringClassName' => 'Velt\\Kernel\\Contracts\\WorkerLifecycleEventsInterface',
        'implementingClassName' => 'Velt\\Kernel\\Contracts\\WorkerLifecycleEventsInterface',
        'name' => 'WORKER_STOPPING',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'worker.stopping\'',
          'attributes' => 
          array (
            'startLine' => 23,
            'endLine' => 23,
            'startTokenPos' => 75,
            'startFilePos' => 589,
            'endTokenPos' => 75,
            'endFilePos' => 605,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 53,
      ),
      'WORKER_STOPPED' => 
      array (
        'declaringClassName' => 'Velt\\Kernel\\Contracts\\WorkerLifecycleEventsInterface',
        'implementingClassName' => 'Velt\\Kernel\\Contracts\\WorkerLifecycleEventsInterface',
        'name' => 'WORKER_STOPPED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'worker.stopped\'',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 25,
            'startTokenPos' => 86,
            'startFilePos' => 643,
            'endTokenPos' => 86,
            'endFilePos' => 658,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 51,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
    ),
    'traitsData' => 
    array (
      'aliases' => 
      array (
      ),
      'modifiers' => 
      array (
      ),
      'precedences' => 
      array (
      ),
      'hashes' => 
      array (
      ),
    ),
  ),
));