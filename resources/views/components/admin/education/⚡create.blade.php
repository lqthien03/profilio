<?php

use App\Models\Education;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

new
    #[Layout('layouts.admin')]
    class extends Component {
    public string $school = '';
    public string $degree = '';
    public ?string $field_of_study = null;
    public ?string $description = null;
    public ?string $start_date = null;
    public ?string $end_date = null;
    public bool $is_current = false;
    public ?string $location = null;
    public int $sort_order = 0;
    public bool $is_active = true;

    protected function rules(): array
    {
        return [
            'school' => ['required', 'string', 'max:255'],
            'degree' => ['required', 'string', 'max:255'],
            'field_of_study' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
                Rule::requiredIf(!$this->is_current && $this->start_date !== null),
            ],
            'is_current' => ['required', 'boolean'],
            'location' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->is_current) {
            $validated['end_date'] = null;
        }

        Education::create($validated);

        session()->flash('success', 'Education created successfully.');

        $this->redirectRoute('admin.education.index');
    }
};
?>

<div class="space-y-6">
    <div>
        <flux:heading size="xl">Add Education</flux:heading>
        <flux:text class="mt-1">Add a new education record.</flux:text>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="grid gap-6 md:grid-cols-2">
            <flux:field>
                <flux:label>School</flux:label>
                <flux:input wire:model="school" placeholder="FPT Polytechnic" />
                <flux:error name="school" />
            </flux:field>

            <flux:field>
                <flux:label>Degree</flux:label>
                <flux:input wire:model="degree" placeholder="Cao đẳng" />
                <flux:error name="degree" />
            </flux:field>

            <flux:field>
                <flux:label>Field of Study</flux:label>
                <flux:input wire:model="field_of_study" placeholder="Công nghệ thông tin" />
                <flux:error name="field_of_study" />
            </flux:field>

            <flux:field>
                <flux:label>Location</flux:label>
                <flux:input wire:model="location" placeholder="TP. Hồ Chí Minh" />
                <flux:error name="location" />
            </flux:field>

            <flux:field>
                <flux:label>Start Date</flux:label>
                <flux:input type="date" wire:model="start_date" />
                <flux:error name="start_date" />
            </flux:field>

            <flux:field>
                <flux:label>End Date</flux:label>
                <flux:input type="date" wire:model="end_date" :disabled="$is_current" />
                <flux:error name="end_date" />
            </flux:field>

            <flux:field>
                <flux:label>Sort Order</flux:label>
                <flux:input type="number" min="0" wire:model="sort_order" />
                <flux:error name="sort_order" />
            </flux:field>
        </div>

        <flux:field>
            <flux:label>Description</flux:label>
            <flux:textarea wire:model="description" rows="4" />
            <flux:error name="description" />
        </flux:field>

        <div class="space-y-4">
            <flux:checkbox wire:model.live="is_current" label="Currently studying here" />
            <flux:checkbox wire:model="is_active" label="Active — show on portfolio" />
        </div>

        <div class="flex gap-3">
            <flux:button type="submit" variant="primary">Save Education</flux:button>
            <flux:button variant="ghost" :href="route('admin.education.index')">Cancel</flux:button>
        </div>
    </form>
</div>