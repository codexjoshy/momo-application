@props(['title' => '', 'key', 'openOnFieldError' => ''])

<!-- Modal -->
<div class="modal fade" id="{{ $key }}" tabindex="-1" role="dialog" aria-labelledby="{{ $key }}Label" aria-hidden="true" {{ $attributes }}>
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $key }}Label">{{ $title }}</h5>
                <button class="close" type="button" data-dismiss="modal"  aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            @if (isset($footer))
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>


@if ($openOnFieldError)
    @push('scripts')
        <script>
            @error($openOnFieldError)
                $('#{{ $key }}').modal('show');
            @enderror
        </script>
    @endpush
@endif
