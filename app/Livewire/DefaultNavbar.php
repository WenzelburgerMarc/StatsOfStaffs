<?php

namespace App\Livewire;

use App\Services\ChatService;
use Livewire\Component;

class DefaultNavbar extends Component
{
    public $unreadCount = 0;

    protected $listeners = ['echo:chat,NewChatMessage' => 'updateUnreadCount', 'updateUnreadCount' => 'updateUnreadCount'];

    public function mount(ChatService $chatService)
    {

        $this->updateUnreadCount($chatService);
    }

    public function updateUnreadCount(ChatService $chatService)
    {
        $this->unreadCount = $chatService->unreadChatsCount(auth()->user());
    }

    public function render()
    {
        return view('components.livewire.default-navbar');
    }
}
