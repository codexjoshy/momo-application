@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row ">
        <x-base.card title="Schedules" class="col-10">
            <x-base.table>
                <x-slot name="thead">
                    <tr><th>S/N</th><th>Title</th><th>Amount</th><th>Message</th><th>Date</th><th>Beneficiaries</th></tr>
                </x-slot>
                <x-slot name="tbody">
                    @foreach ($momoSchedules as $schedule)
                        <tr>
                            <td>{{ ++$loop->index; }}</td>
                            <td>{{ $schedule->title }}</td>
                            <td>{{ number_format($schedule->disbursed_amount, 2) }}</td>
                            <td>{{ $schedule->customer_message }}</td>
                            <td>{{ $schedule->created_at->format('Y-m-d') }}</td>
                            <td class="d-flex justify-content-between">
                                <x-modal-button class="btn-info btn-sm" key="review" id="modalBtn">
                                    view
                                </x-modal-button>
                                <x-modal id="myModal" title="Result" key="review" data-backdrop="static"
                                    openOnFieldError="review_message">
                                    <x-base.datatable tbodyClass="tbody">
                                        <x-slot name="thead">
                                            <tr>
                                                <th>Phone</th>
                                                <th>Amount</th>
                                                <th></th>
                                            </tr>
                                        </x-slot>
                                        <x-slot name="tbody">
                                            @foreach ($schedule->customers as $customer)
                                                <tr>
                                                    <td>{{ $customer->phone_no }}</td>
                                                    <td>{{ number_format($customer->amount, 2) }}</td>
                                                    <td>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </x-slot>
                                    </x-base.datatable>
                                </x-modal>
                                <x-base.form :action="route('admin.schedule.delete', $schedule->id)">
                                    @method('delete')
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </x-base.form>
                            </td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-base.table>
        </x-base.card>
    </div>
</div>
@endsection

