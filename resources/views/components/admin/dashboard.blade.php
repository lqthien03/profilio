<?php

use Livewire\Component;

new class extends Component
{
    public string $title = 'Dashboard';
};
?>

<div class="p-6">

    <flux:heading size="xl">
        {{ $title }}
    </flux:heading>

    <flux:text class="mt-2">
        Welcome to your portfolio CMS.
    </flux:text>

</div>