<?php

use App\Models\Experience;
use Livewire\Attributes\Layout;
use Livewire\Component;

new
    #[Layout('layouts.admin')]
    class extends Component {
    public Experience $experience;

    public string $company = '';

    public string $position = '';

    public string $location = '';

    public string $employment_type = 'Full-time';

    public string $description = '';

    public string $start_date = '';

    public string $end_date = '';

    public bool $is_current = false;

    public int $sort_order = 0;

    public function mount(Experience $experience): void
    {
        $this->experience = $experience;

        $this->company = $experience->company;
        $this->position = $experience->position;
        $this->location = $experience->location ?? '';
        $this->employment_type = $experience->employment_type ?? 'Full-time';
        $this->description = $experience->description ?? '';

        $this->start_date = $experience->start_date?->format('Y-m-d') ?? '';
        $this->end_date = $experience->end_date?->format('Y-m-d') ?? '';

        $this->is_current = $experience->is_current;
        $this->sort_order = $experience->sort_order;
    }

    public function updatedIsCurrent(bool $value): void
    {
        if ($value) {
            $this->end_date = '';
        }
    }

    public function update(): void
    {
        $rules = [
            'company' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'employment_type' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ];

        if ($this->is_current) {
            $rules['end_date'] = ['nullable', 'date'];
        } else {
            $rules['end_date'] = [
                'required',
                'date',
                'after_or_equal:start_date',
            ];
        }

        $validated = $this->validate($rules);

        if ($this->is_current) {
            $validated['end_date'] = null;
        }

        $this->experience->update($validated);

        session()->flash(
            'success',
            'Experience updated successfully.'
        );

        $this->redirectRoute('admin.experiences.index');
    }
};
?>

<div class="space-y-6 p-6">

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">

        <div>
            <flux:heading size="xl">
                Edit Experience
            </flux:heading>

            <flux:text class="mt-1">
                Update your professional experience.
            </flux:text>
        </div>

        <flux:button href="{{ route('admin.experiences.index') }}" variant="ghost" icon="arrow-left">
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

            <div class="mt-6 grid gap-5 md:grid-cols-2">

                <flux:input wire:model="company" label="Company" :invalid="$errors->has('company')" />

                <flux:input wire:model="position" label="Position" :invalid="$errors->has('position')" />

                <flux:input wire:model="location" label="Location" />

                <flux:select wire:model="employment_type" label="Employment Type">
                    <option value="Full-time">
                        Full-time
                    </option>

                    <option value="Part-time">
                        Part-time
                    </option>

                    <option value="Freelance">
                        Freelance
                    </option>

                    <option value="Internship">
                        Internship
                    </option>

                    <option value="Contract">
                        Contract
                    </option>
                </flux:select>

            </div>

        </flux:card>


        {{-- Description --}}
        <flux:card>

            <flux:heading size="lg">
                Description
            </flux:heading>

            <div class="mt-6">

                <flux:textarea wire:model="description" label="Description" rows="8" />

            </div>

        </flux:card>


        {{-- Employment Period --}}
        <flux:card>

            <flux:heading size="lg">
                Employment Period
            </flux:heading>

            <div class="mt-6 grid gap-5 md:grid-cols-2">

                <flux:input wire:model="start_date" label="Start Date" type="date" />

                <flux:input wire:model="end_date" label="End Date" type="date" :disabled="$is_current" />

            </div>

            <div class="mt-5">

                <flux:checkbox wire:model.live="is_current" label="I currently work here" />

            </div>

            @if ($is_current)

                <flux:text class="mt-3">
                    This is your current position. The end date will remain empty.
                </flux:text>

            @endif

        </flux:card>


        {{-- Display Settings --}}
        <flux:card>

            <flux:heading size="lg">
                Display Settings
            </flux:heading>

            <div class="mt-6">

                <flux:input wire:model="sort_order" label="Sort Order" type="number" min="0" />

                <flux:text class="mt-2 text-xs">
                    Lower numbers appear first.
                </flux:text>

            </div>

        </flux:card>


        {{-- Actions --}}
        <div class="flex justify-end gap-3">

            <flux:button href="{{ route('admin.experiences.index') }}" variant="ghost">
                Cancel
            </flux:button>

            <flux:button type="submit" variant="primary" icon="check">
                Save Changes
            </flux:button>

        </div>

    </form>

</div>