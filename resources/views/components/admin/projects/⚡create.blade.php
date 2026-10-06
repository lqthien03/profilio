<?php

use App\Models\Project;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public string $title = '';

    public string $slug = '';

    public string $short_description = '';

    public string $description = '';

    public $thumbnail = null;

    public string $demo_url = '';

    public string $repository_url = '';

    public bool $featured = false;

    public string $status = 'draft';

    public int $sort_order = 0;

    public function updatedTitle(string $value): void
    {
        if ($this->slug === '') {
            $this->slug = Str::slug($value);
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:projects,slug'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'demo_url' => ['nullable', 'url', 'max:255'],
            'repository_url' => ['nullable', 'url', 'max:255'],
            'featured' => ['boolean'],
            'status' => ['required', 'in:draft,published'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        if ($this->thumbnail) {
            $validated['thumbnail'] = $this->thumbnail->store(
                'projects',
                'public'
            );
        }

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        Project::create($validated);

        session()->flash(
            'success',
            'Project created successfully.'
        );

        $this->redirectRoute('admin.projects.index');
    }
};
?>

<div class="space-y-6 p-6">

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">

        <div>
            <flux:heading size="xl">
                Create Project
            </flux:heading>

            <flux:text class="mt-1">
                Add a new project to your portfolio.
            </flux:text>
        </div>

        <flux:button href="{{ route('admin.projects.index') }}" variant="ghost" icon="arrow-left">
            Back
        </flux:button>

    </div>


    {{-- Success message --}}
    @if (session('success'))
        <flux:callout variant="success" icon="check-circle">
            {{ session('success') }}
        </flux:callout>
    @endif


    <form wire:submit="save" class="space-y-6">

        {{-- Basic Information --}}
        <flux:card>

            <flux:heading size="lg">
                Basic Information
            </flux:heading>

            <flux:text class="mt-1">
                The basic information of your project.
            </flux:text>

            <div class="mt-6 space-y-5">

                <flux:input wire:model.live.debounce.300ms="title" label="Title" placeholder="Laravel Portfolio"
                    :invalid="$errors->has('title')" />

                <flux:input wire:model="slug" label="Slug" placeholder="laravel-portfolio"
                    description="Used in the project URL." :invalid="$errors->has('slug')" />

                <flux:textarea wire:model="short_description" label="Short description"
                    placeholder="A short description of this project..." rows="3" />

                <flux:textarea wire:model="description" label="Description" placeholder="Describe your project..."
                    rows="8" />

            </div>

        </flux:card>


        {{-- Thumbnail --}}
        <flux:card>

            <flux:heading size="lg">
                Project Image
            </flux:heading>

            <flux:text class="mt-1">
                Upload the thumbnail displayed on your portfolio.
            </flux:text>

            <div class="mt-6">

                <flux:input wire:model="thumbnail" type="file" label="Thumbnail"
                    accept="image/jpeg,image/png,image/webp" />

                @error('thumbnail')
                    <flux:text variant="danger" class="mt-2">
                        {{ $message }}
                    </flux:text>
                @enderror

                @if ($thumbnail)

                    <div class="mt-4">
                        <img src="{{ $thumbnail->temporaryUrl() }}" alt="Thumbnail preview"
                            class="h-48 w-auto rounded-xl border object-cover">
                    </div>

                @endif

            </div>

        </flux:card>


        {{-- Links --}}
        <flux:card>

            <flux:heading size="lg">
                Project Links
            </flux:heading>

            <div class="mt-6 grid gap-5 md:grid-cols-2">

                <flux:input wire:model="demo_url" label="Demo URL" type="url" placeholder="https://example.com" />

                <flux:input wire:model="repository_url" label="Repository URL" type="url"
                    placeholder="https://github.com/..." />

            </div>

        </flux:card>


        {{-- Publishing --}}
        <flux:card>

            <flux:heading size="lg">
                Publishing
            </flux:heading>

            <div class="mt-6 grid gap-5 md:grid-cols-2">

                <flux:select wire:model="status" label="Status">
                    <option value="draft">
                        Draft
                    </option>

                    <option value="published">
                        Published
                    </option>
                </flux:select>

                <flux:input wire:model="sort_order" label="Sort order" type="number" min="0" />

            </div>

            <div class="mt-5">

                <flux:checkbox wire:model="featured" label="Featured project"
                    description="Display this project in the featured section." />

            </div>

        </flux:card>


        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3">

            <flux:button href="{{ route('admin.projects.index') }}" variant="ghost">
                Cancel
            </flux:button>

            <flux:button type="submit" variant="primary" icon="check">
                Create Project
            </flux:button>

        </div>

    </form>

</div>