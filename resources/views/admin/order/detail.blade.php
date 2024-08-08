@extends('admin.layouts.master')
@section('title', 'Order Detail')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Order Detail ({{$data->no_invoice}})</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{route('order.index')}}" class="btn btn-secondary float-sm-right"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
                            <h3 class="card-title">Order Detail Data ({{$data->no_invoice}})</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="data" class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <td width="5%">#</td>
                                    <td>Sheet Music/Merchandise</td>
                                    <td>Type</td>
                                    <td>Choir</td>
                                    <td class="competition-none">For Competition</td>
                                    <td>Qty</td>
                                    <td>Price</td>
                                    <td>Subtotal</td>
                                </thead>
                                <tbody>
                                    @foreach($data->items as $d)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>@if($d->partitur_id)
                                                {{ $d->partiturDetail->name }}
                                                @else
                                                {{ $d->merchandise->name }}
                                                @endif
                                            </td>
                                            <td>@if($d->partitur_id)
                                                {{ ucfirst($d->partiturDetail->file_type) }}
                                                @else
                                                {{ ucfirst($d->size) }} | {{ ucfirst($d->color) }}
                                                @endif</td>
                                            <td>@if($d->partitur_id)
                                                {{ $d->choir->name }}
                                                @else
                                                -
                                                @endif</td>
                                            <td class="competition-none">{{ $d->for_competition == '1' ? 'Yes' : 'No' }}</td>
                                            <td>{{ $d->quantity }}</td>
                                            <td>{{ number_format($d->total_harga,0,',','.') }}</td>
                                            <td>{{ number_format(($d->quantity *$d->total_harga),0,',','.')  }}</td>
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
@endsection
