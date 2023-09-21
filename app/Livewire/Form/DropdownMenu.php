<?php

namespace App\Livewire\Form;

use Livewire\Component;

class DropdownMenu extends Component
{
    public $title;

    public $name;

    public $items;

    public $selectedId;

    public $selectedValue;

    public $eventName;

    public $allOption = true;

    public function mount($title, $items, $selectedId = null, $emitEvent = null)
    {
        $this->title = $title;
        $this->items = $items;
        $this->selectedId = $selectedId;
        $this->eventName = $emitEvent;

        if ($selectedId) {

            foreach ($items as $item) {
                if ($item['id'] == $selectedId) {
                    $this->selectedValue = $item['value'];
                    $this->setFilter($selectedId, $this->selectedValue);
                    break;
                }
            }
        }
    }

    public function setFilter($id, $value)
    {
        $this->selectedId = $id;
        $this->selectedValue = $value;
        if ($this->eventName) {
            $this->dispatch($this->eventName, $this->selectedId);
        }
    }

    public function resetFilter()
    {
        $this->selectedId = null;
        $this->selectedValue = null;
        if ($this->eventName) {
            $this->dispatch($this->eventName, $this->selectedId);
        }
    }

    public function render()
    {
        return view('components.livewire.form.dropdown-menu');
    }
}
