<?php

namespace App\Livewire\NewChatsList;

use App\Models\Role;
use App\services\UserService;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class CreateNewChatsList extends Component
{
    use WithPagination;

    public $search;

    public $tmpSearch;

    public $roles;

    public $selectedRole;

    public $tmpSelectedRole;

    protected $userService;

    public function __construct()
    {

        $this->userService = app(UserService::class);
    }

    public function mount()
    {
        $this->roles = Role::all();
    }

    #[On('newChatsListRoleSearchEvent')]
    public function setSelectedRole($role)
    {
        $this->selectedRole = $role;
    }

    public function render()
    {

        if (($this->search || $this->selectedRole) && ($this->tmpSearch != $this->search || $this->tmpSelectedRole != $this->selectedRole)) {
            $this->tmpSearch = $this->search;
            $this->tmpSelectedRole = $this->selectedRole;
            $this->resetPage();
        }

        $users = $this->userService->getUsers($this->search, $this->selectedRole);
        $users->withPath(route('create-chat'));

        return view('components.livewire.new-chats-list.create-new-chats-list', [
            'users' => $users,
        ]);
    }

    public function goToChatRoute($username)
    {
        return redirect()->route('show-chat', ['username' => $username]);
    }
}
