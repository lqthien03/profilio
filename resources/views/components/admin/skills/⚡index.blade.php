<?php

use App\Models\Skill;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new
    #[Layout('layouts.admin')]
    class extends Component {
    use WithPagination;

    public string $search = '';

    public string $category = '';

    public string $status = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function with(): array
    {
        return [
            'skills' => Skill::query()
                ->when($this->search, function (Builder $query) {
                    $query->where(function (Builder $query) {
                        $query
                            ->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('category', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->category, function (Builder $query) {
                    $query->where('category', $this->category);
                })
                ->when($this->status !== '', function (Builder $query) {
                    $query->where(
                        'is_active',
                        $this->status === 'active'
                    );
                })
                ->orderBy('sort_order')
                ->orderBy('name')
                ->paginate(10),

            'categories' => Skill::query()
                ->select('category')
                ->distinct()
                ->orderBy('category')
                ->pluck('category'),
        ];
    }

    public function delete(Skill $skill): void
    {
        $skill->delete();

        session()->flash('success', 'Skill deleted successfully.');

        $this->redirectRoute('admin.skills.index');
    }
};
?>

<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl">Skills</flux:heading>
            <flux:text class="mt-1">
                Manage your technical skills and proficiency levels.
            </flux:text>
        </div>

        <flux:button href="{{ route('admin.skills.create') }}" variant="primary" icon="plus">
            New Skill
        </flux:button>
    </div>

    @if (session('success'))
        <flux:callout variant="success" icon="check-circle">
            {{ session('success') }}
        </flux:callout>
    @endif

    <div class="flex flex-col gap-3 sm:flex-row">
        <div class="flex-1">
            <flux:input wire:model.live.debounce.300ms="search" placeholder="Search skills..."
                icon="magnifying-glass" />
        </div>

        <div class="w-full sm:w-48">
            <flux:select wire:model.live="category">
                <flux:select.option value="">All categories</flux:select.option>

                @foreach ($categories as $item)
                    <flux:select.option value="{{ $item }}">
                        {{ $item }}
                    </flux:select.option>
                @endforeach
            </flux:select>
        </div>

        <div class="w-full sm:w-40">
            <flux:select wire:model.live="status">
                <flux:select.option value="">All statuses</flux:select.option>
                <flux:select.option value="active">Active</flux:select.option>
                <flux:select.option value="inactive">Inactive</flux:select.option>
            </flux:select>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Skill</flux:table.column>
                <flux:table.column>Category</flux:table.column>
                <flux:table.column>Level</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column>Order</flux:table.column>
                <flux:table.column align="end">Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($skills as $skill)
                    <flux:table.row :key="$skill->id">
                        <flux:table.cell>
                            <div class="flex items-center gap-3">
                                @if ($skill->icon)
                                    <span>{{ $skill->icon }}</span>
                                @endif

                                <span class="font-medium">
                                    {{ $skill->name }}
                                </span>
                            </div>
                        </flux:table.cell>

                        <flux:table.cell>
                            <flux:badge color="zinc">
                                {{ $skill->category }}
                            </flux:badge>
                        </flux:table.cell>

                        <flux:table.cell>
                            <div class="min-w-24 space-y-1">
                                <div class="flex justify-between text-sm">
                                    <span>{{ $skill->level }}%</span>
                                </div>

                                <div class="h-1.5 overflow-hidden rounded-full bg-zinc-200 dark:bg-zinc-700">
                                    <div class="h-full rounded-full bg-blue-500"
                                        style="width: {{ min(100, max(0, $skill->level)) }}%"></div>
                                </div>
                            </div>
                        </flux:table.cell>

                        <flux:table.cell>
                            @if ($skill->is_active)
                                <flux:badge color="green">Active</flux:badge>
                            @else
                                <flux:badge color="zinc">Inactive</flux:badge>
                            @endif
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $skill->sort_order }}
                        </flux:table.cell>

                        <flux:table.cell align="end">
                            <div class="flex justify-end gap-2">
                                <flux:button href="{{ route('admin.skills.edit', $skill) }}" variant="ghost" size="sm"
                                    icon="pencil-square">
                                    Edit
                                </flux:button>

                                <flux:button size="sm" variant="danger" icon="trash" wire:click="delete({{ $skill->id }})"
                                    wire:confirm="Are you sure you want to delete this skill?">
                                    Delete
                                </flux:button>

                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6">
                            <div class="py-8 text-center">
                                <flux:text>
                                    No skills found.
                                </flux:text>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>

    <div>
        {{ $skills->links() }}
    </div>
</div>