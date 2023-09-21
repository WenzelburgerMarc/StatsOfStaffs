<div x-data="{show: false}" class="nightwind-prevent-block">
    @if($name)
        <input type="hidden" name="{{ $name }}" value="{{ $selectedId }}">
    @endif
    <button @click="show=!show" @click.away="show=false"
            class="inline-flex items-center rounded-lg border border-gray-300 bg-gray-50 px-3 text-sm font-medium text-gray-500 py-1.5 hover:bg-primary-100 focus:border-gray-300 focus:outline-none focus:ring-0 dark:border-gray-800 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-primary-900 dark:focus:border-gray-800"
            type="button">
        {{$selectedValue ? ucfirst($selectedValue) : $title}}
        <i class="ml-1 duration-200 fa-solid fa-chevron-down transition-rotate"
           x-bind:class="show ? 'rotate-180':''"></i>
    </button>
    <div x-cloak x-show="show"
         class="absolute z-10 w-44 overflow-visible rounded-lg bg-white shadow divide-y divide-gray-100 dark:bg-gray-900">
        <ul class="text-sm text-gray-700 dark:text-gray-300">
            @if($allOption)
                <li>
                    <a wire:click.defer="resetFilter"
                       class="block rounded-t-lg px-4 py-2 hover:bg-primary-100 dark:hover:bg-primary-900 cursor-pointer {{!$selectedId ? 'text-primary-600' : ''}}">All</a>
                </li>
            @endif

            @foreach($items as $item)
                <li>
                    <a wire:click.defer="setFilter('{{$item['id']}}', '{{$item['value']}}')"
                       class="{{$items[0] == $item && !$allOption ? 'rounded-t-lg' : ''}} {{end($items) == $item ? 'rounded-b-lg' : ''}} block px-4 py-2 hover:bg-primary-100 dark:hover:bg-primary-900 cursor-pointer {{$selectedId == $item['id'] ? 'text-primary-600' : ''}}">{{ucfirst($item['value'])}}</a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
