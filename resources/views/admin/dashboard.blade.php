@extends('admin.layouts.master')
@section('title','Dashboard')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1>Welcome {{Auth::user()->name}} to the Bandung Choral Society Admin!!</h1>
            </div>
        </div>
    </div>
</section>
@endsection