<?php

use App\Models\Experience;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

new
    #[Layout('layouts.admin')]
    class extends Component {
    public string $company = '';

    public string $position = '';

    public string $location = '';

    public string $employment_type = 'Full-time';

    public string $description = '';

    public string $start_date = '';

    public string $end_date = '';

    public bool $is_current = false;

    public int $sort_order = 0;

    public function updatedIsCurrent(bool $value): void
    {
        if ($value) {
            $this->end_date = '';
        }
    }

    public function save(): void
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

        Experience::create($validated);

        session()->flash(
            'success',
            'Experience created successfully.'
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
                New Experience
            </flux:heading>

            <flux:text class="mt-1">
                Add a new professional experience to your portfolio.
            </flux:text>
        </div>

        <flux:button href="{{ route('admin.experiences.index') }}" variant="ghost" icon="arrow-left">
            Back
        </flux:button>

    </div>


    {{-- Form --}}
    <form wire:submit="save" class="space-y-6">

        {{-- Basic Information --}}
        <flux:card>

            <flux:heading size="lg">
                Basic Information
            </flux:heading>

            <div class="mt-6 grid gap-5 md:grid-cols-2">

                <flux:input wire:model="company" label="Company" placeholder="Example: FPT Software"
                    :invalid="$errors->has('company')" />

                <flux:input wire:model="position" label="Position" placeholder="Example: Backend Developer"
                    :invalid="$errors->has('position')" />

                <flux:input wire:model="location" label="Location" placeholder="Example: Ho Chi Minh City" />

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

            <flux:text class="mt-1">
                Describe your responsibilities, achievements, and technologies.
            </flux:text>

            <div class="mt-6">

                <flux:textarea wire:model="description" label="Description" rows="8"
                    placeholder="Describe your responsibilities and achievements..." />

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
                    End date will be cleared because this is your current position.
                </flux:text>

            @endif

        </flux:card>


        {{-- Publishing --}}
        <flux:card>

            <flux:heading size="lg">
                Display Settings
            </flux:heading>

            <div class="mt-6">

                <flux:input wire:model="sort_order" label="Sort Order" type="number" min="0" placeholder="0" />

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
                Save Experience
            </flux:button>

        </div>

    </form>

</div>