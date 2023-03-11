<x-base.table id="" class="dataTable">
    <x-slot name="thead">
        {{ $thead ?? '' }}
    </x-slot>

    <x-slot name="tbody">
        {{ $tbody ?? '' }}
    </x-slot>
</x-base.table>

@once
    @include('partials.datatables')
@endonce
