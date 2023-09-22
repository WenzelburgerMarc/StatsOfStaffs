<x-default-layout>
    <x-employee-setting :username="$user->username" class="mb-auto">
        <div class="flex w-full flex-col py-5 space-y-5">

            <x-form.vacation-days-overview title="{{$user->username . ' Vacation Days'}}" :employee="$user"/>
            <x-form.panel class="w-full">
                <form action="/admin/manage-employees/{{$user->username}}/absence/create" method="post"
                      enctype="multipart/form-data" class="flex w-full flex-col">
                    @csrf

                    <div class="flex w-full items-center justify-center sm:justify-start">
                        <x-form.title>
                            Create Absence
                        </x-form.title>
                    </div>

                    <x-form.field>

                        <div class="grid min-h-max w-full grid-cols-2 items-start justify-center gap-2">

                            <div class="mr-1">
                                <x-form.input :static-error="true" name="from_date" labeltext="From Date" type="date"/>
                            </div>
                            <div class="ml-1">
                                <x-form.input :static-error="true" name="to_date" labeltext="To Date" type="date"/>
                            </div>


                            <div class="mr-1">
                                <x-form.input :static-error="true" name="from_time" labeltext="From Time" type="time"/>
                            </div>
                            <div class="ml-1">
                                <x-form.input :static-error="true" name="to_time" labeltext="To Time" type="time"/>
                            </div>
                        </div>


                    </x-form.field>

                    <x-form.field>
                        <p class="mx-auto w-full text-center text-sm text-gray-500">
                            If you are absent for a full day, select 00:00 to
                            00:00 as time.
                        </p>
                    </x-form.field>

                    <div class="mt-5 flex items-center justify-center">
                        <x-form.field class="flex w-full items-center justify-center">
                            @php
                                $dropdownReason = [];

                                foreach ($reasons as $reason) {
                                    $dropdownReason[] = ['id' => $reason->id, 'value' => $reason->reason];
                                }
                            @endphp
                            @livewire('form.dropdownMenu', ['name'=>'reason' ,'title' => 'Reason', 'items' => $dropdownReason, 'selectedId' => 1, 'allOption' => false])

                        </x-form.field>

                    </div>
                    <x-form.field>
                        <x-form.file-upload-nonlivewire name="document"/>
                    </x-form.field>

                    <x-form.field>
                        <x-form.textarea name="comment"
                                         placeholder="Please Provide Additional Information About Your Absence"/>
                    </x-form.field>

                    <x-form.field class="mx-auto">
                        <x-form.primary-button type="submit">Request</x-form.primary-button>
                    </x-form.field>

                </form>
            </x-form.panel>
        </div>
    </x-employee-setting>
</x-default-layout>
