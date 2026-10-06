<?php

use App\Models\Project;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public Project $project;

    public string $title = '';

    public string $slug = '';

    public string $short_description = '';

    public string $description = '';

    public $thumbnail = null;

    public ?string $currentThumbnail = null;

    public string $demo_url = '';

    public string $repository_url = '';

    public bool $featured = false;

    public string $status = 'draft';

    public int $sort_order = 0;

    public function mount(Project $project): void
    {
        $this->project = $project;

        $this->title = $project->title;
        $this->slug = $project->slug;
        $this->short_description = $project->short_description ?? '';
        $this->description = $project->description ?? '';
        $this->currentThumbnail = $project->thumbnail;
        $this->demo_url = $project->demo_url ?? '';
        $this->repository_url = $project->repository_url ?? '';
        $this->featured = $project->featured;
        $this->status = $project->status;
        $this->sort_order = $project->sort_order;
    }

    public function updatedTitle(string $value): void
    {
        if ($this->slug === $this->project->slug) {
            $this->slug = Str::slug($value);
        }
    }

    public function removeThumbnail(): void
    {
        if ($this->currentThumbnail) {
            Storage::disk('public')->delete($this->currentThumbnail);

            $this->project->update([
                'thumbnail' => null,
            ]);

            $this->currentThumbnail = null;
        }
    }

    public function update(): void
    {
        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'unique:projects,slug,' . $this->project->id,
            ],
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
            if ($this->currentThumbnail) {
                Storage::disk('public')->delete(
                    $this->currentThumbnail
                );
            }

            $validated['thumbnail'] = $this->thumbnail->store(
                'projects',
                'public'
            );
        } else {
            unset($validated['thumbnail']);
        }

        if ($validated['status'] === 'published') {
            $validated['published_at'] = $this->project->published_at ?? now();
        } else {
            $validated['published_at'] = null;
        }

        $this->project->update($validated);

        session()->flash(
            'success',
            'Project updated successfully.'
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
                Edit Project
            </flux:heading>

            <flux:text class="mt-1">
                Update your portfolio project.
            </flux:text>
        </div>

        <flux:button href="{{ route('admin.projects.index') }}" variant="ghost" icon="arrow-left">
            Back
        </flux:button>

    </div>


    {{-- Form --}}
    <form wire:submit="update" class="space-y-6">

        {{-- Basic Information --}}
        <flux:card>

            <flux:heading size="lg">
                Basic Information
            </flux:heading>

            <div class="mt-6 space-y-5">

                <flux:input wire:model.live.debounce.300ms="title" label="Title" :invalid="$errors->has('title')" />

                <flux:input wire:model="slug" label="Slug" :invalid="$errors->has('slug')" />

                <flux:textarea wire:model="short_description" label="Short description" rows="3" />

                <flux:textarea wire:model="description" label="Description" rows="8" />

            </div>

        </flux:card>


        {{-- Thumbnail --}}
        <flux:card>

            <flux:heading size="lg">
                Project Image
            </flux:heading>

            <flux:text class="mt-1">
                View the current image or upload a new one.
            </flux:text>

            <div class="mt-6 space-y-6">

                {{-- Current thumbnail --}}
                @if ($currentThumbnail)

                    <div>
                        <flux:text class="mb-2 font-medium">
                            Current thumbnail
                        </flux:text>

                        <div class="relative w-fit">

                            <img src="{{ asset('storage/' . $currentThumbnail) }}" alt="{{ $project->title }}"
                                class="h-56 w-auto rounded-xl border border-zinc-200 object-cover shadow-sm dark:border-zinc-700">

                            <flux:button type="button" variant="danger" size="sm" icon="trash"
                                class="absolute right-2 top-2" wire:click="removeThumbnail"
                                wire:confirm="Are you sure you want to remove this image?">
                                Remove
                            </flux:button>

                        </div>

                        <flux:text class="mt-2 text-xs">
                            {{ $currentThumbnail }}
                        </flux:text>

                    </div>

                @else

                    <div
                        class="flex h-56 items-center justify-center rounded-xl border border-dashed border-zinc-300 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800">

                        <div class="text-center">

                            <flux:icon name="photo" class="mx-auto size-10 text-zinc-400" />

                            <flux:text class="mt-2">
                                No thumbnail uploaded
                            </flux:text>

                        </div>

                    </div>

                @endif


                {{-- Upload new thumbnail --}}
                <div>

                    <flux:input wire:model="thumbnail" type="file" label="Replace thumbnail"
                        accept="image/jpeg,image/png,image/webp" />

                    @error('thumbnail')
                        <flux:text variant="danger" class="mt-2">
                            {{ $message }}
                        </flux:text>
                    @enderror

                </div>


                {{-- New image preview --}}
                @if ($thumbnail)

                    <div>

                        <flux:text class="mb-2 font-medium">
                            New thumbnail preview
                        </flux:text>

                        <div class="w-fit">

                            <img src="{{ $thumbnail->temporaryUrl() }}" alt="New thumbnail preview"
                                class="h-56 w-auto rounded-xl border border-zinc-200 object-cover shadow-sm dark:border-zinc-700">

                        </div>

                        <flux:text class="mt-2 text-xs">
                            This image will replace the current thumbnail after saving.
                        </flux:text>

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

                <flux:input wire:model="demo_url" label="Demo URL" type="url" />

                <flux:input wire:model="repository_url" label="Repository URL" type="url" />

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

                <flux:checkbox wire:model="featured" label="Featured project" />

            </div>

        </flux:card>


        {{-- Actions --}}
        <div class="flex justify-end gap-3">

            <flux:button href="{{ route('admin.projects.index') }}" variant="ghost">
                Cancel
            </flux:button>

            <flux:button type="submit" variant="primary" icon="check">
                Save Changes
            </flux:button>

        </div>

    </form>

</div>