<?php

namespace App\Livewire\ChatsList;

use App\Models\Role;
use App\services\ChatService;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class MyChatsList extends Component
{
    use withPagination;

    public $statusSearch;

    public $tmpStatusSearch;

    public $search;

    public $tmpSearch;

    public $roles;

    public $selectedRole;

    public $tmpSelectedRole;

    protected $listeners = ['echo:chat,NewChatMessage' => 'getAllChats'];

    public function mount()
    {
        $this->roles = Role::all();
    }

    public function goToChatRoute($username)
    {

        return redirect()->route('show-chat', ['username' => $username]);
    }

    #[On('myChatsListSetReadStatus')]
    public function setStatusSearch($status)
    {
        if (! isset($status)) {
            $status = null;
        }
        $this->statusSearch = $status;

    }

    #[On('myChatsListRoleSearchEvent')]
    public function setSelectedRole($role)
    {
        $this->selectedRole = $role;
    }

    public function render()
    {
        $chatService = new ChatService();

        $chats = $chatService->getAllChats(auth()->user(), $this->search, $this->statusSearch, $this->selectedRole)->paginate(5);

        if (($this->statusSearch || $this->selectedRole || $this->search) && ($this->statusSearch != $this->tmpStatusSearch || $this->selectedRole != $this->tmpSelectedRole || $this->search != $this->tmpSearch)) {
            $this->tmpSearch = $this->search;
            $this->tmpStatusSearch = $this->statusSearch;
            $this->tmpSelectedRole = $this->selectedRole;
            $this->resetPage();
        }

        $chats->withPath(route('chats'));

        return view('components.livewire.ChatsList.my-chats-list', ['chats' => $chats]);
    }

    public function getAllChats()
    {
        $this->render();
    }
}
