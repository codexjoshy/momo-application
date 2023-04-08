@extends('layouts.dashboard')

@section('metrics')
@can('admin')
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        SMS Balance</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">&#8358; {{ number_format($smsBalance['balance']) }}</div>
                </div>
                <div class="col-auto">
                    <i class="fa fa-money fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __("You are logged in! ")}} {{ auth()->user()->name  }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
