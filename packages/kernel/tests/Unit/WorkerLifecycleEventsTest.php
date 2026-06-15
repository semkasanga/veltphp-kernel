<?php

declare(strict_types=1);

namespace Velt\Kernel\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Velt\Kernel\Contracts\WorkerLifecycleEventsInterface;

final class WorkerLifecycleEventsTest extends TestCase
{
    public function test_worker_lifecycle_event_names_are_stable(): void
    {
        $events = [
            WorkerLifecycleEventsInterface::WORKER_STARTED,
            WorkerLifecycleEventsInterface::WORKER_ITERATION_STARTED,
            WorkerLifecycleEventsInterface::WORKER_ITERATION_ENDED,
            WorkerLifecycleEventsInterface::WORKER_MEMORY_CHECKED,
            WorkerLifecycleEventsInterface::WORKER_STOPPING,
            WorkerLifecycleEventsInterface::WORKER_STOPPED,
        ];

        $this->assertSame(
            [
                'worker.started',
                'worker.iteration.started',
                'worker.iteration.ended',
                'worker.memory.checked',
                'worker.stopping',
                'worker.stopped',
            ],
            $events
        );

        $this->assertCount(
            6,
            array_unique($events)
        );
    }
}
