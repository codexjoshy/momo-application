@props([
'tbodyClass' => '',
])
<div class="datatable table-responsive">
    <table {{ $attributes->merge(['class' => 'table table-bordered table-hover']) }} width="100%" cellspacing="0">
        <thead>
            <tr>
                {{ $thead ?? '' }}
            </tr>
        </thead>
        <tbody class="{{ $tbodyClass }}">
            {{ $tbody ?? '' }}
        </tbody>
    </table>
</div>
