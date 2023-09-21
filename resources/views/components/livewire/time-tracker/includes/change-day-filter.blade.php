<div class="flex w-full items-center justify-between space-x-3">
    <button wire:click="changeDay(-1)"
            class="px-2 py-1 text-black {{\Carbon\Carbon::parse($currentDate)->subDay()->toDateString() == \Carbon\Carbon::today()->toDateString() ? 'bg-primary-200' : 'bg-slate-200'}} rounded-md focus:outline-none">
        <i class="fas fa-arrow-left"></i>
    </button>

    <input id="selectDateInput" name="selectDate" type="date" class="hidden" onclick="this.showPicker()"
           value="{{ \Carbon\Carbon::parse($currentDate)->format('Y-m-d') }}" wire:model.live="currentDate"/>

    <label for="selectDateInput" onclick="openDatePicker()"
           class="text-xl p-3 cursor-pointer rounded-md {{\Carbon\Carbon::today()->toDateString() == \Carbon\Carbon::parse($currentDate)->toDateString() ? 'bg-primary-200' : 'bg-slate-200'}} ">
        {{ \Carbon\Carbon::parse($currentDate)->format('d.m.Y') }}
    </label>

    <button wire:click="changeDay(1)"
            class="px-2 py-1 text-black {{\Carbon\Carbon::parse($currentDate)->addDay()->toDateString() == \Carbon\Carbon::today()->toDateString() ? 'bg-primary-200' : 'bg-slate-200'}} rounded-md focus:outline-none">
        <i class="fas fa-arrow-right"></i>
    </button>
</div>

<script type="text/javascript">
    function openDatePicker() {
        let inputElement = document.getElementById('selectDateInput');
        inputElement.click();
    }

</script>
