@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row ">
        <x-base.card title="Create Schedule" class="col-5">
            <x-base.form :action="route('admin.schedule.upload')" enctype="multipart/form-data">
                <x-base.form-group label="Title of schedule" class="col-12" :required="true">
                    <x-base.input name="title" :value="old('name')" placeholder="Enter title" />
                </x-base.form-group>
                <x-base.form-group label="Amount Disbursed" class="col-12" :required="true">
                    <x-base.input id="amount" name="amount" :value="old('amount')" type="number" placeholder="Enter total amount" />
                </x-base.form-group>
                <x-base.form-group label="Customer Message" class="col-12" :required="true">
                    <x-base.input name="customer_message" :value="old('customer_message')" placeholder="Enter message" />
                </x-base.form-group>
                <x-base.form-group label="List of Customers" class="col-12" :required="true">
                    <x-base.input id="upload" name="upload" type="file" :value="old('customer_message')" placeholder="Enter message" />
                </x-base.form-group>

                <x-base.form-group class="d-flex justify-content-center col-12">
                    <x-base.button id="submit" class="btn btn-block btn-primary justify-content-center d-none">
                        Submit
                    </x-base.button>
                </x-base.form-group>
            </x-base.form>
        </x-base.card>
        <x-base.card title="Preview" class="col-6 ml-4">
            <x-slot name="action">
                <a target="_blank" href="{{ asset('sample.csv') }}" class="text-warning">Download Sample file</a>
            </x-slot>
            <x-base.table tbodyClass="tbody">
                <x-slot name='thead'>
                    <tr><th>S/N</th><th>Customer Phone Number</th> <th>Disbursed Amount</th><th></th></tr>
                </x-slot>
                <x-slot name="tbody">

                </x-slot>
            </x-base.table>
            <div id="loading" class="text-center"></div>
        </x-base.card>
    </div>
</div>
@endsection
@push('scripts')
    <script>
        $(function() {
            $('#upload').change(function(e) {
                processCSV(e.target.files);
            })

            function processCSV(files) {
            // var files = $('#studentFile')[0].files;
            if(files.length > 0){
                $('#modalBtn').hide();
                $('#errorLoad').html('');
                var fd = new FormData();
                let totalAmount =  $('#amount').val();
                // Append data
                fd.append('file',files[0]);
                fd.append('_token',@json(csrf_token()));
                fd.append('totalAmount', totalAmount);
                // AJAX request
                $.ajax({
                    url: "{{route('admin.process.customerList')}}",
                    method: 'post',
                    data: fd,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    beforeSend: function(){
                        $('.tbody').html('');
                        $('#submit').addClass('d-none');
                        $('#loading').html(`<h6 class='text-warning'>Preview Loading... kindly hold on</h6>`);
                    },
                    success: function (data){

                        const errors = data?.error;
                        const result = data?.data;
                        const records = data?.records;
                        const errorLength = Object.keys(errors).length ?? 0;
                        const dataLength = Object.keys(result).length ?? 0;
                        if ( errorLength> 0) {

                            $('#submit').hide();
                            let k =0;
                            let tr = "";
                            if (records) {
                                let errorAmt = 0;
                                for (const record of records) {
                                    ++k;
                                    tr += `
                                        <tr>
                                            <td>${record[0]}</td>
                                            <td>${record[1]}</td>
                                            <td class='text-danger'>${errors[k]??''}</td>
                                        </tr>
                                    `;
                                    errorAmt += parseFloat(record[1])??0;
                                }
                                tr += `
                                    <tr>
                                        <td>Total</td><td class='text-warning'><strong>${errorAmt}</strong></td><td></td>
                                    </tr>
                                    <tr>
                                        <td>Amount Posted</td><td class='text-success'><strong>${totalAmount??0.00}</strong></td><td></td>
                                    </tr>
                                `;
                                $('.tbody').html(tr);

                            }
                            alert('There was an error with the selected file, kindly preview the customers list.');
                            $('#submit').hide();

                        }else{
                            $('#submit').removeClass('d-none');
                        }
                        if (errors?.message) {
                            alert(errors?.message);
                            $('#loading').html(`<span><strong class='text-danger'>${errors?.message}</strong></span>`)
                            $('#submit').addClass('d-none');
                        }
                        if (dataLength) {
                            let k = recordAmount = 0;
                            let tr = "";
                            for (const record of result) {
                                ++k;
                                let amount = parseFloat(record?.amount || 0)
                                recordAmount += amount;
                                let formatAmt = new Intl.NumberFormat('en-IN', { maximumSignificantDigits: 3 }).format(amount);

                                tr += `
                                    <tr>
                                        <td>${k}</td>
                                        <td>${record?.phone}</td>
                                        <td>${formatAmt}</td>
                                        <td></td>
                                    </tr>
                                `;
                            }
                            tr += `
                                    <tr>
                                        <td></td>
                                        <td>Total Posted</td>
                                        <td>${new Intl.NumberFormat('en-IN', { maximumSignificantDigits: 3 }).format(recordAmount?.toFixed(2))}</td>
                                        <td></td>
                                    </tr>
                                `;

                            $('.tbody').html(tr);
                            $('#loading').html(`<h6 class='text-success'>successful 🚀 . Click on the submit button to proceed</h6>`);
                            $('#submit').removeClass('d-none');
                        }
                    }
                });
            }
        }
        })
    </script>
@endpush
