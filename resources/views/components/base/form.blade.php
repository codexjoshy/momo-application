@props(['action' => '', 'method' => 'POST', 'spoof' => '', 'confirm' => true])

<form  method="{{ $method }}" action="{{ $action }}" {{ $attributes }} @if($confirm)
    onsubmit="return confirm('Are you sure?');" @endif>
    @csrf
    @if ($spoof)
    @method($spoof)
    @endif
    {{ $slot }}
</form>
