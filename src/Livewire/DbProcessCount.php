<?php

namespace Kefir500\PulseDbProcesses\Livewire;

use Laravel\Pulse\Livewire\Card;
use Livewire\Attributes\Lazy;
use Livewire\Livewire;

#[Lazy]
class DbProcessCount extends Card
{
    public function render()
    {
        [$connections, $time, $runAt] = $this->remember(
            fn () => $this->graph(['db_process_count'], 'max'),
        );

        if (Livewire::isLivewireRequest()) {
            $this->dispatch('db-process-count-chart-update', connections: $connections);
        }

        return view('pulse-db-processes::livewire.db-process-count', [
            'connections' => $connections,
            'time' => $time,
            'runAt' => $runAt,
        ]);
    }
}
