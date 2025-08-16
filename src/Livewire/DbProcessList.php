<?php

namespace Kefir500\PulseDbProcesses\Livewire;

use Illuminate\Support\Facades\DB;
use Laravel\Pulse\Livewire\Card;
use Livewire\Attributes\Lazy;

#[Lazy]
class DbProcessList extends Card
{
    public $connection = 'default';

    public function render()
    {
        [$processes, $time, $runAt] = $this->remember(
            fn () => collect(DB::connection($this->connection)->select('SHOW PROCESSLIST')),
            $this->connection,
        );

        return view('pulse-db-processes::livewire.db-process-list', [
            'processes' => $processes,
            'time' => $time,
            'runAt' => $runAt,
        ]);
    }
}
