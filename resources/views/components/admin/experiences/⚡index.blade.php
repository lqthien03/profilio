<?php

use App\Models\Experience;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new
    #[Layout('layouts.admin')]
    class extends Component {
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function getExperiencesProperty()
    {
        return Experience::query()
            ->when(
                $this->search,
                function ($query) {
                    $query->where(function ($query) {
                        $query
                            ->where('company', 'like', '%' . $this->search . '%')
                            ->orWhere('position', 'like', '%' . $this->search . '%')
                            ->orWhere('location', 'like', '%' . $this->search . '%');
                    });
                }
            )
            ->orderBy('sort_order')
            ->orderByDesc('start_date')
            ->paginate(10);
    }
};
?>

<div class="space-y-6 p-6">

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">

        <div>
            <flux:heading size="xl">
                Experience
            </flux:heading>

            <flux:text class="mt-1">
                Manage your professional experience.
            </flux:text>
        </div>



    </div>


    {{-- Search --}}
    <flux:card>

        <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass"
            placeholder="Search company, position, or location..." />

    </flux:card>


    {{-- Experience Table --}}
    <flux:card>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead>

                    <tr class="border-b border-zinc-200 dark:border-zinc-700">

                        <th class="px-3 py-3 text-left font-medium">
                            Position
                        </th>

                        <th class="px-3 py-3 text-left font-medium">
                            Company
                        </th>

                        <th class="px-3 py-3 text-left font-medium">
                            Location
                        </th>

                        <th class="px-3 py-3 text-left font-medium">
                            Period
                        </th>

                        <th class="px-3 py-3 text-left font-medium">
                            Type
                        </th>

                        <th class="px-3 py-3 text-left font-medium">
                            Status
                        </th>

                        <th class="px-3 py-3 text-right font-medium">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($this->experiences as $experience)

                        <tr wire:key="experience-{{ $experience->id }}"
                            class="border-b border-zinc-100 last:border-0 dark:border-zinc-800">

                            {{-- Position --}}
                            <td class="px-3 py-4">

                                <flux:text class="font-medium">
                                    {{ $experience->position }}
                                </flux:text>

                            </td>


                            {{-- Company --}}
                            <td class="px-3 py-4">

                                <flux:text>
                                    {{ $experience->company }}
                                </flux:text>

                            </td>


                            {{-- Location --}}
                            <td class="px-3 py-4">

                                <flux:text>
                                    {{ $experience->location ?: '—' }}
                                </flux:text>

                            </td>


                            {{-- Period --}}
                            <td class="px-3 py-4 whitespace-nowrap">

                                <flux:text>

                                    {{ $experience->start_date->format('m/Y') }}

                                    -

                                    @if ($experience->is_current)

                                        Present

                                    @elseif ($experience->end_date)

                                        {{ $experience->end_date->format('m/Y') }}

                                    @else

                                        —

                                    @endif

                                </flux:text>

                            </td>


                            {{-- Employment Type --}}
                            <td class="px-3 py-4">

                                @if ($experience->employment_type)

                                    <flux:badge>
                                        {{ $experience->employment_type }}
                                    </flux:badge>

                                @else

                                    <flux:text>
                                        —
                                    </flux:text>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td class="px-3 py-4">

                                @if ($experience->is_current)

                                    <flux:badge color="green">
                                        Current
                                    </flux:badge>

                                @else

                                    <flux:badge color="zinc">
                                        Past
                                    </flux:badge>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td class="px-3 py-4">

                                <div class="flex justify-end gap-2">



                                    {{-- <flux:button type="button" variant="danger" size="sm" icon="trash"
                                        wire:click="delete({{ $experience->id }})"
                                        wire:confirm="Are you sure you want to delete this experience?">
                                        Delete
                                    </flux:button> --}}

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="px-3 py-12 text-center">

                                <flux:text>
                                    No experience found.
                                </flux:text>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if ($this->experiences->hasPages())

            <div class="mt-6 border-t border-zinc-200 pt-4 dark:border-zinc-700">

                {{ $this->experiences->links() }}

            </div>

        @endif

    </flux:card>

</div>