<div class="mb-auto flex w-full flex-col items-center justify-center py-5 space-y-5 min-w-[280px]">
    @include('components.livewire.time-tracker.includes.change-day-filter')


    @include('components.livewire.time-tracker.includes.record-work')


    @include('components.livewire.time-tracker.includes.records-table')


</div>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function () {

        document.addEventListener('downloadMonthlyReport', function (e) {
            const url = e.detail[0].url;

            const filename = e.detail[0].fileName;
            const element = document.createElement('a');
            element.setAttribute('href', url);
            element.setAttribute('download', filename);
            element.style.display = 'none';
            document.body.appendChild(element);
            element.click();
            document.body.removeChild(element);
        });

    });
</script>
