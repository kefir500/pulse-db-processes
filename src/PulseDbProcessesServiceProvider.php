<?php

namespace Kefir500\PulseDbProcesses;

use Kefir500\PulseDbProcesses\Livewire\DbProcessCount;
use Kefir500\PulseDbProcesses\Livewire\DbProcessList;
use Livewire\LivewireManager;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PulseDbProcessesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('pulse-db-processes')
            ->hasViews();
    }

    public function packageBooted(): void
    {
        $this->callAfterResolving('livewire', function (LivewireManager $livewire) {
            $livewire->component('db-process-count', DbProcessCount::class);
            $livewire->component('db-process-list', DbProcessList::class);
        });
    }
}
