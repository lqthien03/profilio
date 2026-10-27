<?php

use App\Models\Skill;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

new
    #[Layout('layouts.admin')]
    class extends Component {
    public Skill $skill;

    public string $name = '';

    public string $category = 'Backend';

    public int $level = 80;

    public ?string $icon = null;

    public int $sort_order = 0;

    public bool $is_active = true;

    public function mount(Skill $skill): void
    {
        $this->skill = $skill;

        $this->name = $skill->name;
        $this->category = $skill->category;
        $this->level = $skill->level;
        $this->icon = $skill->icon;
        $this->sort_order = $skill->sort_order;
        $this->is_active = $skill->is_active;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category' => [
                'required',
                'string',
                'max:100',
                Rule::in(['Backend', 'Frontend', 'Database', 'Tools', 'Other']),
            ],
            'level' => ['required', 'integer', 'min:0', 'max:100'],
            'icon' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function update(): void
    {
        $validated = $this->validate();

        $this->skill->update($validated);

        session()->flash('success', 'Skill updated successfully.');

        $this->redirectRoute('admin.skills.index');
    }
};
?>

<div class="space-y-6">
    <div>
        <flux:heading size="xl">Edit Skill</flux:heading>
        <flux:text class="mt-1">
            Update skill information and proficiency level.
        </flux:text>
    </div>

    <form wire:submit="update" class="space-y-6">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <flux:field>
                <flux:label>Skill name</flux:label>
                <flux:input wire:model="name" placeholder="e.g. Laravel" />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>Category</flux:label>
                <flux:select wire:model="category">
                    <flux:select.option value="Backend">Backend</flux:select.option>
                    <flux:select.option value="Frontend">Frontend</flux:select.option>
                    <flux:select.option value="Database">Database</flux:select.option>
                    <flux:select.option value="Tools">Tools</flux:select.option>
                    <flux:select.option value="Other">Other</flux:select.option>
                </flux:select>
                <flux:error name="category" />
            </flux:field>

            <flux:field>
                <flux:label>Proficiency level (%)</flux:label>
                <flux:input type="number" min="0" max="100" wire:model="level" />
                <flux:error name="level" />
            </flux:field>

            <flux:field>
                <flux:label>Icon (optional)</flux:label>
                <flux:input wire:model="icon" placeholder="e.g. 🐘" />
                <flux:error name="icon" />
            </flux:field>

            <flux:field>
                <flux:label>Sort order</flux:label>
                <flux:input type="number" min="0" wire:model="sort_order" />
                <flux:error name="sort_order" />
            </flux:field>

            <div class="flex items-center pt-6">
                <flux:checkbox wire:model="is_active" label="Active — show this skill" />
            </div>
        </div>

        <div class="flex justify-end gap-3 border-t border-zinc-200 pt-4 dark:border-zinc-700">
            <flux:button href="{{ route('admin.skills.index') }}" variant="ghost">
                Cancel
            </flux:button>

            <flux:button type="submit" variant="primary" icon="check">
                Update Skill
            </flux:button>
        </div>
    </form>
</div>