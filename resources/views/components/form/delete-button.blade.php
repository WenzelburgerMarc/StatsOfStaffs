@props(['text'])
<button {{$attributes}}
        class="w-fit sm:ml-auto bg-red-500 px-4 py-2 rounded-xl text-white hover:bg-red-600">
    <i class="fa-solid fa-trash mr-1"></i> {{$text}}
</button>
