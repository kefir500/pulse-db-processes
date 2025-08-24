<?php

namespace Kefir500\PulseDbProcesses\Recorders;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Facades\DB;
use Laravel\Pulse\Events\SharedBeat;
use Laravel\Pulse\Pulse;

class DbProcessCount
{
    /**
     * The events to listen for.
     *
     * @var class-string
     */
    public string $listen = SharedBeat::class;

    /**
     * Create a new recorder instance.
     */
    public function __construct(
        protected Pulse $pulse,
        protected Repository $config,
    ) {}

    /**
     * Record the database process count.
     */
    public function record(SharedBeat $event): void
    {
        if ($event->time->second !== 0) {
            return;
        }

        $connections = $this->config->get('pulse.recorders.' . self::class . '.connections', ['default']);

        foreach ($connections as $connection) {
            $processes = DB::connection($connection)->table('information_schema.PROCESSLIST')->get();

            $this->pulse->record(
                type: 'db_process_count',
                key: $connection,
                value: $processes->count(),
                timestamp: $event->time,
            )->max()->onlyBuckets();

            $this->pulse->record(
                type: 'db_process_time',
                key: $connection,
                value: $processes->whereIn('COMMAND', $this->commands)->max('TIME'),
                timestamp: $event->time,
            )->max()->onlyBuckets();
        }
    }
}
