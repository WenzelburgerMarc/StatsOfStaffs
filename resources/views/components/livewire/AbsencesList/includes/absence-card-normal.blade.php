<x-form.field>

    <div class="flex w-full items-center justify-center space-x-2 xs:space-x-5">

        <x-form.field class="absolute top-0 right-6">
            <x-form.text>
                <div class="flex flex-col">
                    <x-form.status text="{{$absence->getStatusName()}}" status="{{$absence->getStatusName()}}"/>
                </div>
            </x-form.text>
        </x-form.field>

        <div class="mt-3 flex flex-col">
            <x-form.field class="rounded-full p-3 dark:bg-slate-100 xs:border xs:border-gray-200 xs:shadow-md">
                <div class="flex items-center justify-center space-x-3">
                    <div>
                        <i class="text-xl text-gray-700 fa-solid fa-hourglass-start"></i>
                    </div>
                    <div class="mr-3 flex flex-col">
                        <x-form.label name="start_date" labeltext="Start Date"/>
                        <x-form.label class="text-gray-500" name="start_date_value"
                                      labeltext="{{ explode(' ', $absence->start_date)[0] }}"/>

                    </div>
                </div>

            </x-form.field>

            @if(substr(explode(' ', $absence->start_date)[1], 0, 5) && substr(explode(' ', $absence->end_date)[1], 0, 5) !== "00:00")
                <x-form.field class="rounded-full p-3 dark:bg-slate-100 xs:border xs:border-gray-200 xs:shadow-md">
                    <div class="flex items-center justify-center space-x-3">
                        <div>
                            <i class="text-xl text-gray-700 fa-regular fa-clock"></i>
                        </div>
                        <div class="mr-3 flex flex-col">
                            <x-form.label name="start_time" labeltext="Start Time" type="time"/>
                            <x-form.label class="text-gray-500" name="start_time_value"
                                          labeltext="{{ substr(explode(' ', $absence->start_date)[1], 0, 5) }}"/>

                        </div>
                    </div>
                </x-form.field>
            @endif
        </div>
        <div class="flex flex-col">
            <x-form.field class="rounded-full p-3 dark:bg-slate-100 xs:border xs:border-gray-200 xs:shadow-md">
                <div class="flex items-center justify-center space-x-3">
                    <div>
                        <i class="text-xl text-gray-700 fa-solid fa-hourglass-end"></i>
                    </div>
                    <div class="ml-3 flex flex-col">
                        <x-form.label name="end_date" labeltext="End Date"/>
                        <x-form.label class="text-gray-500" name="end_date_value"
                                      labeltext="{{ explode(' ', $absence->end_date)[0] }}"/>
                    </div>
                </div>
            </x-form.field>

            @if(substr(explode(' ', $absence->start_date)[1], 0, 5) && substr(explode(' ', $absence->end_date)[1], 0, 5) !== "00:00")
                <x-form.field class="rounded-full p-3 dark:bg-slate-100 xs:border xs:border-gray-200 xs:shadow-md">
                    <div class="flex items-center justify-center space-x-3">
                        <div>
                            <i class="text-xl text-gray-700 fa-regular fa-clock"></i>
                        </div>
                        <div class="ml-3 flex flex-col">
                            <x-form.label name="end_time" labeltext="End Time" type="time"/>
                            <x-form.label class="text-gray-500" name="end_time_value"
                                          labeltext="{{ substr(explode(' ', $absence->end_date)[1], 0, 5) }}"/>
                        </div>
                    </div>
                </x-form.field>
            @endif
        </div>

    </div>

</x-form.field>

<x-form.field
    class="mx-auto w-1/3 min-w-fit rounded-full p-3 dark:bg-slate-100 xs:border xs:border-gray-200 xs:shadow-md">
    <div class="flex items-center justify-center space-x-3">
        <div>
            <i class="text-xl text-gray-700 fa-solid fa-question"></i>
        </div>
        <div class="flex flex-col">
            <x-form.label name="reason"/>
            <x-form.label class="text-gray-500" name="{{$absence->getReasonName()}}"/>
        </div>
    </div>
</x-form.field>

@if(isset($absence->document) || isset($absence->comment))
    <x-form.field class="mx-auto w-full rounded-md p-3 dark:bg-slate-100 xs:border xs:border-gray-200 xs:shadow-md">
        <div class="flex flex-col items-center justify-center space-x-5">
            @if(isset($absence->document))
                <div class="flex items-center justify-center space-x-3">
                    <div>
                        <i class="text-xl text-gray-700 fa-regular fa-file"></i>
                    </div>
                    <div class="flex flex-col">
                        <x-form.label name="document"/>
                        <x-form.link class="text-gray-500 underline"
                                     link="{{route('get-file', ['category' => 'documents', 'filename' => $absence->document])}}">
                            {{ basename($absence->original_document_name) }}
                        </x-form.link>
                    </div>

                </div>
            @endif
            @if(isset($absence->comment) && $absence->comment !== "")
                <div class="w-full p-3 {{isset($absence->document) ? 'mt-3' : ''}}">

                    <div class="flex w-full items-center justify-center space-x-3">
                        <div>
                            <i class="text-xl text-gray-700 fa-regular fa-comment-dots"></i>
                        </div>
                        <div class="flex w-full flex-col">
                            <x-form.label name="comment"/>

                            <x-form.text class="break-words text-gray-500">{{$absence->comment}}</x-form.text>
                        </div>
                    </div>

                </div>
            @endif
        </div>

    </x-form.field>
@endif

@if(!$absenceEditID)
    <x-form.field class="flex w-full items-center justify-end space-x-5">
        @php

            @endphp
        <x-form.icon-button wire:click="edit({{$absence}})" class="text-green-500 hover:text-green-600"
                            icon="fa-solid fa-pen-to-square"/>

        <x-form.icon-button wire:click="delete({{$absence}})" class="text-red-500 hover:text-red-600"
                            icon="fa-solid fa-trash"/>


        <div x-data="{ url: null }">
            <x-form.icon-button wire:click="downloadCSV({{$absence}})"
                                class="text-primary-500 hover:text-primary-600"
                                icon="fa-solid fa-download"/>
        </div>


    </x-form.field>
@endif


<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function () {

        document.addEventListener('downloadAbsence', function (e) {
            const url = e.detail[0].url;

            const filename = '{{$absence->getReasonName()}}_{{$absence->start_date}}_{{$absence->end_date}}.csv';
            const element = document.createElement('a');
            element.setAttribute('href', url);
            element.setAttribute('download', filename);
            element.style.display = 'none';
            document.body.appendChild(element);
            element.click();
            document.body.removeChild(element);
        });

        window.onload = () => {
            Livewire.on('scrollToAbsenceEdit', event => {

                const absenceEditID = event.absenceEditID;
                const absenceEdit = document.getElementById('absence' + absenceEditID);
                requestAnimationFrame(() => {
                    absenceEdit.scrollIntoView({behavior: "smooth", block: "center", inline: "nearest"});
                });

            });
        }
    });

</script>
