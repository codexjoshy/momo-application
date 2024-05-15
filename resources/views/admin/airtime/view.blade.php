@extends('layouts.dashboard')
{{-- @section('metrics')
@can('admin')
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        SMS Balance</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">&#8358; {{ number_format($smsBalance['balance'])
                        }}</div>
                </div>
                <div class="col-auto">
                    <i class="fa fa-money fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Airtime Balance</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">&#8358; {{ number_format($airtimeBal) }}</div>
                </div>
                <div class="col-auto">
                    <i class="fa fa-money fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection --}}
@section('content')
<div class="container">
    <div class="row ">
        <x-base.card title="Schedules" class="col-10">
            <x-base.datatable>
                <x-slot name="thead">
                    <tr>
                        <th>S/N</th>
                        <th>Customer Phone</th>
                        <th>Amount</th>
                        <th>status</th>
                        <th>Transaction ID</th>
                        <th>Date</th>
                    </tr>
                </x-slot>
                <x-slot name="tbody">
                    @foreach ($customers as $customer)
                    @php
                    $bg = "btn-warning";
                    if($customer->status == 'success') $bg = "btn-success";
                    if($customer->status == 'fail') $bg = "btn-danger";
                    $sent="";
                    if ($customer->transaction_id) {
                    $sent = $customer->updated_at->format('Y-m-d H:i:s');
                    }
                    @endphp
                    <tr>
                        <td>{{ ++$loop->index }}</td>
                        <td>{{ $customer->phone_no }}</td>
                        <td> ₦{{ number_format($customer->amount?:'', 2) }}</td>
                        <td><span class="bg  btn-sm {{ $bg }} ">{{ $customer->status }}</span> </td>
                        <td>{{ $customer->transaction_id }}</td>
                        <td>
                            <p><small>scheduled @ {{ optional($customer->created_at)->format('Y-m-d H::i::s') }}</small>
                            </p>
                            @if ($sent)
                            <p><small>airtime sent @ {{ $sent }}</small></p>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </x-slot>
            </x-base.datatable>

        </x-base.card>
    </div>
</div>
@endsection