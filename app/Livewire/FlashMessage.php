<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class FlashMessage extends Component
{
    #[On('sendFlashMessage')]
    public function sendFlashMessage($type, $message)
    {
        session()->forget(['success', 'error', 'info']);

        session()->flash($type, $message);
    }

    public function render()
    {

        return view('components.livewire.flash-message');
    }
}
