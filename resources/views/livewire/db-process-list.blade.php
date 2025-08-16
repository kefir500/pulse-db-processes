<x-pulse::card :cols="$cols" :rows="$rows" :class="$class" wire:poll.30s="">
    <x-pulse::card-header
        name="DB Process List"
        details="{{ $this->connection }}"
        x-bind:title="`Time: {{ number_format($time) }}ms; Run at: ${formatDate('{{ $runAt }}')};`"
    >
        <x-slot:icon>
            <x-pulse::icons.circle-stack />
        </x-slot:icon>
        <x-slot:actions>
            <div>
                <span class="text-xl uppercase font-bold text-gray-700 dark:text-gray-300 tabular-nums">
                    {{ count($processes) }}
                </span>
                <span class="text-xs uppercase font-bold text-gray-500 dark:text-gray-400">
                    Processes
                </span>
            </div>
        </x-slot:actions>
    </x-pulse::card-header>

    <x-pulse::scroll :expand="$expand">
        @if ($processes->isEmpty())
            <x-pulse::no-results />
        @else
            <x-pulse::table>
                <colgroup>
                    <col width="0%" />
                    <col width="0%" />
                    <col width="0%" />
                    <col width="0%" />
                    <col width="0%" />
                    <col width="0%" />
                    <col width="0%" />
                    <col width="100%" />
                    <col width="0%" />
                </colgroup>
                <x-pulse::thead>
                    <tr>
                        <x-pulse::th class="text-right">ID</x-pulse::th>
                        <x-pulse::th>User</x-pulse::th>
                        <x-pulse::th>Host</x-pulse::th>
                        <x-pulse::th>DB</x-pulse::th>
                        <x-pulse::th>Command</x-pulse::th>
                        <x-pulse::th class="text-right">Time</x-pulse::th>
                        <x-pulse::th>State</x-pulse::th>
                        <x-pulse::th>Info</x-pulse::th>
                        <x-pulse::th class="text-right">Progress</x-pulse::th>
                    </tr>
                </x-pulse::thead>
                <tbody>
                    @foreach ($processes->take(100) as $process)
                        <tr wire:key="db-process-{{ $process->Id }}-spacer" class="h-2 first:h-0"></tr>
                        <tr wire:key="db-process-{{ $process->Id }}-row">
                            <x-pulse::td numeric>
                                <code class="block text-xs text-gray-900 dark:text-gray-100 truncate">
                                    {{ $process->Id }}
                                </code>
                            </x-pulse::td>
                            <x-pulse::td>
                                <code class="block text-xs text-gray-900 dark:text-gray-100 truncate">
                                    {{ $process->User }}
                                </code>
                            </x-pulse::td>
                            <x-pulse::td>
                                <code class="block text-xs text-gray-900 dark:text-gray-100 truncate">
                                    {{ $process->Host }}
                                </code>
                            </x-pulse::td>
                            <x-pulse::td>
                                <code class="block text-xs text-gray-900 dark:text-gray-100 truncate">
                                    {{ $process->db }}
                                </code>
                            </x-pulse::td>
                            <x-pulse::td>
                                <code class="block text-xs text-gray-900 dark:text-gray-100 truncate">
                                    {{ $process->Command }}
                                </code>
                            </x-pulse::td>
                            <x-pulse::td numeric>
                                <code class="block text-xs text-gray-900 dark:text-gray-100 truncate">
                                    {{ number_format($process->Time) }}
                                </code>
                            </x-pulse::td>
                            <x-pulse::td>
                                <code class="block text-xs text-gray-900 dark:text-gray-100 truncate">
                                    {{ $process->State }}
                                </code>
                            </x-pulse::td>
                            <x-pulse::td>
                                <code class="block text-xs text-gray-900 dark:text-gray-100 truncate">
                                    {{ $process->Info }}
                                </code>
                            </x-pulse::td>
                            <x-pulse::td numeric>
                                <code class="block text-xs text-gray-900 dark:text-gray-100 truncate">
                                    {{ number_format($process->Progress) }}
                                </code>
                            </x-pulse::td>
                        </tr>
                    @endforeach
                </tbody>
            </x-pulse::table>
        @endif

        @if ($processes->count() > 100)
            <div class="mt-2 text-xs text-gray-400 text-center">Limited to 100 entries</div>
        @endif
    </x-pulse::scroll>
</x-pulse::card>
