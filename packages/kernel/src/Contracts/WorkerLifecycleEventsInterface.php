<?php

declare(strict_types=1);

namespace Velt\Kernel\Contracts;

/**
 * Centralized worker lifecycle hook names.
 *
 * Future worker runtimes can dispatch these events without the Kernel
 * knowing any queue or process implementation details.
 */
interface WorkerLifecycleEventsInterface
{
    public const WORKER_STARTED = 'worker.started';

    public const WORKER_ITERATION_STARTED = 'worker.iteration.started';

    public const WORKER_ITERATION_ENDED = 'worker.iteration.ended';

    public const WORKER_MEMORY_CHECKED = 'worker.memory.checked';

    public const WORKER_STOPPING = 'worker.stopping';

    public const WORKER_STOPPED = 'worker.stopped';
}
