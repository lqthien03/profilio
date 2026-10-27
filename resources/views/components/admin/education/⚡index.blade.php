<?php

use App\Models\Education;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Livewire\Component;

new
    #[Layout('layouts.admin')]
    class extends Component {
    use WithPagination;

    public string $search = '';
    public string $status = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function delete(Education $education): void
    {
        $education->delete();

        session()->flash('success', 'Education deleted successfully.');

        $this->redirectRoute('admin.education.index');
    }
    public function with(): array
    {
        $query = Education::query()
            ->when($this->search !== '', function ($query) {
                $query->where(function ($query) {
                    $query->where('school', 'like', '%' . $this->search . '%')
                        ->orWhere('degree', 'like', '%' . $this->search . '%')
                        ->orWhere('field_of_study', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status !== '', function ($query) {
                $query->where('is_active', (bool) $this->status);
            })
            ->orderBy('sort_order')
            ->orderByDesc('start_date');

        return [
            'educations' => $query->paginate(10),
        ];
    }
};
?>

<div class="space-y-6">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <flux:heading size="xl">Education</flux:heading>
            <flux:text class="mt-1">
                Manage your education history.
            </flux:text>
        </div>

        <flux:button variant="primary" icon="plus" :href="route('admin.education.create')">
            Add Education
        </flux:button>
    </div>

    @if (session('success'))
        <flux:callout variant="success" icon="check-circle">
            {{ session('success') }}
        </flux:callout>
    @endif

    <div class="flex flex-col gap-4 sm:flex-row">
        <div class="flex-1">
            <flux:input wire:model.live.debounce.300ms="search" placeholder="Search school, degree..."
                icon="magnifying-glass" />
        </div>

        <div class="sm:w-48">
            <flux:select wire:model.live="status">
                <flux:select.option value="">All statuses</flux:select.option>
                <flux:select.option value="1">Active</flux:select.option>
                <flux:select.option value="0">Inactive</flux:select.option>
            </flux:select>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 text-left dark:bg-zinc-800">
                    <tr>
                        <th class="px-4 py-3 font-medium">School</th>
                        <th class="px-4 py-3 font-medium">Degree</th>
                        <th class="px-4 py-3 font-medium">Field of Study</th>
                        <th class="px-4 py-3 font-medium">Period</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse ($educations as $education)
                                    <tr wire:key="education-{{ $education->id }}">
                                        <td class="px-4 py-3 font-medium">
                                            {{ $education->school }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ $education->degree }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ $education->field_of_study ?: '—' }}
                                        </td>

                                        <td class="px-4 py-3 whitespace-nowrap">
                                            {{ $education->start_date?->format('M Y') ?? '—' }}
                                            –
                                            {{ $education->is_current
                        ? 'Present'
                        : ($education->end_date?->format('M Y') ?? '—') }}
                                        </td>

                                        <td class="px-4 py-3">
                                            @if ($education->is_active)
                                                <flux:badge color="green">Active</flux:badge>
                                            @else
                                                <flux:badge color="zinc">Inactive</flux:badge>
                                            @endif
                                        </td>

                                        <td class="px-4 py-3">
                                            <div class="flex justify-end gap-2">
                                                <flux:button size="sm" variant="ghost" icon="pencil-square"
                                                    :href="route('admin.education.edit', $education)">
                                                    Edit
                                                </flux:button>

                                                <flux:button size="sm" variant="danger" icon="trash"
                                                    wire:click="delete({{ $education->id }})"
                                                    wire:confirm="Are you sure you want to delete this education record?">
                                                    Delete
                                                </flux:button>
                                            </div>
                                        </td>
                                    </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-zinc-500">
                                No education records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-zinc-200 p-4 dark:border-zinc-700">
            {{ $educations->links() }}
        </div>
    </div>
</div>

<?php
// Keep the query and pagination data in the component's PHP class above.
?>