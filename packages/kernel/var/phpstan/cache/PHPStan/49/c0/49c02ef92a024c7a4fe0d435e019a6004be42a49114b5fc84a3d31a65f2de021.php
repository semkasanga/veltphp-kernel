<?php declare(strict_types = 1);

// odsl-C:\Users\Sem\Desktop\travail\Projets personnels\Velt\veltphp-kernel\packages\kernel\src\Contracts\ConnectionInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Velt\Kernel\Contracts\ConnectionInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.1-8.3-879197ec4388f85df22b9a867bd7f97381e638de28d342a8e6a11b60fa6c6f5d',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Velt\\Kernel\\Contracts\\ConnectionInterface',
        'filename' => 'C:/Users/Sem/Desktop/travail/Projets personnels/Velt/veltphp-kernel/packages/kernel/src/Contracts/ConnectionInterface.php',
      ),
    ),
    'namespace' => 'Velt\\Kernel\\Contracts',
    'name' => 'Velt\\Kernel\\Contracts\\ConnectionInterface',
    'shortName' => 'ConnectionInterface',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Represents a database connection abstraction.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 10,
    'endLine' => 36,
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
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the connection name.
 */',
        'startLine' => 15,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 35,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Velt\\Kernel\\Contracts',
        'declaringClassName' => 'Velt\\Kernel\\Contracts\\ConnectionInterface',
        'implementingClassName' => 'Velt\\Kernel\\Contracts\\ConnectionInterface',
        'currentClassName' => 'Velt\\Kernel\\Contracts\\ConnectionInterface',
        'aliasName' => NULL,
      ),
      'driver' => 
      array (
        'name' => 'driver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Velt\\Kernel\\Contracts\\DriverInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the database driver abstraction.
 */',
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 46,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Velt\\Kernel\\Contracts',
        'declaringClassName' => 'Velt\\Kernel\\Contracts\\ConnectionInterface',
        'implementingClassName' => 'Velt\\Kernel\\Contracts\\ConnectionInterface',
        'currentClassName' => 'Velt\\Kernel\\Contracts\\ConnectionInterface',
        'aliasName' => NULL,
      ),
      'connect' => 
      array (
        'name' => 'connect',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Opens the connection when needed.
 */',
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 36,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Velt\\Kernel\\Contracts',
        'declaringClassName' => 'Velt\\Kernel\\Contracts\\ConnectionInterface',
        'implementingClassName' => 'Velt\\Kernel\\Contracts\\ConnectionInterface',
        'currentClassName' => 'Velt\\Kernel\\Contracts\\ConnectionInterface',
        'aliasName' => NULL,
      ),
      'disconnect' => 
      array (
        'name' => 'disconnect',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Closes the connection.
 */',
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 39,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Velt\\Kernel\\Contracts',
        'declaringClassName' => 'Velt\\Kernel\\Contracts\\ConnectionInterface',
        'implementingClassName' => 'Velt\\Kernel\\Contracts\\ConnectionInterface',
        'currentClassName' => 'Velt\\Kernel\\Contracts\\ConnectionInterface',
        'aliasName' => NULL,
      ),
      'isConnected' => 
      array (
        'name' => 'isConnected',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns true when the connection is open.
 */',
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 40,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Velt\\Kernel\\Contracts',
        'declaringClassName' => 'Velt\\Kernel\\Contracts\\ConnectionInterface',
        'implementingClassName' => 'Velt\\Kernel\\Contracts\\ConnectionInterface',
        'currentClassName' => 'Velt\\Kernel\\Contracts\\ConnectionInterface',
        'aliasName' => NULL,
      ),
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