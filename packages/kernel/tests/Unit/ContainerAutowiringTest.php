<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Velt\Kernel\Container;
use Velt\Kernel\Exceptions\ContainerResolutionException;
use Velt\Kernel\Exceptions\ServiceNotFoundException;

final class ContainerAutowiringTest extends TestCase
{
    public function test_it_autowires_concrete_dependencies(): void
    {
        $container = new Container();

        $service = $container->get(
            ConcreteConsumer::class
        );

        $this->assertInstanceOf(
            ConcreteConsumer::class,
            $service
        );

        $this->assertInstanceOf(
            ConcreteDependency::class,
            $service->dependency
        );
    }

    public function test_it_resolves_bound_interface_dependencies(): void
    {
        $container = new Container();

        $container->bind(
            LoggerContract::class,
            LoggerImplementation::class
        );

        $service = $container->get(
            InterfaceConsumer::class
        );

        $this->assertInstanceOf(
            InterfaceConsumer::class,
            $service
        );

        $this->assertInstanceOf(
            LoggerImplementation::class,
            $service->logger
        );
    }

    public function test_it_uses_default_values_for_optional_parameters(): void
    {
        $container = new Container();

        $service = $container->get(
            OptionalScalarConsumer::class
        );

        $this->assertSame(
            'sqlite::memory:',
            $service->dsn
        );
    }

    public function test_it_allows_nullable_dependencies_to_fall_back_to_null(): void
    {
        $container = new Container();

        $service = $container->get(
            NullableInterfaceConsumer::class
        );

        $this->assertNull(
            $service->logger
        );
    }

    public function test_it_throws_when_an_interface_dependency_is_not_bound(): void
    {
        $container = new Container();

        $this->expectException(
            ContainerResolutionException::class
        );

        $container->get(
            UnboundInterfaceConsumer::class
        );
    }

    public function test_it_throws_when_an_abstract_dependency_cannot_be_instantiated(): void
    {
        $container = new Container();

        $this->expectException(
            ContainerResolutionException::class
        );

        $container->get(
            AbstractDependencyConsumer::class
        );
    }

    public function test_it_throws_when_dependencies_are_circular(): void
    {
        $container = new Container();

        $this->expectException(
            ContainerResolutionException::class
        );

        $container->get(
            CircularConsumerA::class
        );
    }

    public function test_it_reports_unresolvable_constructor_dependencies_as_unavailable(): void
    {
        $container = new Container();

        $this->assertFalse(
            $container->has(
                UnboundInterfaceConsumer::class
            )
        );

        $this->assertFalse(
            $container->has(
                MandatoryScalarConsumer::class
            )
        );
    }

    public function test_it_reports_circular_dependencies_as_unavailable(): void
    {
        $container = new Container();

        $this->assertFalse(
            $container->has(
                CircularConsumerA::class
            )
        );
    }

    public function test_it_reports_missing_services_explicitly(): void
    {
        $container = new Container();

        $this->expectException(
            ServiceNotFoundException::class
        );

        $container->get(
            'missing.service'
        );
    }

    public function test_it_resolves_aliases_to_the_same_service(): void
    {
        $container = new Container();

        $service = new \stdClass();

        $container->instance(
            'service',
            $service
        );

        $container->alias(
            'service',
            'service.alias'
        );

        $this->assertSame(
            $service,
            $container->get('service.alias')
        );
    }

    public function test_has_returns_false_for_uninstantiable_abstract_classes(): void
    {
        $container = new Container();

        $this->assertFalse(
            $container->has(AbstractDependency::class)
        );
    }
}

final class ConcreteDependency
{
}

final class ConcreteConsumer
{
    public function __construct(
        public readonly ConcreteDependency $dependency
    ) {
    }
}

interface LoggerContract
{
}

final class LoggerImplementation implements LoggerContract
{
}

final class InterfaceConsumer
{
    public function __construct(
        public readonly LoggerContract $logger
    ) {
    }
}

final class OptionalScalarConsumer
{
    public function __construct(
        public readonly string $dsn = 'sqlite::memory:'
    ) {
    }
}

final class NullableInterfaceConsumer
{
    public function __construct(
        public readonly ?LoggerContract $logger = null
    ) {
    }
}

final class UnboundInterfaceConsumer
{
    public function __construct(
        public readonly LoggerContract $logger
    ) {
    }
}

abstract class AbstractDependency
{
}

final class AbstractDependencyConsumer
{
    public function __construct(
        public readonly AbstractDependency $dependency
    ) {
    }
}

final class CircularConsumerA
{
    public function __construct(
        public readonly CircularConsumerB $dependency
    ) {
    }
}

final class CircularConsumerB
{
    public function __construct(
        public readonly CircularConsumerA $dependency
    ) {
    }
}

final class MandatoryScalarConsumer
{
    public function __construct(
        public readonly string $dsn
    ) {
    }
}
