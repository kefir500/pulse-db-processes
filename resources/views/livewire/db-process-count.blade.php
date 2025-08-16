<x-pulse::card :cols="$cols" :rows="$rows" :class="$class">
    <x-pulse::card-header
        name="DB Process Count"
        details="past {{ $this->periodForHumans() }}"
        x-bind:title="`Time: {{ number_format($time) }}ms; Run at: ${formatDate('{{ $runAt }}')};`"
    >
        <x-slot:icon>
            <x-pulse::icons.circle-stack />
        </x-slot:icon>
    </x-pulse::card-header>

    <x-pulse::scroll :expand="$expand" wire:poll.30s="">
        @if ($connections->flatten()->isEmpty())
            <x-pulse::no-results />
        @else
            <div class="mt-3 relative">
                @php
                    $max = (int)$connections->pluck('db_process_count')->flatten()->max();
                @endphp
                <div class="absolute -left-px -top-2 max-w-fit h-4 flex items-center px-1 text-xs leading-none text-white font-bold bg-purple-500 rounded after:[--triangle-size:4px] after:border-l-purple-500 after:absolute after:right-[calc(-1*var(--triangle-size))] after:top-[calc(50%-var(--triangle-size))] after:border-t-[length:var(--triangle-size)] after:border-b-[length:var(--triangle-size)] after:border-l-[length:var(--triangle-size)] after:border-transparent">
                    {{ number_format($max) }}
                </div>

                <div
                    wire:ignore
                    class="h-32"
                    x-data="dbProcessCountChart({
                        connections: @js($connections),
                    })"
                >
                    <canvas x-ref="canvas" class="ring-1 ring-gray-900/5 dark:ring-gray-100/10 bg-gray-50 dark:bg-gray-800 rounded-md shadow-sm"></canvas>
                </div>
            </div>
        @endif
    </x-pulse::scroll>
</x-pulse::card>

@script
<script>
Alpine.data('dbProcessCountChart', (config) => ({
    colors: [
        '#9333ea',
        '#db2777',
        '#d97706',
        '#65a30d',
        '#0891b2',
        '#2563eb',
    ],
    init() {
        const chart = new Chart(
            this.$refs.canvas,
            {
                type: 'line',
                data: {
                    labels: this.labels(config.connections),
                    datasets: this.datasets(config.connections),
                },
                options: {
                    maintainAspectRatio: false,
                    layout: {
                        autoPadding: false,
                        padding: {
                            top: 1,
                        },
                    },
                    datasets: {
                        line: {
                            borderWidth: 2,
                            borderCapStyle: 'round',
                            pointHitRadius: 10,
                            pointStyle: false,
                            tension: 0.2,
                            spanGaps: false,
                            segment: {
                                borderColor: (ctx) => ctx.p0.raw === 0 && ctx.p1.raw === 0 ? 'transparent' : undefined,
                            },
                        },
                    },
                    scales: {
                        x: {
                            display: false,
                        },
                        y: {
                            display: false,
                            min: 0,
                            max: this.max(config.connections),
                        },
                    },
                    plugins: {
                        legend: {
                            position: 'left',
                            labels: {
                                boxWidth: 10,
                                boxHeight: 1,
                            },
                        },
                        tooltip: {
                            mode: 'index',
                            position: 'nearest',
                            intersect: false,
                            callbacks: {
                                label: () => null,
                                beforeBody: (context) => context
                                    .map(item => `${item.dataset.label}: ${item.formattedValue}`)
                                    .join(', '),
                            },
                        },
                    },
                },
            },
        );

        Livewire.on('db-process-count-chart-update', ({ connections }) => {
            if (chart === undefined) {
                return;
            }

            chart.data.labels = this.labels(connections);
            chart.options.scales.y.max = this.max(connections);
            this.datasets(connections).forEach((dataset, index) => {
                chart.data.datasets[index].data = dataset.data;
            });
            chart.update();
        });
    },
    labels(connections) {
        return Object.keys(Object.values(connections)[0].db_process_count).map(formatDate);
    },
    datasets(connections) {
        return Object.entries(connections).map(([connection, data], index) => ({
            label: connection,
            borderColor: this.colors[index % this.colors.length],
            data: Object.values(data.db_process_count),
        }));
    },
    max(connections) {
        return Math.max(...Object.values(connections).map(
            (connection) => Math.max(...Object.values(connection.db_process_count))
        ));
    },
}));
</script>
@endscript
