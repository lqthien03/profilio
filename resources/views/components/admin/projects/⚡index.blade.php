<?php

use App\Models\Project;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
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

    public function with(): array
    {
        return [
            'projects' => Project::query()
                ->when(
                    $this->search,
                    fn($query) => $query->where(function ($query) {
                        $query
                            ->where('title', 'like', '%' . $this->search . '%')
                            ->orWhere(
                                'short_description',
                                'like',
                                '%' . $this->search . '%'
                            );
                    })
                )
                ->when(
                    $this->status,
                    fn($query) => $query->where('status', $this->status)
                )
                ->orderBy('sort_order')
                ->orderByDesc('created_at')
                ->paginate(10),
        ];
    }

    public function delete(Project $project): void
    {
        $thumbnail = $project->thumbnail;

        $project->delete();

        if ($thumbnail) {
            Storage::disk('public')->delete($thumbnail);
        }

        session()->flash('success', 'Project deleted successfully.');

        $this->resetPage();
    }
};
?>

<div class="space-y-6 p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between gap-4">

        <div>
            <flux:heading size="xl">
                Projects
            </flux:heading>

            <flux:text class="mt-1">
                Manage your portfolio projects.
            </flux:text>
        </div>

        <flux:button variant="primary" icon="plus" href="{{ route('admin.projects.create') }}">
            New Project
        </flux:button>

    </div>


    {{-- Filters --}}
    <div class="flex flex-col gap-4 sm:flex-row">

        <div class="flex-1">
            <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass"
                placeholder="Search projects..." />
        </div>

        <div class="sm:w-48">
            <flux:select wire:model.live="status">
                <option value="">All status</option>
                <option value="published">Published</option>
                <option value="draft">Draft</option>
            </flux:select>
        </div>

    </div>


    {{-- Projects table --}}
    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">

        <div class="overflow-x-auto">

            <table class="w-full text-left text-sm">

                <thead class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800">
                    <tr>
                        <th class="px-6 py-4 font-medium">
                            Project
                        </th>

                        <th class="px-6 py-4 font-medium">
                            Status
                        </th>

                        <th class="px-6 py-4 font-medium">
                            Featured
                        </th>

                        <th class="px-6 py-4 font-medium">
                            Order
                        </th>

                        <th class="px-6 py-4 text-right font-medium">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

                    @forelse ($projects as $project)

                        <tr wire:key="project-{{ $project->id }}">

                            {{-- Project --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <div
                                        class="flex size-12 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-zinc-100 dark:bg-zinc-800">

                                        @if ($project->thumbnail)
                                            <img src="{{ Storage::url($project->thumbnail) }}" alt="{{ $project->title }}"
                                                class="size-full object-cover">
                                        @else
                                            <flux:icon name="photo" class="size-5 text-zinc-400" />
                                        @endif

                                    </div>

                                    <div class="min-w-0">

                                        <div class="truncate font-medium">
                                            {{ $project->title }}
                                        </div>

                                        <div class="truncate text-xs text-zinc-500">
                                            {{ $project->slug }}
                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- Status --}}
                            <td class="px-6 py-4">

                                @if ($project->status === 'published')

                                    <flux:badge color="green" size="sm">
                                        Published
                                    </flux:badge>

                                @else

                                    <flux:badge color="zinc" size="sm">
                                        Draft
                                    </flux:badge>

                                @endif

                            </td>


                            {{-- Featured --}}
                            <td class="px-6 py-4">

                                @if ($project->featured)

                                    <flux:badge color="amber" size="sm">
                                        Featured
                                    </flux:badge>

                                @else

                                    <span class="text-zinc-400">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Order --}}
                            <td class="px-6 py-4">
                                {{ $project->sort_order }}
                            </td>


                            {{-- Actions --}}
                            <td class="px-6 py-4 text-right">

                                <flux:button variant="ghost" size="sm" href="{{ route('admin.projects.edit', $project) }}">
                                    Edit
                                </flux:button>

                                <flux:button type="button" variant="danger" size="sm" icon="trash"
                                    wire:click="delete({{ $project->id }})"
                                    wire:confirm="Are you sure you want to delete this project?">
                                    Delete
                                </flux:button>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <flux:text>
                                    No projects found.
                                </flux:text>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if ($projects->hasPages())

            <div class="border-t border-zinc-200 p-4 dark:border-zinc-700">
                {{ $projects->links() }}
            </div>

        @endif

    </div>

</div>