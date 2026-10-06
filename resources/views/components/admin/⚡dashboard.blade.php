<?php

use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Component;

new
    #[Layout('layouts.admin')]
    class extends Component {
    public string $title = 'Dashboard';

    public function getTotalProjectsProperty(): int
    {
        return Project::count();
    }

    public function getPublishedProjectsProperty(): int
    {
        return Project::where('status', 'published')->count();
    }

    public function getDraftProjectsProperty(): int
    {
        return Project::where('status', 'draft')->count();
    }

    public function getFeaturedProjectsProperty(): int
    {
        return Project::where('featured', true)->count();
    }

    public function getRecentProjectsProperty()
    {
        return Project::latest()->take(5)->get();
    }
};
?>

<div class="space-y-6 p-6">

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">

        <div>
            <flux:heading size="xl">
                {{ $title }}
            </flux:heading>

            <flux:text class="mt-2">
                Welcome to your portfolio CMS.
            </flux:text>
        </div>

        <flux:button
            href="{{ route('admin.projects.create') }}"
            variant="primary"
            icon="plus"
        >
            New Project
        </flux:button>

    </div>


    {{-- Statistics --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

        {{-- Total --}}
        <flux:card>

            <div class="flex items-center justify-between">

                <div>
                    <flux:text>
                        Total Projects
                    </flux:text>

                    <flux:heading size="xl" class="mt-2">
                        {{ $this->totalProjects }}
                    </flux:heading>
                </div>

                <div class="rounded-lg bg-zinc-100 p-3 dark:bg-zinc-800">
                    <flux:icon
                        name="folder"
                        class="size-6 text-zinc-600 dark:text-zinc-300"
                    />
                </div>

            </div>

        </flux:card>


        {{-- Published --}}
        <flux:card>

            <div class="flex items-center justify-between">

                <div>
                    <flux:text>
                        Published
                    </flux:text>

                    <flux:heading size="xl" class="mt-2">
                        {{ $this->publishedProjects }}
                    </flux:heading>
                </div>

                <div class="rounded-lg bg-green-50 p-3 dark:bg-green-900/20">
                    <flux:icon
                        name="check-circle"
                        class="size-6 text-green-600"
                    />
                </div>

            </div>

        </flux:card>


        {{-- Draft --}}
        <flux:card>

            <div class="flex items-center justify-between">

                <div>
                    <flux:text>
                        Drafts
                    </flux:text>

                    <flux:heading size="xl" class="mt-2">
                        {{ $this->draftProjects }}
                    </flux:heading>
                </div>

                <div class="rounded-lg bg-yellow-50 p-3 dark:bg-yellow-900/20">
                    <flux:icon
                        name="pencil"
                        class="size-6 text-yellow-600"
                    />
                </div>

            </div>

        </flux:card>


        {{-- Featured --}}
        <flux:card>

            <div class="flex items-center justify-between">

                <div>
                    <flux:text>
                        Featured
                    </flux:text>

                    <flux:heading size="xl" class="mt-2">
                        {{ $this->featuredProjects }}
                    </flux:heading>
                </div>

                <div class="rounded-lg bg-blue-50 p-3 dark:bg-blue-900/20">
                    <flux:icon
                        name="star"
                        class="size-6 text-blue-600"
                    />
                </div>

            </div>

        </flux:card>

    </div>


    {{-- Recent Projects --}}
    <flux:card>

        <div class="flex items-center justify-between gap-4">

            <div>
                <flux:heading size="lg">
                    Recent Projects
                </flux:heading>

                <flux:text class="mt-1">
                    Your latest portfolio projects.
                </flux:text>
            </div>

            <flux:button
                href="{{ route('admin.projects.index') }}"
                variant="ghost"
            >
                View all
            </flux:button>

        </div>


        <div class="mt-6 overflow-x-auto">

            <table class="w-full text-sm">

                <thead>
                    <tr class="border-b border-zinc-200 dark:border-zinc-700">

                        <th class="px-3 py-3 text-left font-medium">
                            Project
                        </th>

                        <th class="px-3 py-3 text-left font-medium">
                            Status
                        </th>

                        <th class="px-3 py-3 text-left font-medium">
                            Featured
                        </th>

                        <th class="px-3 py-3 text-right font-medium">
                            Created
                        </th>

                    </tr>
                </thead>

                <tbody>

                    @forelse ($this->recentProjects as $project)

                        <tr class="border-b border-zinc-100 last:border-0 dark:border-zinc-800">

                            <td class="px-3 py-4">

                                <div class="flex items-center gap-3">

                                    @if ($project->thumbnail)

                                        <img
                                            src="{{ asset('storage/' . $project->thumbnail) }}"
                                            alt="{{ $project->title }}"
                                            class="size-10 rounded-lg object-cover"
                                        >

                                    @else

                                        <div class="flex size-10 items-center justify-center rounded-lg bg-zinc-100 dark:bg-zinc-800">

                                            <flux:icon
                                                name="photo"
                                                class="size-5 text-zinc-400"
                                            />

                                        </div>

                                    @endif

                                    <div>
                                        <flux:text class="font-medium">
                                            {{ $project->title }}
                                        </flux:text>

                                        <flux:text class="text-xs">
                                            {{ $project->slug }}
                                        </flux:text>
                                    </div>

                                </div>

                            </td>


                            <td class="px-3 py-4">

                                @if ($project->status === 'published')

                                    <flux:badge color="green">
                                        Published
                                    </flux:badge>

                                @else

                                    <flux:badge color="yellow">
                                        Draft
                                    </flux:badge>

                                @endif

                            </td>


                            <td class="px-3 py-4">

                                @if ($project->featured)

                                    <flux:badge color="blue">
                                        Featured
                                    </flux:badge>

                                @else

                                    <flux:text>
                                        —
                                    </flux:text>

                                @endif

                            </td>


                            <td class="px-3 py-4 text-right">

                                <flux:text class="text-xs">
                                    {{ $project->created_at->format('d/m/Y') }}
                                </flux:text>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4" class="px-3 py-10 text-center">

                                <flux:text>
                                    No projects found.
                                </flux:text>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </flux:card>

</div>