@extends('layouts.datatables')

@section('content1')
    <div class="container mt-3 mb-3">
        <div class="card text-white bg-primary">
        <div class="card-header font-weight-bold text-center">
            <a class="btn btn-success toggle" data-toggle="collapse" href="#alllists" role="button" aria-expanded="false" aria-controls="activedTab" id="serviceopen">All List</a>
            <a class="btn btn-success toggle" data-toggle="collapse" href="#cadangan"  role="button" aria-expanded="false" aria-controls="logTab" id="serviceclose">Cadangan</a>
        </div>

            <div class="card-body bg-light text-dark">

                <div class="collapse show" id="alllists">
                    <div class="container">
                    @if ( Auth::user()->name === "malek" || Auth::user()->name === "dicky")
                        <a href="{{ route('material.create') }}" class="btn btn-success modal-show mb-3" title="Create Material"><i class="fa fa-plus"></i> Create</a>
                    @endif
                        <table id="alllist" class="table table-striped table-bordered dt-responsive" style="width:100%" url="{{route('material.table')}}">
                            <thead>
                                <tr>
                                    <th scope="col">Nama Barang</th>
                                    <th scope="col">Serial</th>
                                    <th scope="col">Jumlah</th>
                                    <th scope="col">Mitra</th>
                                    <th scope="col">Tanggal Keluar</th>
                                    <th scope="col">Tanggal Terima</th>
                                    <th scope="col">Tanggal Pemasangan</th>
                                    <th scope="col">Nojs</th>
                                    <th scope="col">Site</th>
                                    <th scope="col">Teknisi</th>
                                    <th scope="col">Status</th>
                                    @if ( Auth::user()->name === "malek" || Auth::user()->name === "dicky")
                                    <th scope="col">Action</th>
                                    @endif
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <div class="collapse" id="cadangan">
                    <table id="table-cadangan" class="table table-striped table-bordered dt-responsive" style="width:100%" cadangan="{{route('material.cadangan')}}">
                        <thead>
                            <tr>
                                <th scope="col">Nama Barang</th>
                                <th scope="col">Serial</th>
                                <th scope="col">Jumlah</th>
                                <th scope="col">Mitra</th>
                                <th scope="col">Tanggal Keluar</th>
                                <th scope="col">Tanggal Terima</th>
                                <th scope="col">Tanggal Pemasangan</th>
                                <th scope="col">Nojs</th>
                                <th scope="col">Site</th>
                                <th scope="col">Teknisi</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

@include('nojs._modal')

@endsection

@push('scripts1')
    <script type="module" src="{{asset('js/material/material.js')}}" defer></script>
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}" defer></script>
@endpush
