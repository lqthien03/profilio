<?php

use App\Models\Profile;
use App\Models\SocialLink;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Component;

new #[Layout('layouts.admin')]
    class extends Component {
    use WithFileUploads;

    public Profile $profile;

    public string $name = '';
    public string $headline = '';
    public string $about = '';
    public string $email = '';
    public string $phone = '';
    public string $address = '';
    public string $resume_url = '';
    public string $website_url = '';

    public $avatar = null;
    public ?string $currentAvatar = null;

    // Social link form
    public ?int $editingSocialLinkId = null;

    public string $social_platform = 'github';
    public string $social_label = '';
    public string $social_url = '';
    public int $social_sort_order = 0;
    public bool $social_is_active = true;

    public function mount(): void
    {
        $this->profile = Profile::firstOrCreate(
            ['id' => 1],
            ['name' => 'Your Name']
        );

        $this->name = $this->profile->name;
        $this->headline = $this->profile->headline ?? '';
        $this->about = $this->profile->about ?? '';
        $this->email = $this->profile->email ?? '';
        $this->phone = $this->profile->phone ?? '';
        $this->address = $this->profile->address ?? '';
        $this->resume_url = $this->profile->resume_url ?? '';
        $this->website_url = $this->profile->website_url ?? '';
        $this->currentAvatar = $this->profile->avatar;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'headline' => ['nullable', 'string', 'max:255'],
            'about' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'resume_url' => ['nullable', 'url', 'max:2048'],
            'website_url' => ['nullable', 'url', 'max:2048'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        unset($validated['avatar']);

        if ($this->avatar) {
            $validated['avatar'] = $this->avatar->store('profiles', 'public');

            if ($this->currentAvatar) {
                Storage::disk('public')->delete($this->currentAvatar);
            }
        }

        $this->profile->update($validated);
        $this->currentAvatar = $this->profile->fresh()->avatar;
        $this->avatar = null;

        session()->flash('success', 'Profile updated successfully.');
    }

    public function resetSocialForm(): void
    {
        $this->resetValidation();

        $this->editingSocialLinkId = null;
        $this->social_platform = 'github';
        $this->social_label = '';
        $this->social_url = '';
        $this->social_sort_order = (int) (
            $this->profile->socialLinks()->max('sort_order') ?? -1
        ) + 1;
        $this->social_is_active = true;
    }

    public function saveSocialLink(): void
    {
        $validated = $this->validate([
            'social_platform' => [
                'required',
                'string',
                'in:facebook,instagram,github,linkedin,youtube,tiktok,x,website,other',
            ],
            'social_label' => ['nullable', 'string', 'max:100'],
            'social_url' => ['required', 'url', 'max:2048'],
            'social_sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'social_is_active' => ['required', 'boolean'],
        ]);

        $data = [
            'platform' => $validated['social_platform'],
            'label' => $validated['social_label'] ?: null,
            'url' => $validated['social_url'],
            'sort_order' => $validated['social_sort_order'],
            'is_active' => $validated['social_is_active'],
        ];

        if ($this->editingSocialLinkId !== null) {
            $link = $this->profile->socialLinks()
                ->findOrFail($this->editingSocialLinkId);

            $link->update($data);
        } else {
            $this->profile->socialLinks()->create($data);
        }

        $this->resetSocialForm();

        session()->flash('social_success', 'Social link saved successfully.');
    }

    public function editSocialLink(int $id): void
    {
        $link = $this->profile->socialLinks()->findOrFail($id);

        $this->resetValidation();

        $this->editingSocialLinkId = $link->id;
        $this->social_platform = $link->platform;
        $this->social_label = $link->label ?? '';
        $this->social_url = $link->url;
        $this->social_sort_order = $link->sort_order;
        $this->social_is_active = $link->is_active;
    }

    public function deleteSocialLink(int $id): void
    {
        $link = $this->profile->socialLinks()->findOrFail($id);

        $link->delete();

        if ($this->editingSocialLinkId === $id) {
            $this->resetSocialForm();
        }

        session()->flash('social_success', 'Social link deleted successfully.');
    }

};
?>

<div class="space-y-6">
    <div>
        <flux:heading size="xl">Profile</flux:heading>
        <flux:text class="mt-1">
            Manage your personal information and public portfolio details.
        </flux:text>
    </div>

    @if (session('success'))
        <flux:callout variant="success" icon="check-circle">
            {{ session('success') }}
        </flux:callout>
    @endif

    <form wire:submit="save" class="space-y-6">
        <flux:card>
            <div class="space-y-5">
                <flux:heading size="lg">Personal information</flux:heading>

                <div>
                    <flux:label>Name</flux:label>
                    <flux:input wire:model="name" placeholder="Your full name" />
                    <flux:error name="name" />
                </div>

                <div>
                    <flux:label>Professional headline</flux:label>
                    <flux:input wire:model="headline" placeholder="Full-stack Web Developer" />
                    <flux:error name="headline" />
                </div>

                <div>
                    <flux:label>About me</flux:label>
                    <flux:textarea wire:model="about" rows="5" placeholder="Introduce yourself..." />
                    <flux:error name="about" />
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <flux:label>Email</flux:label>
                        <flux:input type="email" wire:model="email" />
                        <flux:error name="email" />
                    </div>

                    <div>
                        <flux:label>Phone</flux:label>
                        <flux:input wire:model="phone" />
                        <flux:error name="phone" />
                    </div>
                </div>

                <div>
                    <flux:label>Address</flux:label>
                    <flux:input wire:model="address" />
                    <flux:error name="address" />
                </div>
            </div>
        </flux:card>

        <flux:card>
            <div class="space-y-5">
                <flux:heading size="lg">Portfolio links</flux:heading>

                <div>
                    <flux:label>Website URL</flux:label>
                    <flux:input type="url" wire:model="website_url" placeholder="https://example.com" />
                    <flux:error name="website_url" />
                </div>

                <div>
                    <flux:label>Resume / CV URL</flux:label>
                    <flux:input type="url" wire:model="resume_url" placeholder="https://example.com/cv.pdf" />
                    <flux:error name="resume_url" />
                </div>
            </div>
        </flux:card>

        <flux:card>
            <div class="space-y-4">
                <flux:heading size="lg">Avatar</flux:heading>

                @if ($avatar)
                    <img src="{{ $avatar->temporaryUrl() }}" alt="Avatar preview"
                        class="h-24 w-24 rounded-full object-cover">
                @elseif ($currentAvatar)
                    <img src="{{ Storage::disk('public')->url($currentAvatar) }}" alt="Current avatar"
                        class="h-24 w-24 rounded-full object-cover">
                @endif

                <input type="file" wire:model="avatar" accept="image/*" class="block w-full text-sm">
                <flux:error name="avatar" />

                <flux:text size="sm">
                    Maximum image size: 2 MB.
                </flux:text>
            </div>
        </flux:card>

        <div class="flex justify-end">
            <flux:button type="submit" variant="primary">
                Save profile
            </flux:button>
        </div>
    </form>

    <flux:card>
        <div class="space-y-5">
            <div>
                <flux:heading size="lg">Social links</flux:heading>
                <flux:text class="mt-1">
                    Manage your Facebook, Instagram, GitHub and other social profiles.
                </flux:text>
            </div>

            @if (session('social_success'))
                <flux:callout variant="success" icon="check-circle">
                    {{ session('social_success') }}
                </flux:callout>
            @endif

            <form wire:submit="saveSocialLink" class="space-y-4 rounded-xl border p-4">
                <flux:heading size="md">
                    {{ $editingSocialLinkId ? 'Edit social link' : 'Add social link' }}
                </flux:heading>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <flux:label>Platform</flux:label>
                        <flux:select wire:model="social_platform">
                            <flux:select.option value="facebook">Facebook</flux:select.option>
                            <flux:select.option value="instagram">Instagram</flux:select.option>
                            <flux:select.option value="github">GitHub</flux:select.option>
                            <flux:select.option value="linkedin">LinkedIn</flux:select.option>
                            <flux:select.option value="youtube">YouTube</flux:select.option>
                            <flux:select.option value="tiktok">TikTok</flux:select.option>
                            <flux:select.option value="x">X</flux:select.option>
                            <flux:select.option value="website">Website</flux:select.option>
                            <flux:select.option value="other">Other</flux:select.option>
                        </flux:select>
                        <flux:error name="social_platform" />
                    </div>

                    <div>
                        <flux:label>Display label (optional)</flux:label>
                        <flux:input wire:model="social_label" placeholder="e.g. My GitHub" />
                        <flux:error name="social_label" />
                    </div>
                </div>

                <div>
                    <flux:label>URL</flux:label>
                    <flux:input type="url" wire:model="social_url" placeholder="https://github.com/your-username" />
                    <flux:error name="social_url" />
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <flux:label>Sort order</flux:label>
                        <flux:input type="number" min="0" wire:model="social_sort_order" />
                        <flux:error name="social_sort_order" />
                    </div>

                    <div class="flex items-center gap-3 pt-6">
                        <flux:checkbox wire:model="social_is_active" label="Visible on portfolio" />
                    </div>
                </div>

                <div class="flex flex-wrap gap-2">
                    <flux:button type="submit" variant="primary">
                        {{ $editingSocialLinkId ? 'Update link' : 'Add link' }}
                    </flux:button>

                    @if ($editingSocialLinkId)
                        <flux:button type="button" variant="ghost" wire:click="resetSocialForm">
                            Cancel editing
                        </flux:button>
                    @endif
                </div>
            </form>

            <div class="space-y-3">
                <flux:heading size="md">Your social links</flux:heading>

                @forelse ($profile->socialLinks()->get() as $link)
                    <div wire:key="social-link-{{ $link->id }}"
                        class="flex flex-col gap-3 rounded-xl border p-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="min-w-0 space-y-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-medium">
                                    {{ $link->label ?: ucfirst($link->platform) }}
                                </span>

                                @if ($link->is_active)
                                    <flux:badge color="green">Active</flux:badge>
                                @else
                                    <flux:badge color="zinc">Hidden</flux:badge>
                                @endif
                            </div>

                            <div class="text-sm text-zinc-500">
                                {{ ucfirst($link->platform) }}
                                · Order: {{ $link->sort_order }}
                            </div>

                            <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer"
                                class="block break-all text-sm text-blue-600 hover:underline">
                                {{ $link->url }}
                            </a>
                        </div>

                        <div class="flex shrink-0 gap-2">
                            <flux:button size="sm" type="button" wire:click="editSocialLink({{ $link->id }})">
                                Edit
                            </flux:button>

                            <flux:button size="sm" type="button" variant="danger"
                                wire:click="deleteSocialLink({{ $link->id }})"
                                wire:confirm="Are you sure you want to delete this social link?">
                                Delete
                            </flux:button>
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed p-6 text-center">
                        <flux:text>
                            No social links yet. Add your first link above.
                        </flux:text>
                    </div>
                @endforelse
            </div>
        </div>
    </flux:card>

</div>