@extends('admin.layouts.master')
@section('title', 'Order')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Order</h1>
                </div>
            </div>

        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Order Data</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form method="get">
                                <div class="row g-3 align-d->shipments-center">
                                    <div class="col-auto">
                                        <label class="col-form-label">Start Date</label>
                                    </div>
                                    <div class="col-auto">
                                        <input type="date" name="start" class="form-control" value="{{ request('start', date('Y-m-01')) }}">
                                    </div>
                                    <div class="col-auto">
                                        <label for="inputPassword6" class="col-form-label">End Date</label>
                                    </div>
                                    <div class="col-auto">
                                        <input type="date" name="end" class="form-control" value="{{ request('end', date('Y-m-t')) }}">
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                            </form>
                            <br>
                            <table id="data" class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <td width="5%">#</td>
                                    <td>Invoice</td>
                                    <td>Receipt Num</td>
                                    <td>Date</td>
                                    <td>Customer</td>
                                    <td>Address</td>
                                    <td>Total</td>
                                    <td>Status</td>
                                    <td>Action</td>
                                </thead>
                                <tbody>
                                    @foreach($data as $d)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $d->no_invoice }}</td>
                                            <td>{{ $d->no_resi == NULL ? '-' : $d->no_resi }}</td>
                                            <td>{{\Carbon\Carbon::parse($d->date)->format('j M Y')}}</td>
                                            <td>{{ $d->customer->name }}</td>
                                            <td>@if(isset($d->shipment->informasi_tambahan))
                                                {{ $d->shipment->informasi_tambahan }},
                                                {{ $d->shipment->kecamatan }},
                                                {{ $d->shipment->kota }},
                                                {{ $d->shipment->provinsi }},
                                                {{ $d->shipment->kode_pos }},
                                                {{ $d->shipment->negara }}
                                            @else
                                                -
                                            @endif</td>
                                            <td>{{ number_format(($d->total + $d->shipment_fee - $d->voucher),0,',','.') }}</td>
                                            <td><b>@if($d->status == 2) <span class="badge badge-success">Finished</span> @elseif($d->status == 1) <span class="badge badge-warning">In delivery</span> @else <span class="badge badge-info">It's being packaged</span> @endif</td>
                                            <td>
                                                @if($d->no_resi == null)
                                                <button type="button" class="btn btn-primary openSwal" data-id="{{ $d->id }}">Input Receipt</button>
                                                @else
                                                    @if($d->status == 1)
                                                    <button id="doneButton" class="btn btn-success" data-id="{{ $d->id }}">Complete</button>
                                                    @endif
                                                @endif
                                                <a href="{{ route('order.detail', ['id' => $d->id]) }}"
                                                    type="button" class="btn btn-info">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <script>
    $(document).ready(function() {
        $('#doneButton').click(function() {
            let orderId = $(this).data('id'); 
            Swal.fire({
                title: 'Is this order complete?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("order.saveComplete") }}',
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}", // CSRF token
                            id: orderId,
                            receiptNumber: result.value
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false,
                                didClose: () => window.location.reload()
                            });
                        },
                        error: function(response) {
                            Swal.fire('Error!', response.responseJSON.message, 'error');
                        }
                    });
                }
            });
        });

        $('.openSwal').click(function() {
            let orderId = $(this).data('id'); 
            Swal.fire({
                title: 'Enter Receipt Number',
                input: 'text',
                showCancelButton: true,
                inputValidator: (value) => {
                    if (!value) {
                        return 'You need to write something!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    $.ajax({
                        url: '{{ route("order.saveReceiptNumber") }}', // Adjust if you're using the API route
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}", // CSRF token
                            id: orderId,
                            receiptNumber: result.value
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false,
                                didClose: () => window.location.reload()
                            });
                        },
                        error: function(response) {
                            Swal.fire('Error!', response.responseJSON.message, 'error');
                        }
                    });
                }
            });
        });
    });
    </script>

@endsection
