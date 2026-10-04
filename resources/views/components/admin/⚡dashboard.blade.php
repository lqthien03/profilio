<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new
#[Layout('layouts.admin')]
class extends Component
{
    public string $title = 'Dashboard';
};
?>

<div class="space-y-6 p-6">

    <div>
        <flux:heading size="xl">
            {{ $title }}
        </flux:heading>

        <flux:text class="mt-2">
            Welcome to your portfolio CMS.
        </flux:text>
    </div>

</div>