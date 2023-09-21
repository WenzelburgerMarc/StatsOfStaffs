<?php

namespace App\Livewire\EmployeeList;

use App\Models\Role;
use App\Services\UserService;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeeList extends Component
{
    use WithPagination;

    public $search;

    public $tmpSearch;

    public $roles;

    public $selectedRole;

    public $tmpSelectedRole;

    protected $userService;

    public function __construct($id = null)
    {
        $this->userService = app(UserService::class);
    }

    public function mount()
    {
        $this->roles = Role::all();
    }

    #[On('manageEmployeesRoleSearchEvent')]
    public function setRoleSearch($role = '')
    {
        $this->selectedRole = $role;
    }

    public function render()
    {
        if (($this->search || $this->selectedRole) && ($this->search != $this->tmpSearch || $this->selectedRole != $this->tmpSelectedRole)) {
            $this->tmpSearch = $this->search;
            $this->tmpSelectedRole = $this->selectedRole;
            $this->resetPage();
        }

        $users = $this->userService->getUsers($this->search, $this->selectedRole, false);
        $users->withPath(route('manage-employees'));

        return view('components.livewire.employee-list.employee-list', [
            'users' => $users,
        ]);
    }

    public function goToManageEmployee($username)
    {
        return redirect()->route('manage-employee', ['username' => $username]);
    }
}
